<?php declare(strict_types=1);

namespace Qlimix\Validation\Inspector;

use Qlimix\Validation\Key;
use Qlimix\Validation\Validator\Exception\ViolationMessageException;
use Qlimix\Validation\Violation;
use Qlimix\Validation\ViolationSet;
use function array_key_exists;
use function count;

final class HashInspector implements InspectorInterface
{
    private const HASH_KEY_REQUIRED = 'hash.key.required';

    /** @var Key[] */
    private array $keys;

    /**
     * @param Key[] $keys
     */
    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    /**
     * @inheritDoc
     */
    public function inspect(array $value): ViolationSet
    {
        $violations = [];
        foreach ($this->keys as $key) {
            if (array_key_exists($key->getKey(), $value)) {
                $messages = [];
                foreach ($key->getValidators() as $validator) {
                    try {
                        $validator->validate($value[$key->getKey()]);
                    } catch (ViolationMessageException $exception) {
                        $messages[] = $exception->getViolationMessage();
                    }
                }

                if (count($messages) > 0) {
                    $violations[] = new Violation($key->getKey(), $messages);
                }
            } elseif ($key->isRequired()) {
                $violations[] = new Violation($key->getKey(), [self::HASH_KEY_REQUIRED]);
            }
        }

        return new ViolationSet($violations, []);
    }
}
