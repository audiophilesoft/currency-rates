<?php
declare(strict_types=1);

namespace App\Task;


class Factory
{

    public function create(int $code, string $name = null): Task
    {
        return new Task($code, $name);
    }

}