<?php declare(strict_types=1);

namespace Qlimix\Validation;

final class ViolationGroup
{
    private string $property;

    /** @var Violation[] */
    private array $violations;

    /** @var ViolationGroup[] */
    private array $violationGroups;

    /**
     * @param Violation[] $violations
     * @param ViolationGroup[] $violationGroups
     */
    public function __construct(string $property, array $violations, array $violationGroups)
    {
        $this->property = $property;
        $this->violations = $violations;
        $this->violationGroups = $violationGroups;
    }

    public static function createFromViolationSet(string $property, ViolationSet $violationSet): self
    {
        return new self($property, $violationSet->getViolations(), $violationSet->getViolationGroups());
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return Violation[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * @return ViolationGroup[]
     */
    public function getViolationGroups(): array
    {
        return $this->violationGroups;
    }
}
