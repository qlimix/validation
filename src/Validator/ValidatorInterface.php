<?php declare(strict_types=1);

namespace Qlimix\Validation\Validator;

use Qlimix\Validation\Validator\Exception\ValidatorException;

interface ValidatorInterface
{
    /**
     * @param mixed $value
     *
     * @throws ValidatorException
     */
    public function validate($value): void;
}
