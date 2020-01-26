<?php declare(strict_types=1);

namespace Qlimix\Validation\Inspector;

use Qlimix\Validation\Violation;
use Qlimix\Validation\ViolationGroup;
use Qlimix\Validation\ViolationSet;
use function array_key_exists;

final class KeyInspector implements InspectorInterface
{
    private const COLLECTION_KEY_REQUIRED = 'hash.key.required';

    private string $key;

    private bool $required;

    /** @var InspectorInterface[] */
    private array $inspectors;

    /**
     * @param InspectorInterface[] $inspectors
     */
    public function __construct(string $key, bool $required, array $inspectors)
    {
        $this->key = $key;
        $this->required = $required;
        $this->inspectors = $inspectors;
    }

    /**
     * @inheritDoc
     */
    public function inspect(array $value): ViolationSet
    {
        $violations = [];
        if (!array_key_exists($this->key, $value)) {
            if ($this->required) {
                $violations[] = new Violation($this->key, [self::COLLECTION_KEY_REQUIRED]);
            }

            return new ViolationSet($violations, []);
        }

        $violationGroups = [];
        foreach ($this->inspectors as $inspector) {
            $violationSet = $inspector->inspect($value[$this->key]);
            if ($violationSet->isEmpty()) {
                continue;
            }

            $violationGroups[] = ViolationGroup::createFromViolationSet($this->key, $violationSet);
        }

        return new ViolationSet($violations, $violationGroups);
    }
}
