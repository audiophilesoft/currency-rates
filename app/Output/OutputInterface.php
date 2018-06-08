<?php
declare(strict_types=1);

namespace App\Output;


interface OutputInterface
{
    public function writeMessage(string $message);

}