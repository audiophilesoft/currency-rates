<?php

declare(strict_types=1);

namespace App;

use App\Input\Console\ArgumentsHandler;
use App\Task\Handler as TaskHandler;
use App\Task\TaskFactory;
use App\Writer\Resolver as WriterResolver;
use App\Writer\WriterInterface;
use DI\Container;

class Kernel
{
    private const TASK_CODE_CODE_FORMATTING = 'formatting';

    private Container $serviceContainer;
    private $settings;
    private $taskFactory;
    private $taskHandler;
    private array $currencyProviders;
    private array $currencies;
    private ArgumentsHandler $argumentsHandler;
    private WriterResolver $writerResolver;

    public function __construct(
        Container $serviceContainer,
        Settings $settings,
        TaskFactory $taskFactory,
        TaskHandler $taskHandler,
        ArgumentsHandler $argumentsHandler,
        WriterResolver $writerResolver,
        array $currencyProvidersMap
    ) {
        $this->serviceContainer = $serviceContainer;
        $this->settings = $settings;
        $this->taskFactory = $taskFactory;
        $this->taskHandler = $taskHandler;
        $this->argumentsHandler = $argumentsHandler;
        $this->writerResolver = $writerResolver;
        $this->initCurrencyProviders($currencyProvidersMap);
    }

    protected function initCurrencyProviders(array $currencyProvidersMap): void
    {
        foreach ($currencyProvidersMap as $currency => $loaderClass) {
            $this->currencyProviders[$currency] = $this->serviceContainer->get($loaderClass);
        }
    }

    public function run()
    {
        $this->gatherCurrencies();
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
                "Getting $currency from ".get_class($provider)
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
            'Formatting file '.$this->getFilePath()
        );

        $this->taskHandler->run($task);
    }

    private function getWriter(): ?WriterInterface
    {
        return $this->writerResolver->get($this->getFormat(), ['filePath' => $this->getFilePath()]);
    }

    private function getFormat(): string
    {
        return $this->argumentsHandler->getArgumentValue($this->argumentsHandler::ARGUMENT_FORMAT) ??
            $this->settings->get('default_format');
    }

    private function getFilePath(): string
    {
        return $this->argumentsHandler->getArgumentValue($this->argumentsHandler::ARGUMENT_FILE_NAME) ??
            $this->settings->get('default_file_path');
    }
}
