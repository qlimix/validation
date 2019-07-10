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
        $this->assertSame(
            'hash.key.required',
            $result->getViolationGroups()[0]->getViolations()[0]->getMessages()[0]
        );
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
        $violation = $result->getViolations()[0];

        $this->assertFalse($result->isEmpty());
        $this->assertSame('collection.item.invalid', $violation->getMessages()[0]);
        $this->assertSame('1', $violation->getProperty());
    }

    /**
     * @test
     */
    public function shouldBeInvalidOnMultipleNoneArrayItems(): void
    {
        $hashValidation = new HashValidation(
            [
                new Key('foo', true, []),
            ],
            []
        );

        $validation = new CollectionValidation($hashValidation);

        $result = $validation->validate([['foo' => 'bar'], 1, 'foo']);

        $firstViolation = $result->getViolations()[0];
        $secondViolation = $result->getViolations()[1];

        $this->assertFalse($result->isEmpty());
        $this->assertSame('collection.item.invalid', $firstViolation->getMessages()[0]);
        $this->assertSame('1', $firstViolation->getProperty());

        $this->assertSame('collection.item.invalid', $secondViolation->getMessages()[0]);
        $this->assertSame('2', $secondViolation->getProperty());
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

        $violation = $result->getViolationGroups()[0]
            ->getViolationGroups()[0]
            ->getViolations()[0];

        $this->assertFalse($result->isEmpty());
        $this->assertSame('hash.key.required', $violation->getMessages()[0]);
        $this->assertSame('test3', $violation->getProperty());
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
