<?php declare(strict_types=1);

namespace Qlimix\Validation;

use Qlimix\Validation\Inspector\InspectorInterface;
use function is_array;

final class CollectionValidation implements ValidationInterface
{
    private const COLLECTION_ITEM_INVALID = 'collection.item.invalid';

    /** @var InspectorInterface[]  */
    private array $inspectors;

    /**
     * @param InspectorInterface[] $inspectors
     */
    public function __construct(array $inspectors)
    {
        $this->inspectors = $inspectors;
    }

    /**
     * @inheritDoc
     */
    public function validate(array $value): ViolationSet
    {
        $violationGroups = [];
        $violations = [];
        foreach ($value as $index => $item) {
            if (!is_array($item)) {
                $violations[] = new Violation((string) $index, [self::COLLECTION_ITEM_INVALID]);
                continue;
            }

            foreach ($this->inspectors as $inspector) {
                $violationSet = $inspector->inspect($item);
                if ($violationSet->isEmpty()) {
                    continue;
                }

                $violationGroups[] = ViolationGroup::createFromViolationSet(
                    (string) $index,
                    $violationSet
                );
            }
        }

        return new ViolationSet($violations, $violationGroups);
    }
}
