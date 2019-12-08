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
    private const OUTPUT_INIT_TASK_CODE = 'output_initialization';

    private $serviceContainer;
    private $settings;
    private $writer;
    private $taskFactory;
    private $taskHandler;
    private $currencyProviders = [];
    private $currencies = [];

    public function __construct(
        Container $serviceContainer,
        Settings $settings,
        WriterInterface $writer,
        TaskFactory $taskFactory,
        TaskHandler $taskHandler
    ) {
        $this->serviceContainer = $serviceContainer;
        $this->settings = $settings;
        $this->writer = $writer;
        $this->taskFactory = $taskFactory;
        $this->taskHandler = $taskHandler;
        $this->configure();
    }

    protected function configure(): void
    {
        foreach ($this->settings->get('currency_providers') as $currency => $loader_class) {
            $this->currencyProviders[$currency] = $this->serviceContainer->get($loader_class);
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
        $task = $this->taskFactory->create(
            function () {
                $this->writer->init();
            },
            self::OUTPUT_INIT_TASK_CODE,
            'Output initialization'
        );

        $this->taskHandler->run($task);
    }

    private function gatherCurrencies(): void
    {
        foreach ($this->currencyProviders as $currency => $provider) {
            $task = $this->taskFactory->create(
                function () use ($provider, $currency) {
                    $this->currencies[$currency] = $provider->get($currency);
                },
                $currency,
                "Getting $currency from " . get_class($provider)
            );

            $this->taskHandler->run($task);
        }
    }

    private function saveCurrencies(): void
    {
        $task = $this->taskFactory->create(
            function () {
                $this->writer->write($this->currencies);
            },
            self::CODE_FORMATTING_TASK_CODE,
            'Formatting file ' . $this->writer->getFilePath()
        );

        $this->taskHandler->run($task);
    }
}
