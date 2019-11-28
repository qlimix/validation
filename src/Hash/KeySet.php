<?php declare(strict_types=1);

namespace Qlimix\Validation\Hash;

final class KeySet
{
    private string $key;

    private bool $required;

    /** @var Key[] */
    private array $keys;

    /** @var KeySet[] */
    private array $keySets;

    /**
     * @param Key[] $keys
     * @param KeySet[] $keySets
     */
    public function __construct(string $key, bool $required, array $keys, array $keySets)
    {
        $this->key = $key;
        $this->required = $required;
        $this->keys = $keys;
        $this->keySets = $keySets;
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
     * @return Key[]
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * @return KeySet[]
     */
    public function getKeySets(): array
    {
        return $this->keySets;
    }
}
