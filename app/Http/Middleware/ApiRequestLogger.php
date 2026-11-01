<?php

namespace App\Http\Middleware;

use App\Support\ClientDetector;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiRequestLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->attributes->set('_request_start_time', microtime(true));

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        if ($this->shouldIgnore($request)) {
            return;
        }

        $date = now()->format('Y-m-d H:i:s');
        $method = strtoupper($request->method());
        $uri = $request->getRequestUri();
        $statusCode = $response->getStatusCode();
        $statusText = $this->getStatusText($statusCode);
        $durationMs = $this->calculateDuration($request);
        $clientInfo = ClientDetector::detect($request->userAgent());
        $ip = $request->ip() ?? '127.0.0.1';
        $user = $this->resolveUser($request);

        $useColors = (bool) config('request-logger.colors', true);

        $consoleLine = $this->buildConsoleLine(
            date: $date,
            method: $method,
            uri: $uri,
            statusCode: $statusCode,
            statusText: $statusText,
            durationMs: $durationMs,
            client: $clientInfo['display'],
            ip: $ip,
            user: $user,
            useColors: $useColors
        );

        $this->writeToConsole($consoleLine);

        $plainLine = $useColors ? $this->stripAnsiColors($consoleLine) : $consoleLine;

        $this->writeToLog($plainLine, [
            'date' => $date,
            'method' => $method,
            'uri' => $uri,
            'status_code' => $statusCode,
            'duration_ms' => $durationMs,
            'client' => $clientInfo['display'],
            'browser' => $clientInfo['browser'],
            'platform' => $clientInfo['platform'],
            'device' => $clientInfo['device'],
            'ip' => $ip,
            'user' => $user,
        ]);
    }

    /**
     * Determine if the request should be skipped by the logger.
     */
    protected function shouldIgnore(Request $request): bool
    {
        if (! config('request-logger.enabled', true)) {
            return true;
        }

        $ignorePaths = config('request-logger.ignore_paths', []);

        foreach ($ignorePaths as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate the request duration in milliseconds.
     */
    protected function calculateDuration(Request $request): float
    {
        $start = defined('LARAVEL_START')
            ? LARAVEL_START
            : (float) $request->attributes->get('_request_start_time', microtime(true));

        return round((microtime(true) - $start) * 1000, 2);
    }

    /**
     * Get a short, clean description of the HTTP status code.
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

    /**
     * Build the formatted log string for console output.
     */
    protected function buildConsoleLine(
        string $date,
        string $method,
        string $uri,
        int $statusCode,
        string $statusText,
        float $durationMs,
        string $client,
        string $ip,
        ?string $user,
        bool $useColors
    ): string {
        $formattedDuration = sprintf('%.2fms', $durationMs);
        $paddedMethod = str_pad($method, 6, ' ', STR_PAD_BOTH);
        $userSuffix = $user ? " ({$user})" : '';

        if (! $useColors) {
            return "{$date}  [{$method}] {$uri}  [{$statusCode} {$statusText}]  ~ {$formattedDuration}  [{$client}]  {$ip}{$userSuffix}";
        }

        // Action-wise method badge colors
        $methodStyle = match ($method) {
            'GET' => 'black;bg=cyan;options=bold',
            'POST' => 'black;bg=green;options=bold',
            'PUT', 'PATCH' => 'black;bg=yellow;options=bold',
            'DELETE' => 'white;bg=red;options=bold',
            default => 'white;bg=gray;options=bold',
        };

        // Response-wise status badge colors
        $statusStyle = match (true) {
            $statusCode >= 200 && $statusCode < 300 => 'black;bg=green;options=bold',
            $statusCode >= 300 && $statusCode < 400 => 'black;bg=cyan;options=bold',
            $statusCode >= 400 && $statusCode < 500 => 'black;bg=yellow;options=bold',
            $statusCode >= 500 => 'white;bg=red;options=bold',
            default => 'white;bg=gray;options=bold',
        };

        // Duration latency colors
        $durationColor = match (true) {
            $durationMs < 100 => 'bright-green;options=bold',
            $durationMs < 500 => 'bright-yellow;options=bold',
            default => 'bright-red;options=bold',
        };

        $userPart = $user ? " <fg=bright-blue;options=bold>({$user})</>" : '';

        return "<fg=gray>{$date}</>  <fg={$methodStyle}> {$paddedMethod} </> <fg=bright-white;options=bold>{$uri}</>  <fg={$statusStyle}> {$statusCode} {$statusText} </>  <fg={$durationColor}>~ {$formattedDuration}</>  <fg=bright-magenta;options=bold>[{$client}]</>  <fg=cyan>{$ip}</>{$userPart}";
    }

    /**
     * Write the formatted line to standard output for console display.
     */
    protected function writeToConsole(string $line): void
    {
        if (app()->runningUnitTests() || ! config('request-logger.console', true)) {
            return;
        }

        try {
            if (defined('STDOUT') && is_resource(STDOUT)) {
                fwrite(STDOUT, $line.PHP_EOL);

                return;
            }

            $stdout = @fopen('php://stdout', 'w');
            if ($stdout !== false) {
                fwrite($stdout, $line.PHP_EOL);
                fclose($stdout);

                return;
            }

            @file_put_contents('php://stdout', $line.PHP_EOL);
        } catch (\Throwable) {
            // Keep request flow intact even if console output stream is unavailable
        }
    }

    /**
     * Write the log entry to Laravel's logging subsystem.
     *
     * @param  array<string, mixed>  $context
     */
    protected function writeToLog(string $message, array $context): void
    {
        if (! config('request-logger.log_file', true)) {
            return;
        }

        $channel = config('request-logger.channel');

        try {
            if ($channel) {
                Log::channel($channel)->info($message, $context);
            } else {
                Log::info($message, $context);
            }
        } catch (\Throwable) {
            // Log exceptions should never disrupt request delivery
        }
    }

    /**
     * Strip ANSI color escape sequences from a string.
     */
    protected function stripAnsiColors(string $value): string
    {
        $clean = strip_tags($value);

        return (string) preg_replace('/\033\[[0-9;]*m/', '', $clean);
    }
}
