<?php declare(strict_types=1);

namespace Qlimix\Validation\Validator;

use Qlimix\Validation\Validator\Exception\ViolationGroupException;
use Qlimix\Validation\Validator\Exception\ViolationMessageException;
use Qlimix\Validation\Validator\Exception\ViolationSetException;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     *
     * @throws ViolationMessageException
     * @throws ViolationGroupException
     * @throws ViolationSetException
     */
    public function validate($value): void;
}
