<?php declare(strict_types=1);

namespace Qlimix\Validation\Hash;

final class HashKeySet
{
    /** @var string */
    private $key;

    /** @var bool */
    private $required;

    /** @var HashKey[] */
    private $hashKeys;

    /** @var HashKeySet[] */
    private $hashKeySets;

    /**
     * @param HashKey[]    $hashKeys
     * @param HashKeySet[] $hashKeySets
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
     * @return HashKey[]
     */
    public function getHashKeys(): array
    {
        return $this->hashKeys;
    }

    /**
     * @return HashKeySet[]
     */
    public function getHashKeySets(): array
    {
        return $this->hashKeySets;
    }
}
