<?php
declare(strict_types=1);

namespace App;

class Console
{
    public function writeMessage(string $message): void
    {
        echo $message . PHP_EOL;
    }

}