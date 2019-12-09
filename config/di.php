<?php

declare(strict_types=1);

return [
    \TKovrijenko\FloatFormatter\FormatterInterface::class => DI\get(
        \TKovrijenko\FloatFormatter\SignificantFormatter::class
    ),
    \App\Output\OutputInterface::class => DI\get(\App\Output\Console::class),
    \App\Profiler\ProfilerInterface::class => DI\get(\App\Profiler\SimpleProfiler::class),
    \App\Settings::class => DI\autowire()->constructorParameter('dir', __DIR__)->constructorParameter(
        'file_name',
        'app.php'
    ),
    \App\Writer\Resolver::class => DI\autowire()->constructorParameter(
        'map',
        [
            'txt' => \App\Writer\Text::class,
            'xlsx' => \App\Writer\Excel::class,
        ]
    ),
    \App\Kernel::class => DI\autowire()->constructorParameter(
        'currencyProvidersMap',
        [
            'HRN/RUB' => App\Parser\Minfin::class,
            'HRN/DOL' => App\Parser\Minfin::class,
            'HRN/RUR' => App\Parser\PrivatBank::class,
            'HRN/USD' => App\Parser\PrivatBank::class,
            'HRN/WMZ' => App\Parser\ObmenkaUa::class,
            'HRN/WMR' => App\Parser\ObmenkaUa::class,
            'HRN/WMB' => App\Parser\ObmenkaUa::class,
            'DOL/HRN' => App\Parser\Minfin::class,
            'DOL/RUB' => App\Parser\SberBankRF::class,
            'RUB/HRN' => App\Parser\Minfin::class,
            'RUB/DOL' => App\Parser\SberBankRF::class,
        ]
    ),
];