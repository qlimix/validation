<?php declare(strict_types=1);

namespace Qlimix\Validation;

use Qlimix\Validation\Exception\ValidationException;

interface ValidationInterface
{
    /**
     * @param mixed[] $value
     *
     * @throws ValidationException
     */
    public function validate(array $value): ViolationSet;
}
