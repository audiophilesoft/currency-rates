<?php

declare(strict_types=1);

namespace App;

use App\Input\Console\ArgumentsHandler;
use App\Task\Handler as TaskHandler;
use App\Task\TaskFactory;
use App\Writer\WriterFactory;
use App\Writer\WriterFactoryInterface;
use App\Writer\WriterInterface;
use DI\FactoryInterface;

class Kernel
{
    private const TASK_CODE_CODE_FORMATTING = 'formatting';
    private const TASK_CODE_OUTPUT_INIT = 'output_initialization';

    private $serviceContainer;
    private $settings;
    private WriterFactoryInterface $writerFactory;
    private $taskFactory;
    private $taskHandler;
    private $currencyProviders = [];
    private $currencies = [];
    private ArgumentsHandler $argumentsHandler;


    public function __construct(
        FactoryInterface $serviceContainer,
        Settings $settings,
        WriterFactoryInterface $writerFactory,
        TaskFactory $taskFactory,
        TaskHandler $taskHandler,
        ArgumentsHandler $argumentsHandler
    ) {
        $this->serviceContainer = $serviceContainer;
        $this->settings = $settings;
        $this->writerFactory = $writerFactory;
        $this->taskFactory = $taskFactory;
        $this->taskHandler = $taskHandler;
        $this->argumentsHandler = $argumentsHandler;
        $this->initLoaders();
    }


    protected function initLoaders(): void
    {
        foreach ($this->settings->get('currency_providers') as $currency => $loaderClass) {
            $this->currencyProviders[$currency] = $this->serviceContainer->get($loaderClass);
        }
    }


    public function run()
    {
        //$this->gatherCurrencies();
        $this->saveCurrencies();
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
        $writer = $this->getWriter();
        $task = $this->taskFactory->create(
            fn() => $writer->write($this->currencies),
            self::TASK_CODE_CODE_FORMATTING,
            'Formatting file ' . $this->getFilePath()
        );

        $this->taskHandler->run($task);
    }

    private function getWriter(): ?WriterInterface
    {
        $class = $this->settings->get('writers_map')[$this->argumentsHandler->getArgumentValue($this->argumentsHandler::ARGUMENT_FORMAT) ?? $this->settings->get('default_format')] ;
        return $this->serviceContainer->make($class, ['filePath' => $this->getFilePath()]);
    }


    protected function getFilePath(): string
    {
        return $this->argumentsHandler->getArgumentValue($this->argumentsHandler::ARGUMENT_FILE_NAME) ??
            $this->settings->get('default_file_path');
    }
}
