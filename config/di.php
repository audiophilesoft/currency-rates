<?php
return [
    \App\Writer\WriterInterface::class => DI\get(\App\Writer\Text::class),
    \App\Settings::class => DI\autowire()
        ->constructor(__DIR__, 'app.php')
];