<?php declare(strict_types=1);

namespace Qlimix\Validation\Validator\Exception;

use Exception;

final class ViolationMessageException extends Exception
{
    private string $violationMessage;

    public function __construct(string $violationMessage)
    {
        parent::__construct();
        $this->violationMessage = $violationMessage;
    }

    public function getViolationMessage(): string
    {
        return $this->violationMessage;
    }
}
