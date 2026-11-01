<?php

namespace App\Http\Middleware;

use App\Support\ClientDetector;
use App\Support\HttpConsoleLog;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiRequestLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        $request->attributes->set('_request_start_time', $start);

        try {
            $response = $next($request);
        } catch (Throwable $e) {
            $durationMs = (microtime(true) - $start) * 1000;
            $this->persist($request, null, $durationMs, $e);
            throw $e;
        }

        $durationMs = (microtime(true) - $start) * 1000;
        $this->persist($request, $response, $durationMs);

        return $response;
    }

    /**
     * Persist request record to HttpConsoleLog and application logs.
     */
    protected function persist(Request $request, ?Response $response, float $durationMs, ?Throwable $exception = null): void
    {
        if ($this->shouldIgnore($request)) {
            return;
        }

        $date = now()->format('Y-m-d H:i:s');
        $method = strtoupper($request->method());
        $path = $request->getRequestUri();

        $statusCode = $exception
            ? (method_exists($exception, 'getStatusCode') ? (int) $exception->getStatusCode() : 500)
            : (int) ($response?->getStatusCode() ?? 200);

        $statusText = $this->getStatusText($statusCode);
        $clientInfo = ClientDetector::detect($request->userAgent());
        $ip = $request->ip() ?? '127.0.0.1';
        $user = $this->resolveUser($request);
        $size = $response ? $this->contentSize($response) : 0;
        $ms = round($durationMs, 2);

        $body = $response?->getContent();
        $bodyStr = is_string($body) ? $body : '';
        $snip = strlen($bodyStr) > 8000 ? substr($bodyStr, 0, 8000) : $bodyStr;
        $decoded = $snip !== '' ? json_decode($snip, true) : null;
        $isHtml = $this->looksLikeHtml($snip);
        $isLoginPage = $isHtml && (
            str_contains($snip, 'Sign-in to Dashboard')
            || str_contains($snip, '/login"')
        );

        $message = null;
        $errors = null;
        $cause = null;
        $exceptionName = $exception ? class_basename($exception) : null;
        $stack = null;
        $sql = null;

        if ($exception) {
            $message = $this->friendlyExceptionMessage($exception);
            $cause = class_basename($exception);
            $stack = $message."\n".$this->shortStack($exception);
            if ($exception instanceof QueryException) {
                $sql = $exception->getSql();
            }
        } elseif (is_array($decoded)) {
            $message = $decoded['message'] ?? ($decoded['errorMessages'][0]['message'] ?? null);
            $errors = $decoded['errorMessages'] ?? ($decoded['errors'] ?? null);
        } elseif ($isLoginPage) {
            $message = 'Redirected to login page';
            $cause = 'Unauthenticated redirect to /login';
        } elseif ($statusCode >= 400) {
            $message = 'Request failed with status '.$statusCode;
        }

        $record = [
            'id' => (string) microtime(true),
            'date' => $date,
            'method' => $method,
            'path' => $path,
            'status' => $statusCode,
            'status_text' => $statusText,
            'duration_ms' => $ms,
            'client' => $clientInfo['display'],
            'ip' => $ip,
            'user' => $user,
            'size' => $size,
            'is_api' => $request->is('api/*'),
            'is_html' => $isHtml,
            'is_login_page' => $isLoginPage,
            'message' => $message,
            'cause' => $cause,
            'errors' => $errors,
            'exception' => $exceptionName,
            'stack' => $stack,
            'sql' => $sql,
        ];

        // 1. Write to the fast bridge for ServeCommand console output
        HttpConsoleLog::write($record);

        // 2. Also write to Laravel application log
        if (config('request-logger.log_file', true)) {
            $logMessage = "{$date} {$method} {$path} {$statusCode} {$statusText} ~ {$ms}ms [{$clientInfo['display']}] {$ip}".($user ? " ({$user})" : '');

            $logContext = [
                'method' => $method,
                'uri' => $path,
                'path' => $path,
                'status_code' => $statusCode,
                'status' => $statusCode,
                'duration_ms' => $ms,
                'ip' => $ip,
                'client' => $clientInfo['display'],
                'browser' => $clientInfo['browser'],
                'platform' => $clientInfo['platform'],
                'device' => $clientInfo['device'],
                'user' => $user,
            ];

            if ($statusCode >= 400 || $exception) {
                Log::warning($logMessage, array_filter(array_merge($logContext, [
                    'message' => $message,
                    'cause' => $cause,
                    'sql' => $sql,
                ])));
            } else {
                Log::info($logMessage, $logContext);
            }
        }
    }

    /**
     * Determine if the request should be skipped by the logger.
     */
    protected function shouldIgnore(Request $request): bool
    {
        if (! config('request-logger.enabled', true)) {
            return true;
        }

        $ignorePaths = config('request-logger.ignore_paths', ['favicon.ico', '*.ico', 'up']);

        foreach ($ignorePaths as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a short description of the HTTP status code.
     */
    protected function getStatusText(int $statusCode): string
    {
        $standard = Response::$statusTexts[$statusCode] ?? 'UNKNOWN';

        return match ($statusCode) {
            422 => 'UNPROCESSABLE',
            500 => 'SERVER ERROR',
            default => strtoupper($standard),
        };
    }

    /**
     * Resolve the identifier for an authenticated user if available.
     */
    protected function resolveUser(Request $request): ?string
    {
        if (! config('request-logger.include_user', true)) {
            return null;
        }

        $user = $request->user();

        if ($user) {
            return (string) ($user->email ?? $user->name ?? "ID: {$user->getAuthIdentifier()}");
        }

        $jwtPayload = $request->attributes->get('jwt_payload');

        if (is_object($jwtPayload)) {
            if (! empty($jwtPayload->email)) {
                return (string) $jwtPayload->email;
            }

            $userId = $jwtPayload->id ?? ($jwtPayload->userId ?? null);

            if ($userId) {
                return "ID: {$userId}";
            }
        }

        return null;
    }

    private function friendlyExceptionMessage(Throwable $e): string
    {
        if ($e instanceof QueryException) {
            return $e->getMessage();
        }

        $message = $e->getMessage();

        return $message !== '' ? $message : 'Unhandled exception';
    }

    private function shortStack(Throwable $e): string
    {
        $lines = explode("\n", $e->getTraceAsString());
        $appLines = array_values(array_filter($lines, static function (string $line): bool {
            return str_contains($line, DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR)
                || str_contains($line, '/app/');
        }));

        $picked = $appLines !== [] ? array_slice($appLines, 0, 8) : array_slice($lines, 0, 5);

        return implode("\n", $picked);
    }

    private function looksLikeHtml(string $body): bool
    {
        $trim = ltrim($body);

        return $trim !== '' && (
            str_starts_with($trim, '<!DOCTYPE')
            || str_starts_with($trim, '<html')
            || str_starts_with($trim, '<HTML')
        );
    }

    private function contentSize(Response $response): int
    {
        $content = $response->getContent();

        if ($content === false || $content === null) {
            return 0;
        }

        return strlen($content);
    }
}
