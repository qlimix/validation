<?php declare(strict_types=1);

namespace Qlimix\Validation;

use Qlimix\Validation\Hash\HashKey;
use Qlimix\Validation\Hash\HashKeySet;
use Qlimix\Validation\Validator\Exception\ViolationGroupException;
use Qlimix\Validation\Validator\Exception\ViolationMessageException;
use function array_key_exists;
use function count;

final class HashValidation implements ValidationInterface
{
    /** @var HashKey[] */
    private $keys;

    /** @var HashKeySet[] */
    private $keySets;

    /**
     * @param HashKey[]    $keys
     * @param HashKeySet[] $keySets
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
                $violationSet = $this->validateKeySets($keySet->getHashKeySets(), $value[$keySet->getKey()]);
                if (!$violationSet->isEmpty()) {
                    $violationGroups[] = ViolationGroup::createFromViolationSet($keySet->getKey(), $violationSet);
                }
            } elseif ($keySet->isRequired()) {
                $violations[] = new Violation($keySet->getKey(), ['hash.key.required'], []);
            }
        }

        return new ViolationSet($violations, $violationGroups);
    }

    /**
     * @param HashKey[] $keys
     * @param mixed[]   $value
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
                    } catch (ViolationGroupException $exception) {
                        $groups[] = $exception->getViolationGroup();
                    }
                }
                if (count($messages) > 0) {
                    $violations[] = new Violation($key->getKey(), $messages, $groups);
                }
            } elseif ($key->isRequired()) {
                $violations[] = new Violation($key->getKey(), ['hash.key.required'], []);
            }
        }

        return $violations;
    }

    /**
     * @param HashKeySet[] $keySets
     * @param mixed[]      $value
     */
    private function validateKeySets(array $keySets, array $value): ViolationSet
    {
        $violations = [];
        $violationGroups = [];
        foreach ($keySets as $keySet) {
            if (array_key_exists($keySet->getKey(), $value)) {
                $keyViolations = $this->validateKeys($keySet->getHashKeys(), $value[$keySet->getKey()]);
                $keySetViolations = [];
                foreach ($keySet->getHashKeySets() as $hashKeySet) {
                    $keySetViolationSet = $this->validateKeySets($hashKeySet->getHashKeySets(), $value[$keySet->getKey()]);
                    if ($keySetViolationSet->isEmpty()) {
                        continue;
                    }

                    $keySetViolations[] = ViolationGroup::createFromViolationSet(
                        $hashKeySet->getKey(),
                        $keySetViolationSet
                    );
                }
                if (count($keyViolations) > 0 || count($keySetViolations) > 0) {
                    $violationGroups[] = new ViolationGroup($keySet->getKey(), $keyViolations, $keySetViolations);
                }
            } elseif ($keySet->isRequired()) {
                $violations[] = new Violation($keySet->getKey(), ['hash.key.required'], []);
            }
        }

        return new ViolationSet($violations, $violationGroups);
    }
}
