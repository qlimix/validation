<?php declare(strict_types=1);

namespace Qlimix\Validation\Validator;

use Qlimix\Validation\Validator\Exception\ViolationGroupException;
use Qlimix\Validation\Validator\Exception\ViolationMessageException;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     *
     * @throws ViolationMessageException
     * @throws ViolationGroupException
     */
    public function validate($value): void;
}
