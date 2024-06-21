<?php

namespace Zus1\Serializer\Event;

use Illuminate\Foundation\Events\Dispatchable;
use Zus1\Serializer\Serializer;

class NormalizedDataEvent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private Serializer $serializer,
        private string $subjectClass,
    ){
    }

    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }

    public function getSubjectClass(): string
    {
        return $this->subjectClass;
    }
}
