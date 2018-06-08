<?php
declare(strict_types=1);

namespace App\Task;

use App\{
    Output\OutputInterface, Profiler\ProfilerInterface
};

class Handler implements HandlerInterface
{
    protected $output;
    protected $profiler;

    public function __construct(OutputInterface $output, ProfilerInterface $profiler)
    {
        $this->output = $output;
        $this->profiler = $profiler;
    }


    public function run(Task $task): void
    {
        $id = $task->getId();
        $this->output->writeMessage($task->getName() . '...');
        $this->profiler->start($id);
        $task->run();
        $this->profiler->finish($id);

        if ($task->getStatusCode() === Task::CODE_FINISHED) {
            $this->output->writeMessage('Done in ' . $this->profiler->getDuration($id) . ' s');
        }

    }

}