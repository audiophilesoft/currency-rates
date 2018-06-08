<?php
declare(strict_types=1);

namespace App\Task;


class Task
{
    protected $id;
    protected $name;
    protected $function;
    protected $status_code = 0;
    protected $error;


    public const CODE_RUNNING = 1;
    public const CODE_FINISHED = 2;
    public const CODE_FAIL = 3;

    public function __construct(callable $function, string $id, string $name = null)
    {
        $this->function = $function;
        $this->id = $id;
        $this->name = $name;
    }

    public function run(): void
    {
        try {
            ($this->function)();
        } catch (\Throwable $th) {
            $this->status_code = self::CODE_FAIL;
            $this->error = $th->getMessage();
            return;
        }

        $this->status_code = self::CODE_FINISHED;
    }



    public function getStatusCode(): int
    {
        return $this->status_code;
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