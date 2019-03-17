<?php declare(strict_types=1);

namespace Qlimix\Validation;

use function is_array;

final class CollectionValidation implements ValidationInterface
{
    /** @var HashValidation */
    private $hashValidation;

    public function __construct(HashValidation $hashValidation)
    {
        $this->hashValidation = $hashValidation;
    }

    /**
     * @inheritDoc
     */
    public function validate(array $value): ViolationSet
    {
        $violations = [];
        $violationGroups = [];
        foreach ($value as $index => $item) {
            if (!is_array($item)) {
                $violations[] = new Violation((string) $index, ['collection.item.invalid'], []);
            }

            $violationSet = $this->hashValidation->validate($item);
            if ($violationSet->isEmpty()) {
                continue;
            }

            $violationGroups[] = ViolationGroup::createFromViolationSet((string) $index, $violationSet);
        }

        return new ViolationSet($violations, $violationGroups);
    }
}
