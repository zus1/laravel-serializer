<?php

namespace Zus1\Serializer\Event;

use Zus1\Serializer\Interface\NormalizerInterface;
use Illuminate\Foundation\Events\Dispatchable;

class NormalizedDataEvent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private NormalizerInterface $normalizer,
        private string $subjectClass,
    ){
    }

    public function getNormalizer(): NormalizerInterface
    {
        return $this->normalizer;
    }

    public function getSubjectClass(): string
    {
        return $this->subjectClass;
    }
}
