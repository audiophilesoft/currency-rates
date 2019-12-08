<?php

declare(strict_types=1);
return [
    \App\Writer\WriterFactoryInterface::class => DI\get(\App\Writer\TextWriterFactory::class),
    \App\Output\OutputInterface::class => DI\get(\App\Output\Console::class),
    \App\Profiler\ProfilerInterface::class => DI\get(\App\Profiler\SimpleProfiler::class),
    \App\Settings::class => DI\autowire()->constructorParameter('dir', __DIR__)->constructorParameter(
        'file_name',
        'app.php'
    )
];