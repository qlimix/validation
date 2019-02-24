<?php declare(strict_types=1);

namespace Qlimix\Tests\Validation;

use PHPUnit\Framework\TestCase;
use Qlimix\Validation\Violation;
use Qlimix\Validation\ViolationGroup;
use Qlimix\Validation\ViolationSet;

final class ViolationSetTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeEmpty(): void
    {
        $violationSet = new ViolationSet([], []);
        $this->assertTrue($violationSet->isEmpty());
    }

    /**
     * @test
     */
    public function shouldNotBeEmpty(): void
    {
        $violationSet = new ViolationSet([new Violation('test', [], [])], []);
        $this->assertFalse($violationSet->isEmpty());
    }

    /**
     * @test
     */
    public function shouldMatchExpectation(): void
    {
        $violation = new Violation('test', ['foo', 'bar'], []);
        $group = new ViolationGroup('foo', [new Violation('bar', ['foo' => 'bar'], [])], []);
        $group2 = new ViolationGroup('foobar', [new Violation('dummy', ['foo' => 'bar'], [])], [$group]);

        $violationSet = new ViolationSet([$violation], [$group2]);

        $this->assertSame(
            [
                'test' => [
                    'foo', 'bar'
                ],
                'foobar' => [
                    'dummy' => [
                        'foo' => 'bar',
                    ],
                    'foo' => [
                        'bar' => [
                            'foo' => 'bar'
                        ]
                    ]
                ]
            ],
            $violationSet->toArray()
        );
    }
}
