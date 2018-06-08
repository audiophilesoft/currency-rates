<?php
declare(strict_types=1);

namespace App\Output;

class Console implements OutputInterface
{
    public function writeMessage(string $message): void
    {
        echo $message . PHP_EOL;
    }

}