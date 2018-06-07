<?php
declare(strict_types=1);
return [
    \App\Writer\WriterInterface::class => DI\get(\App\Writer\Text::class),
    \App\Settings::class => DI\autowire()
        ->constructor(__DIR__, 'app.php')
];