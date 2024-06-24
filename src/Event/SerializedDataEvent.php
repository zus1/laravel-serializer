<?php

namespace Zus1\Serializer\Event;

use Zus1\Serializer\Interface\SerializerInterface;
use Illuminate\Foundation\Events\Dispatchable;

class SerializedDataEvent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private SerializerInterface $serializer,
        private string $className
    ) {
    }

    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    public function getClassName(): string
    {
        return $this->className;
    }
}