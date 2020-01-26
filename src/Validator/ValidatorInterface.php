<?php declare(strict_types=1);

namespace Qlimix\Validation\Validator;

use Qlimix\Validation\Validator\Exception\ViolationMessageException;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     *
     * @throws ViolationMessageException
     */
    public function validate($value): void;
}
