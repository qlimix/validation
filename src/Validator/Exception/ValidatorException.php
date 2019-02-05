<?php declare(strict_types=1);

namespace Qlimix\Validation\Validator\Exception;

use Exception;
use Qlimix\Validation\ViolationInterface;

final class ValidatorException extends Exception
{
    /** @var ViolationInterface[] */
    private $violations;

    /**
     * @param ViolationInterface[] $violations
     */
    public function __construct(array $violations, string $message = '')
    {
        parent::__construct($message);
        $this->violations = $violations;
    }

    /**
     * @return ViolationInterface[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}
