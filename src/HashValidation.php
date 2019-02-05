<?php declare(strict_types=1);

namespace Qlimix\Validation;

use Qlimix\Validation\Exception\ValidationException;
use Qlimix\Validation\Validator\Exception\ValidatorException;
use Qlimix\Validation\Validator\HashValidator;
use Throwable;

final class HashValidation implements ValidationInterface
{
    /** @var HashValidator */
    private $hashValidator;

    public function __construct(HashValidator $hashValidator)
    {
        $this->hashValidator = $hashValidator;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): array
    {
        try {
            $this->hashValidator->validate($value);
        } catch (ValidatorException $exception) {
            return $exception->getViolations();
        } catch (Throwable $exception) {
            throw new ValidationException('Could not validate', 0, $exception);
        }

        return [];
    }
}
