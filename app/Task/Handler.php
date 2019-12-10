<?php

declare(strict_types=1);

namespace App\Task;

use App\{
    Output\OutputInterface, Profiler\ProfilerInterface
};

class Handler implements HandlerInterface
{
    private OutputInterface $output;
    private ProfilerInterface $profiler;

    public function __construct(OutputInterface $output, ProfilerInterface $profiler)
    {
        $this->output = $output;
        $this->profiler = $profiler;
    }

    public function run(Task $task)
    {
        $id = $task->getId();
        $this->output->writeMessage($task->getName() . '...');
        $this->profiler->start($id);
        try {
            $result = $task->run();
        } catch (\Throwable $th) {
        }
        $this->profiler->finish($id);

        $this->output->writeMessage($this->getTaskMessage($task));

        return $result ?? null;
    }

    private function getTaskMessage(Task $task): string
    {
        switch ($task->getStatusCode()) {
            case Task::CODE_FINISHED:
                return 'Done in ' . $this->profiler->getDuration($task->getId()) . ' s';
            case Task::CODE_FAIL:
                return 'Failed with error: ' . $task->getError();
            case Task::CODE_RUNNING:
                return 'The task is still running (inconsistency)';
            default:
                return 'The task has wrong status code: ' . $task->getStatusCode();
        }
    }

}