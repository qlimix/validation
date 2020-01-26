<?php declare(strict_types=1);

namespace Qlimix\Tests\Validation;

use PHPUnit\Framework\TestCase;
use Qlimix\Validation\CollectionValidation;
use Qlimix\Validation\Key;
use Qlimix\Validation\Inspector\HashInspector;
use Qlimix\Validation\Inspector\KeyInspector;

final class CollectionValidationTest extends TestCase
{
    public function testShouldBeValid(): void
    {
        $hashInspector = new HashInspector([
            new Key('foo', true, []),
        ]);

        $validation = new CollectionValidation([$hashInspector]);

        $result = $validation->validate([['foo' => 'bar']]);
        $this->assertTrue($result->isEmpty());
    }

    public function testShouldBeInvalid(): void
    {
        $hashInspector = new HashInspector([
            new Key('foo', true, []),
        ]);

        $validation = new CollectionValidation([$hashInspector]);

        $result = $validation->validate([[]]);

        $this->assertFalse($result->isEmpty());
        $this->assertSame(
            'hash.key.required',
            $result->getViolationGroups()[0]->getViolations()[0]->getMessages()[0]
        );
    }

    public function testShouldBeInvalidOnNoneArrayItem(): void
    {
        $hashInspector = new HashInspector([
            new Key('foo', true, []),
        ]);

        $validation = new CollectionValidation([$hashInspector]);

        $result = $validation->validate([['foo' => 'bar'], 1]);
        $violation = $result->getViolations()[0];

        $this->assertFalse($result->isEmpty());
        $this->assertSame('collection.item.invalid', $violation->getMessages()[0]);
        $this->assertSame('1', $violation->getProperty());
    }

    public function testSBeInvalidOnMultipleNoneArrayItems(): void
    {
        $hashInspector = new HashInspector([
            new Key('foo', true, []),
        ]);

        $validation = new CollectionValidation([$hashInspector]);

        $result = $validation->validate([['foo' => 'bar'], 1, 'foo']);

        $firstViolation = $result->getViolations()[0];
        $secondViolation = $result->getViolations()[1];

        $this->assertFalse($result->isEmpty());
        $this->assertSame('collection.item.invalid', $firstViolation->getMessages()[0]);
        $this->assertSame('1', $firstViolation->getProperty());

        $this->assertSame('collection.item.invalid', $secondViolation->getMessages()[0]);
        $this->assertSame('2', $secondViolation->getProperty());
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

        $validation = new CollectionValidation([$hashInspector, $keyInspector]);

        $result = $validation->validate([['test1' => 'bar', 'test2' => ['test3' => 'bar']]]);
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

        $validation = new CollectionValidation([$hashInspector, $keyInspector]);

        $result = $validation->validate([['test1' => 'bar', 'test2' => []]]);

        $violation = $result->getViolationGroups()[0]
            ->getViolationGroups()[0]
            ->getViolations()[0];

        $this->assertFalse($result->isEmpty());
        $this->assertSame('hash.key.required', $violation->getMessages()[0]);
        $this->assertSame('test3', $violation->getProperty());
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
                new KeyInspector(
                    'test4',
                    true,
                    [new HashInspector([new Key('test5', true, [])])],
                ),
            ]
        );

        $validation = new CollectionValidation([$hashInspector, $keyInspector]);

        $result = $validation->validate([
            ['test1' => 'bar', 'test2' => ['test3' => 'bar', 'test4' => ['test5' => 'foo']]]
        ]);
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
                new KeyInspector(
                    'test4',
                    true,
                    [],
                ),
            ]
        );

        $validation = new CollectionValidation([$hashInspector, $keyInspector]);

        $result = $validation->validate([['test1' => 'bar', 'test2' => ['test3' => 'bar']]]);
        $this->assertFalse($result->isEmpty());
    }
}
