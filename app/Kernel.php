<?php
declare(strict_types=1);

namespace App;

use App\Writer\Excel;
use App\Writer\Text;
use App\Writer\WriterInterface;
use DI\Container;

class Kernel
{

    private const CODE_FORMATTING_TASK_ID = 'formatting';
    private const OUTPUT_INIT_TASK_ID = 'output initialization';


    private $service_container;
    private $settings;
    private $console;
    private $profiler;
    private $writer;
    private $currency_providers = [];


    public function __construct(
        Container $service_container,
        Settings $settings,
        Console $console,
        Profiler $profiler,
        WriterInterface $writer
    ) {
        $this->service_container = $service_container;
        $this->settings = $settings;
        $this->console = $console;
        $this->profiler = $profiler;
        $this->writer = $writer;
        $this->configure();
    }


    /**
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    protected function configure(): void
    {
        foreach ($this->settings->get('currency_provides') as $currency => $loader_class) {
            $this->currency_providers[$currency] = $this->service_container->get($loader_class);
        }
    }


    public function run()
    {
        $currencies = [];

        $this->console->writeMessage('Output initialization...');
        $this->profiler->start(self::OUTPUT_INIT_TASK_ID);
        $this->writer->init();
        $this->profiler->finish(self::OUTPUT_INIT_TASK_ID);
        $this->console->writeMessage('Done in ' . $this->profiler->getDuration(self::OUTPUT_INIT_TASK_ID) . ' s');

        foreach ($this->currency_providers as $currency => $provider) {
            $this->console->writeMessage("Getting Minfin $currency from " . get_class($provider) . '...');
            $this->profiler->start($currency);
            $currencies[$currency] = $provider->get($currency);
            $this->profiler->finish($currency);
            $this->console->writeMessage('Done in ' . $this->profiler->getDuration($currency) . ' s');
        }

        $this->console->writeMessage('Formatting file...');
        $this->profiler->start(self::CODE_FORMATTING_TASK_ID);
        $this->writer->write($currencies);
        $this->profiler->finish(self::CODE_FORMATTING_TASK_ID);
        $this->console->writeMessage('Done in ' . $this->profiler->getDuration(self::CODE_FORMATTING_TASK_ID) . ' s');
    }

}