<?php declare(strict_types=1);

namespace Qlimix\Validation\Validator;

use Qlimix\Validation\Hash\HashKey;
use Qlimix\Validation\Validator\Exception\ValidatorException;
use Qlimix\Validation\Violation;
use function array_key_exists;
use function count;
use function is_array;

final class HashValidator implements ValidatorInterface
{
    /** @var HashKey[] */
    private $keys;

    /**
     * @param HashKey[] $keys
     */
    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): void
    {
        if (!is_array($value)) {
            throw new ValidatorException([new Violation('hash', ['hash.invalid'])]);
        }

        $violations = [];
        foreach ($this->keys as $key) {
            if (array_key_exists($key->getKey(), $value)) {
                foreach ($key->getValidators() as $validator) {
                    try {
                        $validator->validate($value[$key->getKey()]);
                    } catch (ValidatorException $exception) {
                        $violations[] = $exception->getViolations();
                    }
                }
            } elseif ($key->isRequired()) {
                $violations[] = new Violation($key->getKey(), ['hash.key.required']);
            }
        }

        if (count($violations) > 0) {
            throw new ValidatorException($violations);
        }
    }
}
