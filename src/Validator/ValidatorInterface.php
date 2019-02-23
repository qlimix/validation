<?php declare(strict_types=1);

namespace Qlimix\Validation\Validator;

use Qlimix\Validation\Validator\Exception\ViolationMessageException;
use Qlimix\Validation\Validator\Exception\ViolationSetException;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     *
     * @throws ViolationMessageException
     * @throws ViolationSetException
     */
    public function validate($value): void;
}
