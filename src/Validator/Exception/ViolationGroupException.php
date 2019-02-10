<?php declare(strict_types=1);

namespace Qlimix\Validation\Validator\Exception;

use Exception;
use Qlimix\Validation\ViolationGroup;

final class ViolationGroupException extends Exception
{
    /** @var ViolationGroup */
    private $violationGroup;

    public function __construct(ViolationGroup $violationGroup)
    {
        parent::__construct();
        $this->violationGroup = $violationGroup;
    }

    public function getViolationGroup(): ViolationGroup
    {
        return $this->violationGroup;
    }
}
