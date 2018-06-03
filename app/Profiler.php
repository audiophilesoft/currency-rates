<?php
namespace App;

class Profiler
{
    private $tasks = [];
    public const CODE_RUNNING = 1;
    public const CODE_FINISHED = 2;
    public const CODE_FAIL = 3;


    public function start(string $task_name): void
    {
        $task_data = &$this->tasks[$task_name];
        $task_data['code'] = self::CODE_RUNNING;
        $task_data['started'] = self::getCurrentTime();
    }

    public function finish(string $task_name): void
    {
        $task_data = &$this->tasks[$task_name];
        $task_data['code'] = self::CODE_FINISHED;
        $task_data['finished'] = self::getCurrentTime();
    }


    public function getDuration(string $task_name): float
    {
        $task_data = &$this->tasks[$task_name];
        return round($task_data['finished'] - $task_data['started'], 2);
    }

    private static function getCurrentTime(): float
    {
        return microtime(true);
    }

}