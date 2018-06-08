<?php
declare(strict_types=1);

namespace App\Task;


interface HandlerInterface
{
    public function run(Task $task): string;
}