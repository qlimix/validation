<?php declare(strict_types=1);

namespace Qlimix\Tests\Validation;

use PHPUnit\Framework\TestCase;
use Qlimix\Validation\Hash\HashKey;
use Qlimix\Validation\Hash\HashKeySet;
use Qlimix\Validation\HashValidation;

final class HashValidationTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeValid(): void
    {
        $validation = new HashValidation(
            [
                new HashKey('foo', true, []),
            ],
            []
        );

        $result = $validation->validate(['foo' => 'bar']);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeInvalid(): void
    {
        $validation = new HashValidation(
            [
                new HashKey('foo', true, []),
            ],
            []
        );

        $result = $validation->validate([]);
        $this->assertFalse($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeValidRecursive(): void
    {
        $validation = new HashValidation(
            [
                new HashKey('test1', true, []),
            ],
            [
                new HashKeySet('test2', true, [new HashKey('test3', true, [])], [])
            ]
        );

        $result = $validation->validate(['test1' => 'bar', 'test2' => ['test3' => 'bar']]);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeInvalidRecursive(): void
    {
        $validation = new HashValidation(
            [
                new HashKey('test1', true, []),
            ],
            [
                new HashKeySet('test2', true, [], [new HashKeySet('test3', true, [], [])])
            ]
        );

        $result = $validation->validate(['test1' => 'bar', 'test2' => []]);
        $this->assertFalse($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeValidMultipleRecursive(): void
    {
        $validation = new HashValidation(
            [
                new HashKey('test1', true, []),
            ],
            [
                new HashKeySet('test2', true,
                    [
                        new HashKey('test3', true, [])
                    ],
                    [
                        new HashKeySet('test4', true,
                            [
                                new HashKey('test5', true, [])
                            ],
                            []
                        )
                    ]
                )
            ]
        );

        $result = $validation->validate(['test1' => 'bar', 'test2' => ['test3' => 'bar', 'test4' => ['test5' => 'foo']]]);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeInvalidMultipleRecursive(): void
    {
        $validation = new HashValidation(
            [
                new HashKey('test1', true, []),
            ],
            [
                new HashKeySet('test2', true,
                    [
                        new HashKey('test3', true, [])
                    ],
                    [
                        new HashKeySet('test4', true, [], [])
                    ]
                )
            ]
        );

        $result = $validation->validate(['test1' => 'bar', 'test2' => ['test3' => 'bar']]);
        $this->assertFalse($result->isEmpty());
    }
}
