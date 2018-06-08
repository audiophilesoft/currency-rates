<?php
declare(strict_types=1);

namespace App\Task;


class Task
{
    protected $code;
    protected $name;
    protected $call;

    public function __construct(callable $call, int $code, string $name = null)
    {
        $this->call = $call;
        $this->code = $code;
        $this->name = $name;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function run()
    {
        return ($this->call)();
    }


    public function setCode(int $code): void
    {
        $this->code = $code;
    }


    public function getName(): string
    {
        return $this->name;
    }


    public function setName(string $name): void
    {
        $this->name = $name;
    }

}