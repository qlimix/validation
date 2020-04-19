<?php declare(strict_types=1);

namespace Qlimix\Validation;

final class Violation
{
    private string $property;

    /** @var string[] */
    private array $messages;

    /**
     * @param string[] $messages
     */
    public function __construct(string $property, array $messages)
    {
        $this->property = $property;
        $this->messages = $messages;
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
}
