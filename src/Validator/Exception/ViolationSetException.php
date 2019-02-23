<?php declare(strict_types=1);

namespace Qlimix\Validation\Validator\Exception;

use Exception;
use Qlimix\Validation\ViolationSet;

final class ViolationSetException extends Exception
{
    /** @var ViolationSet */
    private $violationSet;

    public function __construct(ViolationSet $violationSet)
    {
        parent::__construct();
        $this->violationSet = $violationSet;
    }

    public function getViolationSet(): ViolationSet
    {
        return $this->violationSet;
    }
}
