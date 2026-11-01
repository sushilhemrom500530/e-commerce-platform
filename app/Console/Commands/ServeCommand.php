<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;
use Illuminate\Support\Stringable;

class ServeCommand extends BaseServeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on the PHP development server with clean colored logs';

    /**
     * Flush the output buffer.
     *
     * @return void
     */
    protected function flushOutputBuffer()
    {
        $lines = (new Stringable($this->outputBuffer))->explode("\n");

        $this->outputBuffer = (string) $lines->pop();

        $lines
            ->map(fn ($line) => trim($line))
            ->filter()
            ->each(function ($line) {
                if ((new Stringable($line))->contains('Development Server (http')) {
                    if ($this->serverRunningHasBeenDisplayed === false) {
                        $this->serverRunningHasBeenDisplayed = true;

                        $this->components->info("Server running on [http://{$this->host()}:{$this->port()}].");
                        $this->comment('  <fg=yellow;options=bold>Press Ctrl+C to stop the server</>');

                        $this->newLine();
                    }

                    return;
                }

                // Suppress PHP's internal connection events and duplicate closing lines
                if ((new Stringable($line))->contains([' Accepted', ' Closing', 'URI:', ' [200]: GET '])) {
                    return;
                }

                if ((new Stringable($line))->contains(['Closed without sending a request', 'Failed to poll event', 'favicon.ico'])) {
                    return;
                }

                if (! empty($line)) {
                    if ((new Stringable($line))->startsWith('[')) {
                        $line = (new Stringable($line))->after('] ');
                    }

                    // Directly write with full color support without overriding styles
                    $this->output->writeln("  $line");
                }
            });
    }
}
