<?php
declare(strict_types=1);

namespace App\Profiler;

class SimpleProfiler implements ProfilerInterface
{
    private $tasks = [];

    public function start($task_id): void
    {
        $this->tasks[$task_id]['started'] = self::getCurrentTime();
    }

    public function finish($task_id): void
    {
        $this->tasks[$task_id]['finished'] = self::getCurrentTime();
    }

    public function getDuration($task_id): float
    {
        $task_data = &$this->tasks[$task_id];
        return round($task_data['finished'] - $task_data['started'], 2);
    }

    public function getCode($task_id): ?int
    {
        return $this->tasks[$task_id] ?? null;
    }

    private static function getCurrentTime(): float
    {
        return microtime(true);
    }

}