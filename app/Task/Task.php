<?php

declare(strict_types=1);

namespace App\Task;

class Task
{
    protected string $id;
    protected string $name;
    protected \Closure $function;
    protected int $statusCode;
    protected string $error;

    public const CODE_RUNNING = 1;
    public const CODE_FINISHED = 2;
    public const CODE_FAIL = 3;

    public function __construct(callable $function, string $id, string $name = null)
    {
        $this->function = $function;
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return mixed|null
     */
    public function run()
    {
        try {
            $this->statusCode = self::CODE_RUNNING;
            $result = ($this->function)();
            $this->statusCode = self::CODE_FINISHED;
        } catch (\Throwable $th) {
            $this->statusCode = self::CODE_FAIL;
            $this->error = $th->getMessage();
        }

        return $result ?? null;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
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