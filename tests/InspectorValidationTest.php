<?php declare(strict_types=1);

namespace Qlimix\Tests\Validation;

use PHPUnit\Framework\TestCase;
use Qlimix\Validation\Key;
use Qlimix\Validation\Inspector\HashInspector;
use Qlimix\Validation\Inspector\KeyInspector;
use Qlimix\Validation\InspectorValidation;
use Qlimix\Validation\Validator\Exception\ViolationMessageException;
use Qlimix\Validation\Validator\ValidatorInterface;

final class InspectorValidationTest extends TestCase
{
    public function testShouldBeValid(): void
    {
        $hashInspector = new HashInspector([
            new Key('foo', true, []),
        ]);
        $validation = new InspectorValidation([$hashInspector]);

        $result = $validation->validate(['foo' => 'bar']);
        $this->assertTrue($result->isEmpty());
    }

    public function testShouldBeInvalid(): void
    {
        $hashInspector = new HashInspector([
            new Key('foo', true, []),
        ]);

        $validation = new InspectorValidation([$hashInspector]);

        $result = $validation->validate([]);
        $this->assertFalse($result->isEmpty());
        $this->assertSame(
            'hash.key.required',
            $result->getViolations()[0]->getMessages()[0]
        );
    }

    public function testShouldBeValidRecursive(): void
    {
        $hashInspector = new HashInspector([
            new Key('test1', true, []),
        ]);

        $keyInspector = new KeyInspector(
            'test2',
            true,
            [new HashInspector([new Key('test3', true, [])])]
        );

        $validation = new InspectorValidation([$hashInspector, $keyInspector]);

        $result = $validation->validate(['test1' => 'bar', 'test2' => ['test3' => 'bar']]);
        $this->assertTrue($result->isEmpty());
    }

    public function testShouldBeInvalidRecursive(): void
    {
        $hashInspector = new HashInspector([
            new Key('test1', true, []),
        ]);

        $keyInspector = new KeyInspector(
            'test2',
            true,
            [new HashInspector([new Key('test3', true, [])])]
        );

        $validation = new InspectorValidation([$hashInspector, $keyInspector]);

        $result = $validation->validate(['test1' => 'bar', 'test2' => []]);
        $this->assertFalse($result->isEmpty());
    }

    public function testShouldBeValidMultipleRecursive(): void
    {
        $hashInspector = new HashInspector([
            new Key('test1', true, []),
        ]);

        $keyInspector = new KeyInspector(
            'test2',
            true,
            [
                new HashInspector([new Key('test3', true, [])]),
                new KeyInspector('test4', true, [
                    new HashInspector([new Key('test5', true, [])])
                ]),
            ]
        );

        $validation = new InspectorValidation([$hashInspector, $keyInspector]);

        $result = $validation->validate(['test1' => 'bar', 'test2' => ['test3' => 'bar', 'test4' => ['test5' => 'foo']]]);
        $this->assertTrue($result->isEmpty());
    }

    public function testShouldBeInvalidMultipleRecursive(): void
    {
        $hashInspector = new HashInspector([
            new Key('test1', true, []),
        ]);

        $keyInspector = new KeyInspector(
            'test2',
            true,
            [
                new HashInspector([new Key('test3', true, [])]),
                new KeyInspector('test4', true, [
                    new HashInspector([new Key('test5', true, [])])
                ]),
            ]
        );

        $validation = new InspectorValidation([$hashInspector, $keyInspector]);

        $result = $validation->validate(['test1' => 'bar', 'test2' => ['test3' => 'bar']]);
        $this->assertFalse($result->isEmpty());
    }

    public function testShouldBeInvalidWithMissingRequiredField(): void
    {
        $hashInspector = new HashInspector([
            new Key('test1', true, []),
        ]);

        $keyInspector = new KeyInspector(
            'test2',
            true,
            [
                new HashInspector([new Key('test3', true, [])]),
            ]
        );

        $validation = new InspectorValidation([$hashInspector, $keyInspector]);

        $result = $validation->validate(['test1' => 'bar']);
        $this->assertFalse($result->isEmpty());
        $this->assertSame('hash.key.required', $result->getViolations()[0]->getMessages()[0]);
    }

    public function testShouldBeInvalidWithThrowingValidator(): void
    {
        $validator = $this->createMock(ValidatorInterface::class);
        $validator->expects($this->once())
            ->method('validate')
            ->willThrowException(new ViolationMessageException('invalid'));


        $hashInspector = new HashInspector([
            new Key('test1', true, [$validator]),
        ]);

        $validation = new InspectorValidation([$hashInspector]);

        $result = $validation->validate(['test1' => 'bar']);
        $this->assertFalse($result->isEmpty());
    }
}
