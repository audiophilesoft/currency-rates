<?php
declare(strict_types=1);

namespace App\Profiler;


interface ProfilerInterface
{


    public function start($task_id): void;

    public function finish($task_id): void;

    public function getDuration($task_id): float;
}