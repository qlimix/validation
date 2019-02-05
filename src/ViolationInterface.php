<?php declare(strict_types=1);

namespace Qlimix\Validation;

interface ViolationInterface
{
    /**
     * @return string[]
     */
    public function getMessage(): array;

    public function getProperty(): string;
}
