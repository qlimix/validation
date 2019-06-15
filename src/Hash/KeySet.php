<?php declare(strict_types=1);

namespace Qlimix\Validation\Hash;

final class KeySet
{
    /** @var string */
    private $key;

    /** @var bool */
    private $required;

    /** @var Key[] */
    private $hashKeys;

    /** @var KeySet[] */
    private $hashKeySets;

    /**
     * @param Key[] $hashKeys
     * @param KeySet[] $hashKeySets
     */
    public function __construct(string $key, bool $required, array $hashKeys, array $hashKeySets)
    {
        $this->key = $key;
        $this->required = $required;
        $this->hashKeys = $hashKeys;
        $this->hashKeySets = $hashKeySets;
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
    public function getHashKeys(): array
    {
        return $this->hashKeys;
    }

    /**
     * @return KeySet[]
     */
    public function getHashKeySets(): array
    {
        return $this->hashKeySets;
    }
}
