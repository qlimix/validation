<?php declare(strict_types=1);

namespace Qlimix\Tests\Validation\Validator\Exception;

use PHPUnit\Framework\TestCase;
use Qlimix\Validation\Validator\Exception\ViolationSetException;
use Qlimix\Validation\ViolationSet;

final class ViolationSetExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateValidException(): void
    {
        $exception = new ViolationSetException(new ViolationSet([], []));

        $this->assertTrue($exception->getViolationSet()->isEmpty());
        $this->assertSame('', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }
}
