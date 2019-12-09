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
        $this->output->writeMessage($task->getName().'...');
        $this->profiler->start($id);
        $result = $task->run();
        $this->profiler->finish($id);

        if ($task->getStatusCode() === Task::CODE_FINISHED) {
            $this->output->writeMessage('Done in '.$this->profiler->getDuration($id).' s');
        }

        return $result;
    }

}