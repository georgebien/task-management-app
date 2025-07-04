<?php

namespace App\Console;

use Symfony\Component\Console\Output\ConsoleOutput;

class ConsoleLogger
{
    protected ConsoleOutput $output;

    public function __construct()
    {
        $this->output = new ConsoleOutput();
    }

    public function info(string $message): void
    {
        $this->output->writeln("<fg=green>[{$this->timestamp()}] INFO:</> $message");
    }

    public function error(string $message): void
    {
        $this->output->writeln("<fg=red>[{$this->timestamp()}] ERROR:</> $message");
    }

    private function timestamp(): string
    {
        return now()->format('Y-m-d H:i:s');
    }
}