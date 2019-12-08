<?php
declare(strict_types=1);

namespace App;

use App\Task\Handler as TaskHandler;
use App\Task\TaskFactory;
use App\Writer\WriterInterface;
use DI\Container;

class Kernel
{

    private const CODE_FORMATTING_TASK_CODE = 'formatting';
    private const OUTPUT_INIT_TASK_CODE = 'output initialization';


    private $service_container;
    private $settings;
    private $writer;
    private $task_factory;
    private $task_handler;
    private $currency_providers = [];
    private $currencies = [];


    public function __construct(
        Container $service_container,
        Settings $settings,
        WriterInterface $writer,
        TaskFactory $task_factory,
        TaskHandler $task_handler
    ) {
        $this->service_container = $service_container;
        $this->settings = $settings;
        $this->writer = $writer;
        $this->task_factory = $task_factory;
        $this->task_handler = $task_handler;
        $this->configure();
    }



    protected function configure(): void
    {
        foreach ($this->settings->get('currency_providers') as $currency => $loader_class) {
            $this->currency_providers[$currency] = $this->service_container->get($loader_class);
        }
    }


    public function run()
    {
        $this->initOutput();
        $this->gatherCurrencies();
        $this->saveCurrencies();
    }


    private function initOutput()
    {
        $task = $this->task_factory->create(
            function () {
                $this->writer->init();
            },
            self::OUTPUT_INIT_TASK_CODE, 'Output initialization'
        );

        $this->task_handler->run($task);
    }


    private function gatherCurrencies(): void
    {
        foreach ($this->currency_providers as $currency => $provider) {

            $task = $this->task_factory->create(
                function () use ($provider, $currency) {
                    $this->currencies[$currency] = $provider->get($currency);
                },
                $currency, "Getting $currency from " . get_class($provider)
            );

            $this->task_handler->run($task);
        }
    }


    private function saveCurrencies(): void
    {
        $task = $this->task_factory->create(
            function () {
                $this->writer->write($this->currencies);
            },
            self::CODE_FORMATTING_TASK_CODE, 'Formatting file ' . $this->writer->getFilePath()
        );

        $this->task_handler->run($task);
    }

}