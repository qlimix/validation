<?php declare(strict_types=1);

namespace Qlimix\Validation;

final class Violation
{
    /** @var string */
    private $property;

    /** @var string[] */
    private $messages;

    /**
     * @param string[] $messages
     */
    public function __construct(string $property, array $messages)
    {
        $this->property = $property;
        $this->messages = $messages;
    }

    /**
     * @return string[]
     */
    public function getMessage(): array
    {
        return $this->messages;
    }

    public function getProperty(): string
    {
        return $this->property;
    }
}
