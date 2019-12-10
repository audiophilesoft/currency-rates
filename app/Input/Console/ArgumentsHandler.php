<?php

declare(strict_types=1);

namespace App\Input\Console;

class ArgumentsHandler
{
    private array $arguments;

    public const ARGUMENT_FILE_NAME = 'file-path';
    public const ARGUMENT_FORMAT = 'format';

    private const ARGUMENTS_LONG = [
        self::ARGUMENT_FILE_NAME . '::',
        self::ARGUMENT_FORMAT . '::'
    ];

    public function __construct()
    {
        $this->arguments = getopt('', self::ARGUMENTS_LONG);
    }

    public function getArgumentValue(string $argumentName): ?string
    {
        return $this->arguments[$argumentName] ?? null;
    }
}