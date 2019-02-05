<?php declare(strict_types=1);

namespace Qlimix\Validation;

use Qlimix\Validation\Exception\ValidationException;
use Qlimix\Validation\Validator\Exception\ValidatorException;
use Qlimix\Validation\Validator\HashValidator;
use Throwable;
use function is_array;

final class CollectionValidation implements ValidationInterface
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
        if (!is_array($value)) {
            return [new Violation('hash', ['hash.invalid'])];
        }

        $violations = [];
        foreach ($value as $item) {
            try {
                $this->hashValidator->validate($item);
            } catch (ValidatorException $exception) {
                $violations[] = $exception->getViolations();
            } catch (Throwable $exception) {
                throw new ValidationException('Could not validate', 0, $exception);
            }
        }

        return $violations;
    }
}
