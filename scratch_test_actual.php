<?php

require 'vendor/autoload.php';

use Symfony\Component\Console\Output\BufferedOutput;

$b = new BufferedOutput();
$b->setDecorated(true);
$b->writeln("  <fg=gray></><fg=gray>2026-11-01</> <fg=black;bg=cyan;options=bold> GET </> <fg=black;bg=green;options=bold> 200 OK </> <fg=gray></>");
$out = $b->fetch();
echo "BufferedOutput JSON: " . json_encode($out) . "\n";
