<?php
declare(strict_types=1);

namespace App\Task;


class TaskFactory
{
    public function create(callable $call, string $code, string $name = null): Task
    {
        return new Task($call, $code, $name);
    }

}