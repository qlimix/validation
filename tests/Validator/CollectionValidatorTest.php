<?php declare(strict_types=1);

namespace Qlimix\Tests\Validation\Validator;

use PHPUnit\Framework\TestCase;
use Qlimix\Validation\CollectionValidation;
use Qlimix\Validation\Hash\Key;
use Qlimix\Validation\HashValidation;
use Qlimix\Validation\Validator\CollectionValidator;

final class CollectionValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeValid(): void
    {
        $collectionValidation = new CollectionValidation(new HashValidation(
            [
                new Key('test3', true, [])
            ],
            []
        ));

        $validation = new HashValidation(
            [
                new Key('test1', true, []),
                new Key('test2', true, [
                    new CollectionValidator($collectionValidation)
                ]),
            ],
            []
        );

        $result = $validation->validate(['test1' => 'bar', 'test2' => [['test3' => 3], ['test3' => 4]]]);
        $this->assertTrue($result->isEmpty());
    }
    /**
     * @test
     */
    public function shouldBeInvalid(): void
    {
        $collectionValidation = new CollectionValidation(new HashValidation(
            [
                new Key('test3', true, [])
            ],
            []
        ));

        $validation = new HashValidation(
            [
                new Key('test1', true, []),
                new Key('test2', true, [
                    new CollectionValidator($collectionValidation)
                ]),
            ],
            []
        );

        $result = $validation->validate(['test1' => 'bar', 'test2' => [['test3' => 3], ['test4' => 4]]]);

        $violation = $result->getViolations()[0]
            ->getViolationGroups()[0]
            ->getViolationGroups()[0]
            ->getViolations()[0];

        $this->assertFalse($result->isEmpty());
        $this->assertSame('test3', $violation->getProperty());
        $this->assertSame('hash.key.required', $violation->getMessages()[0]);
    }
}
