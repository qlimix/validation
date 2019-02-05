<?php declare(strict_types=1);

namespace Qlimix\Validation;

interface ValidationBuilderInterface
{
    public function build(): ValidationInterface;
}
