<?php declare(strict_types=1);

namespace Qlimix\Validation\Validator;

use Qlimix\Validation\CollectionValidation;
use Qlimix\Validation\Validator\Exception\ViolationMessageException;
use Qlimix\Validation\Validator\Exception\ViolationSetException;
use function is_array;

final class CollectionValidator implements ValidatorInterface
{
    private const COLLECTION_INVALID = 'collection.invalid';

    /** @var CollectionValidation */
    private $collectionValidation;

    public function __construct(CollectionValidation $collectionValidation)
    {
        $this->collectionValidation = $collectionValidation;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): void
    {
        if (!is_array($value)) {
            throw new ViolationMessageException(self::COLLECTION_INVALID);
        }

        $violationSet = $this->collectionValidation->validate($value);

        if (!$violationSet->isEmpty()) {
            throw new ViolationSetException($violationSet);
        }
    }
}
