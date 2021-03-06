<?php declare(strict_types=1);

namespace Qlimix\Validation;

use Qlimix\Validation\Validator\ValidatorInterface;

final class Key
{
    private string $key;

    private bool $required;

    /** @var ValidatorInterface[] */
    private array $validators;

    /**
     * @param ValidatorInterface[] $validators
     */
    public function __construct(string $key, bool $required, array $validators)
    {
        $this->key = $key;
        $this->required = $required;
        $this->validators = $validators;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return ValidatorInterface[]
     */
    public function getValidators(): array
    {
        return $this->validators;
    }
}
