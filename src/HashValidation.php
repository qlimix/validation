<?php declare(strict_types=1);

namespace Qlimix\Validation;

use Qlimix\Validation\Hash\Key;
use Qlimix\Validation\Hash\KeySet;
use Qlimix\Validation\Validator\Exception\ViolationMessageException;
use Qlimix\Validation\Validator\Exception\ViolationSetException;
use function array_key_exists;
use function count;

final class HashValidation implements ValidationInterface
{
    private const HASH_KEY_REQUIRED = 'hash.key.required';

    /** @var Key[] */
    private array $keys;

    /** @var KeySet[] */
    private array $keySets;

    /**
     * @param Key[] $keys
     * @param KeySet[] $keySets
     */
    public function __construct(array $keys, array $keySets)
    {
        $this->keys = $keys;
        $this->keySets = $keySets;
    }

    /**
     * @inheritDoc
     */
    public function validate(array $value): ViolationSet
    {
        $violationGroups = [];
        $violations = $this->validateKeys($this->keys, $value);
        foreach ($this->keySets as $keySet) {
            if (array_key_exists($keySet->getKey(), $value)) {
                $violationSet = $this->validateKeySets($keySet->getKeySets(), $value[$keySet->getKey()]);
                if (!$violationSet->isEmpty()) {
                    $violationGroups[] = ViolationGroup::createFromViolationSet($keySet->getKey(), $violationSet);
                }
            } elseif ($keySet->isRequired()) {
                $violations[] = new Violation($keySet->getKey(), [self::HASH_KEY_REQUIRED], []);
            }
        }

        return new ViolationSet($violations, $violationGroups);
    }

    /**
     * @param Key[] $keys
     * @param mixed[] $value
     *
     * @return Violation[]
     */
    private function validateKeys(array $keys, array $value): array
    {
        $violations = [];
        foreach ($keys as $key) {
            if (array_key_exists($key->getKey(), $value)) {
                $messages = [];
                $groups = [];
                foreach ($key->getValidators() as $validator) {
                    try {
                        $validator->validate($value[$key->getKey()]);
                    } catch (ViolationMessageException $exception) {
                        $messages[] = $exception->getViolationMessage();
                    } catch (ViolationSetException $exception) {
                        $groups[] = new ViolationGroup(
                            $key->getKey(),
                            $exception->getViolationSet()->getViolations(),
                            $exception->getViolationSet()->getViolationGroups()
                        );
                    }
                }

                if (count($messages) > 0 || count($groups) > 0) {
                    $violations[] = new Violation($key->getKey(), $messages, $groups);
                }
            } elseif ($key->isRequired()) {
                $violations[] = new Violation($key->getKey(), [self::HASH_KEY_REQUIRED], []);
            }
        }

        return $violations;
    }

    /**
     * @param KeySet[] $keySets
     * @param mixed[] $value
     */
    private function validateKeySets(array $keySets, array $value): ViolationSet
    {
        $violations = [];
        $violationGroups = [];
        foreach ($keySets as $keySet) {
            if (array_key_exists($keySet->getKey(), $value)) {
                $keyViolations = $this->validateKeys($keySet->getKeys(), $value[$keySet->getKey()]);

                if (count($keyViolations) > 0) {
                    $violationGroups[] = new ViolationGroup($keySet->getKey(), $keyViolations, []);
                }
            } elseif ($keySet->isRequired()) {
                $violations[] = new Violation($keySet->getKey(), [self::HASH_KEY_REQUIRED], []);
            }
        }

        return new ViolationSet($violations, $violationGroups);
    }
}
