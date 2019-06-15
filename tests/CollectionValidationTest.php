<?php declare(strict_types=1);

namespace Qlimix\Tests\Validation;

use PHPUnit\Framework\TestCase;
use Qlimix\Validation\Hash\Key;
use Qlimix\Validation\Hash\KeySet;
use Qlimix\Validation\CollectionValidation;
use Qlimix\Validation\HashValidation;

final class CollectionValidationTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeValid(): void
    {
        $hashValidation = new HashValidation(
            [
                new Key('foo', true, []),
            ],
            []
        );

        $validation = new CollectionValidation($hashValidation);

        $result = $validation->validate([['foo' => 'bar']]);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeInvalid(): void
    {
        $hashValidation = new HashValidation(
            [
                new Key('foo', true, []),
            ],
            []
        );

        $validation = new CollectionValidation($hashValidation);

        $result = $validation->validate([[]]);
        $this->assertFalse($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeInvalidOnNoneArrayItem(): void
    {
        $hashValidation = new HashValidation(
            [
                new Key('foo', true, []),
            ],
            []
        );

        $validation = new CollectionValidation($hashValidation);

        $result = $validation->validate([['foo' => 'bar'], 1]);
        $this->assertFalse($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeValidRecursive(): void
    {
        $hashValidation = new HashValidation(
            [
                new Key('test1', true, []),
            ],
            [
                new KeySet('test2', true, [new Key('test3', true, [])], [])
            ]
        );

        $validation = new CollectionValidation($hashValidation);

        $result = $validation->validate([['test1' => 'bar', 'test2' => ['test3' => 'bar']]]);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeInvalidRecursive(): void
    {
        $hashValidation = new HashValidation(
            [
                new Key('test1', true, []),
            ],
            [
                new KeySet('test2', true, [], [new KeySet('test3', true, [], [])])
            ]
        );

        $validation = new CollectionValidation($hashValidation);

        $result = $validation->validate([['test1' => 'bar', 'test2' => []]]);
        $this->assertFalse($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeValidMultipleRecursive(): void
    {
        $hashValidation = new HashValidation(
            [
                new Key('test1', true, []),
            ],
            [
                new KeySet('test2', true,
                    [
                        new Key('test3', true, [])
                    ],
                    [
                        new KeySet('test4', true,
                            [
                                new Key('test5', true, [])
                            ],
                            []
                        )
                    ]
                )
            ]
        );

        $validation = new CollectionValidation($hashValidation);

        $result = $validation->validate([
            ['test1' => 'bar', 'test2' => ['test3' => 'bar', 'test4' => ['test5' => 'foo']]]
        ]);
        $this->assertTrue($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeInvalidMultipleRecursive(): void
    {
        $hashValidation = new HashValidation(
            [
                new Key('test1', true, []),
            ],
            [
                new KeySet('test2', true,
                    [
                        new Key('test3', true, [])
                    ],
                    [
                        new KeySet('test4', true, [], [])
                    ]
                )
            ]
        );

        $validation = new CollectionValidation($hashValidation);

        $result = $validation->validate([['test1' => 'bar', 'test2' => ['test3' => 'bar']]]);
        $this->assertFalse($result->isEmpty());
    }
}
