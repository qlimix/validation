<?php declare(strict_types=1);

namespace Qlimix\Validation;

final class Violation
{
    /** @var string */
    private $property;

    /** @var string[] */
    private $messages;

    /** @var ViolationGroup[] */
    private $violationGroups;

    /**
     * @param string[]         $messages
     * @param ViolationGroup[] $violationGroups
     */
    public function __construct(string $property, array $messages, array $violationGroups)
    {
        $this->property = $property;
        $this->messages = $messages;
        $this->violationGroups = $violationGroups;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return string[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return ViolationGroup[]
     */
    public function getViolationGroups(): array
    {
        return $this->violationGroups;
    }
}
