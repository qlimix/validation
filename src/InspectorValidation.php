<?php declare(strict_types=1);

namespace Qlimix\Validation;

use Qlimix\Validation\Inspector\InspectorInterface;

final class InspectorValidation implements ValidationInterface
{
    /** @var InspectorInterface[] */
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
        $violationSets = [];
        foreach ($this->inspectors as $inspector) {
            $violationSets[] = $inspector->inspect($value);
        }

        return ViolationSet::createFromArray($violationSets);
    }
}
