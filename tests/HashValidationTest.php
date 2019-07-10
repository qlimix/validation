<?php declare(strict_types=1);

namespace Qlimix\Tests\Validation;

use PHPUnit\Framework\TestCase;
use Qlimix\Validation\Hash\Key;
use Qlimix\Validation\Hash\KeySet;
use Qlimix\Validation\HashValidation;
use Qlimix\Validation\Validator\Exception\ViolationMessageException;
use Qlimix\Validation\Validator\ValidatorInterface;

final class HashValidationTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeValid(): void
    {
        $validation = new HashValidation(
            [
                new Key('foo', true, []),
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
                new Key('foo', true, []),
            ],
            []
        );

        $result = $validation->validate([]);
        $this->assertFalse($result->isEmpty());
        $this->assertSame(
            'hash.key.required',
            $result->getViolations()[0]->getMessages()[0]
        );
    }

    /**
     * @test
     */
    public function shouldBeValidRecursive(): void
    {
        $validation = new HashValidation(
            [
                new Key('test1', true, []),
            ],
            [
                new KeySet('test2', true, [new Key('test3', true, [])], [])
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
                new Key('test1', true, []),
            ],
            [
                new KeySet('test2', true, [], [new KeySet('test3', true, [], [])])
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

        $result = $validation->validate(['test1' => 'bar', 'test2' => ['test3' => 'bar']]);
        $this->assertFalse($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeInvalidWithMissingRequiredField(): void
    {
        $validation = new HashValidation(
            [
                new Key('test1', true, []),
            ],
            [
                new KeySet('test2', true,
                    [
                        new Key('test3', true, [])
                    ],
                    []
                )
            ]
        );

        $result = $validation->validate(['test1' => 'bar']);
        $this->assertFalse($result->isEmpty());
        $this->assertSame('hash.key.required', $result->getViolations()[0]->getMessages()[0]);
    }

    /**
     * @test
     */
    public function shouldBeInvalidWithThrowingValidator(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('validate')
            ->willThrowException(new ViolationMessageException('invalid'));

        $validation = new HashValidation(
            [
                new Key('test1', true, [$validator]),
            ],
            []
        );

        $result = $validation->validate(['test1' => 'bar']);
        $this->assertFalse($result->isEmpty());
    }

    /**
     * @test
     */
    public function shouldBeValidWithSubValidationKeySet(): void
    {
        $validation = new HashValidation(
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
                            [
                                new KeySet('test2', true,
                                    [
                                        new Key('test3', true, [])
                                    ],
                                    []
                                )
                            ]
                        ),
                        new KeySet('test6', true,
                            [
                                new Key('test7', true, [])
                            ],
                            []
                        )
                    ]
                )
            ]
        );

        $result = $validation->validate(['test1' => 'bar']);

        $this->assertFalse($result->isEmpty());
        $this->assertSame('hash.key.required', $result->getViolations()[0]->getMessages()[0]);
    }
}
