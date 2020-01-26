<?php declare(strict_types=1);

namespace Qlimix\Tests\Validation\Validator\Exception;

use PHPUnit\Framework\TestCase;
use Qlimix\Validation\Validator\Exception\ViolationMessageException;

final class ViolationMessageExceptionTest extends TestCase
{
    public function testShouldCreateValidException(): void
    {
        $message = 'foobar';
        $exception = new ViolationMessageException($message);

        $this->assertSame($message, $exception->getViolationMessage());
        $this->assertSame('', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }
}
