<?php

namespace App\Console\Commands;

use App\Support\HttpConsoleLog;
use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Enhanced `php artisan serve` with decorated, colored HTTP request logging.
 */
#[AsCommand(name: 'serve', description: 'Serve the application on the PHP development server with decorated colored logs')]
class ServeCommand extends BaseServeCommand
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on the PHP development server with decorated colored logs';

    private ?string $lastPrintedId = null;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 1. Enable Windows VT100 virtual terminal processing for native ANSI color rendering
        if (function_exists('sapi_windows_vt100_support')) {
            @sapi_windows_vt100_support(STDOUT, true);
            @sapi_windows_vt100_support(STDERR, true);
        }

        // 2. Ensure console output is set to decorated mode
        if ($this->output) {
            $this->output->setDecorated(true);
            if (method_exists($this->output, 'getOutput')) {
                $this->output->getOutput()->setDecorated(true);
            }
        }

        $workers = max(
            1,
            (int) env('SERVE_WORKERS', env('PHP_CLI_SERVER_WORKERS', 1))
        );

        if ($workers > 1 && function_exists('pcntl_fork')) {
            putenv('PHP_CLI_SERVER_WORKERS=' . $workers);
            $_ENV['PHP_CLI_SERVER_WORKERS'] = (string) $workers;
            $_SERVER['PHP_CLI_SERVER_WORKERS'] = (string) $workers;
            $this->components->info("Starting PHP CLI server with {$workers} workers (pcntl).");
        }

        $environmentFile = $this->option('env')
            ? base_path('.env') . '.' . $this->option('env')
            : base_path('.env');

        $hasEnvironment = file_exists($environmentFile);

        $environmentLastModified = $hasEnvironment
            ? filemtime($environmentFile)
            : now()->addDays(30)->getTimestamp();

        $process = $this->startProcess($hasEnvironment);

        while ($process->isRunning()) {
            if ($hasEnvironment) {
                clearstatcache(false, $environmentFile);
            }

            if (
                !$this->option('no-reload') &&
                $hasEnvironment &&
                filemtime($environmentFile) > $environmentLastModified
            ) {
                $environmentLastModified = filemtime($environmentFile);

                $this->newLine();
                $this->components->info('Environment modified. Restarting server...');
                $process->stop(5);
                $this->serverRunningHasBeenDisplayed = false;
                $process = $this->startProcess($hasEnvironment);
            }

            $this->drainBridgeLogs();
            usleep(100 * 1000);
        }

        $status = $process->getExitCode();
        if ($status !== 0) {
            $this->components->error('Server failed to start.');
        }

        return $status ?? 0;
    }

    /**
     * Drain and print all buffered HTTP log entries in FIFO order.
     */
    private function drainBridgeLogs(): void
    {
        foreach (HttpConsoleLog::pullAll() as $record) {
            $this->printFromRecord($record);
        }
    }

    /**
     * Hook into the server process output stream.
     *
     * @return callable(string, string): void
     */
    protected function handleProcessOutput()
    {
        return fn($type, $buffer) => str($buffer)->explode("\n")->each(function ($line) {
            $line = rtrim((string) $line);
            if ($line === '') {
                return;
            }

            if (str($line)->contains('Development Server (http')) {
                if ($this->serverRunningHasBeenDisplayed) {
                    return;
                }

                $this->components->info("Server running on [http://{$this->host()}:{$this->port()}].");
                $this->comment('  <fg=yellow;options=bold>Press Ctrl+C to stop the server</>');
                $this->newLine();
                $this->serverRunningHasBeenDisplayed = true;

                return;
            }

            if (str($line)->contains(' Accepted')) {
                try {
                    $requestPort = $this->getRequestPortFromLine($line);
                    $this->requestsPool[$requestPort] = [
                        $this->getDateFromLine($line),
                        true,
                    ];
                } catch (\Throwable) {
                    // ignore malformed Accepted lines
                }

                $this->drainBridgeLogs();

                return;
            }

            if (str($line)->contains(' Closing')) {
                try {
                    unset($this->requestsPool[$this->getRequestPortFromLine($line)]);
                } catch (\Throwable) {
                    // ignore
                }

                $this->drainBridgeLogs();

                return;
            }

            if (str($line)->contains(['Closed without sending a request', 'Failed to poll event', 'favicon.ico'])) {
                return;
            }

            $this->drainBridgeLogs();

            if (str_contains($line, '❌ ERROR')) {
                $this->output->writeln('  ' . $line);
            }
        });
    }

    /**
     * Format and print a request log record with rich ANSI colors.
     *
     * @param  array<string, mixed>  $record
     */
    private function printFromRecord(array $record): void
    {
        $id = isset($record['id']) ? (string) $record['id'] : null;
        if ($id !== null && $id === $this->lastPrintedId) {
            return;
        }
        if ($id !== null) {
            $this->lastPrintedId = $id;
        }

        if (!$this->output->isDecorated()) {
            $this->output->setDecorated(true);
        }

        $format = config('request-logger.format', env('REQUEST_LOGGER_FORMAT', 'badge'));

        if ($format === 'nestjs') {
            $this->printNestJsRecord($record);
        } elseif ($format === 'both') {
            $this->printBadgeRecord($record);
            $this->printNestJsRecord($record, true);
        } else {
            // Default: 'badge' - The requested vibrant decorated single line
            $this->printBadgeRecord($record);
        }

        $this->printProblemDiagnostics($record);
    }

    /**
     * Print the modern decorated badge line.
     *
     * @param  array<string, mixed>  $record
     */
    private function printBadgeRecord(array $record): void
    {
        $date = (string) ($record['date'] ?? ($record['timestamp'] ?? now()->format('Y-m-d H:i:s')));
        $method = strtoupper((string) ($record['method'] ?? 'GET'));
        $path = (string) ($record['path'] ?? '/');
        $status = (int) ($record['status'] ?? 200);
        $statusText = strtoupper((string) ($record['status_text'] ?? 'OK'));
        $durationMs = (float) ($record['duration_ms'] ?? 0.0);
        $client = (string) ($record['client'] ?? 'Unknown Client');
        $ip = (string) ($record['ip'] ?? '127.0.0.1');
        $user = !empty($record['user']) ? (string) $record['user'] : null;
        $msFormatted = number_format($durationMs, 2, '.', '');

        $useColor = config('request-logger.colors', true)
            && getenv('NO_COLOR') === false
            && empty($_SERVER['NO_COLOR']);

        if (!$useColor) {
            $userPart = $user ? " ({$user})" : '';
            $this->output->writeln(" {$date}  {$method} {$path}  {$status} {$statusText}  ~ {$msFormatted}ms  [{$client}]  {$ip}{$userPart}");

            return;
        }

        $reset = "\033[0m";

        // 1. Date: Dim/Gray
        $datePart = "\033[90m{$date}{$reset}";

        // 2. HTTP Method Badge (padded to 8 chars: '  GET   ', '  POST  ', '  PUT   ', ' PATCH  ', ' DELETE ')
        $methodBadge = match ($method) {
            'GET' => "\033[1;30;46m  GET   {$reset}",
            'POST' => "\033[1;30;42m  POST  {$reset}",
            'PUT' => "\033[1;30;43m  PUT   {$reset}",
            'PATCH' => "\033[1;30;43m PATCH  {$reset}",
            'DELETE' => "\033[1;97;41m DELETE {$reset}",
            default => "\033[1;97;100m  {$method}  {$reset}",
        };

        // 3. Request Path: Bright White Bold
        $pathPart = "\033[1;97m{$path}{$reset}";

        // 4. HTTP Status Badge
        $statusBadge = match (true) {
            $status >= 200 && $status < 300 => "\033[1;30;42m {$status} {$statusText} {$reset}",
            $status >= 300 && $status < 400 => "\033[1;30;46m {$status} {$statusText} {$reset}",
            $status >= 400 && $status < 500 => "\033[1;30;43m {$status} {$statusText} {$reset}",
            $status >= 500 => "\033[1;97;41m {$status} {$statusText} {$reset}",
            default => "\033[1;97;100m {$status} {$statusText} {$reset}",
        };

        // 5. Latency Badge
        $durationBadge = match (true) {
            $durationMs < 100 => "\033[1;92m~ {$msFormatted}ms{$reset}",
            $durationMs < 500 => "\033[1;93m~ {$msFormatted}ms{$reset}",
            default => "\033[1;91m~ {$msFormatted}ms{$reset}",
        };

        // 6. Client User Agent Badge
        $clientBadge = "\033[1;95m[{$client}]{$reset}";

        // 7. IP Address
        $ipBadge = "\033[36m{$ip}{$reset}";

        // 8. User (if authenticated)
        $userPart = $user ? " \033[1;94m({$user}){$reset}" : '';

        $this->output->writeln(" {$datePart}  {$methodBadge} {$pathPart}  {$statusBadge}  {$durationBadge}  {$clientBadge}  {$ipBadge}{$userPart}");
    }

    /**
     * Print classic NestJS-style access or info line.
     *
     * @param  array<string, mixed>  $record
     */
    private function printNestJsRecord(array $record, bool $infoOnly = false): void
    {
        $method = strtoupper((string) ($record['method'] ?? 'REQ'));
        $path = (string) ($record['path'] ?? '/');
        $status = (int) ($record['status'] ?? 0);
        $durationMs = (float) ($record['duration_ms'] ?? 0.0);
        $ms = number_format($durationMs, 3, '.', '');
        $size = $record['size'] ?? 0;
        $timestamp = (string) ($record['timestamp'] ?? ($record['date'] ?? now()->format('Y-m-d H:i:s')));
        $statusLabel = $status > 0 ? (string) $status : '---';

        $useColor = config('request-logger.colors', true)
            && getenv('NO_COLOR') === false
            && empty($_SERVER['NO_COLOR']);

        $reset = "\033[0m";

        if (!$infoOnly) {
            $access = sprintf('%s %s %s %s ms - -', $method, $path, $statusLabel, $ms);
            $color = match (true) {
                $status >= 500 || !empty($record['is_login_page']) => "\033[1;91m",
                $status >= 400 => "\033[1;93m",
                default => "\033[1;92m",
            };
            $accessLine = $useColor ? "  {$color}{$access}{$reset}" : "  {$access}";
            $this->output->writeln($accessLine);
        }

        $info = sprintf(
            'info: Incoming Request %s %s %s %d ms {"size":%s,"timestamp":"%s"}',
            $method,
            $statusLabel,
            $path,
            (int) round($durationMs),
            json_encode($size),
            $timestamp
        );
        $infoLine = $useColor ? "  \033[90m{$info}{$reset}" : "  {$info}";
        $this->output->writeln($infoLine);
    }

    /**
     * Print error diagnostics if request encountered an error or exception.
     *
     * @param  array<string, mixed>  $record
     */
    private function printProblemDiagnostics(array $record): void
    {
        $status = (int) ($record['status'] ?? 200);
        $statusText = strtoupper((string) ($record['status_text'] ?? 'ERROR'));
        $isProblem = $status >= 400
            || !empty($record['is_login_page'])
            || !empty($record['exception']);

        if (!$isProblem) {
            return;
        }

        $method = strtoupper((string) ($record['method'] ?? 'GET'));
        $path = (string) ($record['path'] ?? '/');
        $durationMs = (float) ($record['duration_ms'] ?? 0.0);

        $payload = [
            'name' => $record['exception'] ?? 'Error',
            'message' => $record['message'] ?? 'Request failed',
            'statusCode' => $status,
            'path' => parse_url($path, PHP_URL_PATH) ?: $path,
            'method' => $method,
            'duration_ms' => $durationMs,
        ];

        if (!empty($record['cause'])) {
            $payload['cause'] = $record['cause'];
        }
        if (!empty($record['sql'])) {
            $payload['sql'] = $record['sql'];
        }
        if (!empty($record['errors'])) {
            $payload['errors'] = $record['errors'];
        }
        if (!empty($record['is_login_page'])) {
            $payload['responseType'] = 'html-login-page';
        }
        if (!empty($record['stack'])) {
            $payload['stack'] = $record['stack'];
        }

        $useColor = config('request-logger.colors', true)
            && getenv('NO_COLOR') === false
            && empty($_SERVER['NO_COLOR']);

        $reset = "\033[0m";
        $header = $useColor ? "  \033[1;91m❌ ERROR: {$status} {$statusText}\033[0m" : "  ❌ ERROR: {$status} {$statusText}";
        $this->output->writeln($header);

        $jsonLines = explode("\n", json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: '{}');
        foreach ($jsonLines as $jsonLine) {
            $formattedLine = $useColor ? "  \033[31m{$jsonLine}{$reset}" : "  {$jsonLine}";
            $this->output->writeln($formattedLine);
        }
    }
}
