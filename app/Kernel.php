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
    private const TASK_CODE_OUTPUT_INITIALIZATION = 'output_initialization';

    private Container $serviceContainer;
    private Settings $settings;
    private TaskFactory $taskFactory;
    private TaskHandler $taskHandler;
    private array $currencyProviders;
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
//        $currencies = $this->gatherCurrencies();
        $currencies = array (
            'HRN/RUB' => 0.36599999999999999,
            'HRN/DOL' => 23.600000000000001,
            'HRN/RUR' => 0.33800000000000002,
            'HRN/USD' => 23.449999999999999,
            'HRN/WMZ' => 23.109999999999999,
            'HRN/WMR' => 0.36179450072358904,
            'HRN/WMB' => 11.449999999999999,
            'DOL/HRN' => 0.042283298097251586,
            'DOL/RUB' => 0.016147263038914905,
            'RUB/HRN' => 2.7100271002710028,
            'RUB/DOL' => 61.93,
        );
        $writer = $this->getWriter();
        $this->saveCurrencies($currencies, $writer);
    }

    private function gatherCurrencies(): array
    {
        $currencies = [];
        foreach ($this->currencyProviders as $currency => $provider) {
            $task = $this->taskFactory->create(
                fn() => $provider->get($currency),
                $currency,
                "Getting $currency from " . get_class($provider)
            );

            $currencies[$currency] = $this->taskHandler->run($task);
        }

        return $currencies;
    }

    private function saveCurrencies(array $currencies, WriterInterface $writer): void
    {
        $task = $this->taskFactory->create(
            fn() => $writer->write($currencies),
            self::TASK_CODE_CODE_FORMATTING,
            'Formatting file ' . $this->getFilePath()
        );

        $this->taskHandler->run($task);
    }

    private function getWriter(): ?WriterInterface
    {
        $task = $this->taskFactory->create(
            fn() => $this->writerResolver->get($this->getFormat(), ['filePath' => $this->getFilePath()]),
            self::TASK_CODE_OUTPUT_INITIALIZATION,
            'Output initialization for ' . $this->getFormat()
        );

        return $this->taskHandler->run($task);
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
