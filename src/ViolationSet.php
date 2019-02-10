<?php declare(strict_types=1);

namespace Qlimix\Validation;

use function count;

final class ViolationSet
{
    /** @var Violation[] */
    private $violations;

    /** @var ViolationGroup[] */
    private $violationGroups;

    /**
     * @param Violation[]      $violations
     * @param ViolationGroup[] $violationGroups
     */
    public function __construct(array $violations, array $violationGroups)
    {
        $this->violations = $violations;
        $this->violationGroups = $violationGroups;
    }

    public function isEmpty(): bool
    {
        return count($this->violations) === 0 && count($this->violationGroups) === 0;
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
