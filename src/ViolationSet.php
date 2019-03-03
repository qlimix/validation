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
     * @param Violation[] $violations
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

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        $result = [];
        foreach ($this->violations as $violation) {
            $result[$violation->getProperty()] = $violation->getMessages();
        }

        foreach ($this->getViolationGroups() as $violationGroup) {
            $result[$violationGroup->getProperty()] = $this->violationGroupToArray($violationGroup);
        }

        return $result;
    }

    /**
     * @return mixed[]
     */
    private function violationGroupToArray(ViolationGroup $violationGroup): array
    {
        $result = [];
        foreach ($violationGroup->getViolations() as $violation) {
            $result[$violation->getProperty()] = $violation->getMessages();
        }

        foreach ($violationGroup->getViolationGroups() as $subViolationGroup) {
            $result[$subViolationGroup->getProperty()] = $this->violationGroupToArray($subViolationGroup);
        }

        return $result;
    }
}
