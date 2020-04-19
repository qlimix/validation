<?php declare(strict_types=1);

namespace Qlimix\Validation\Inspector;

use Qlimix\Validation\ViolationSet;

interface InspectorInterface
{
    /**
     * @param array<int, mixed> $value
     */
    public function inspect(array $value): ViolationSet;
}
