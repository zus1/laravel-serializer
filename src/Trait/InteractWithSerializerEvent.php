<?php

namespace Zus1\Serializer\Trait;

use Zus1\Serializer\Event\SerializedDataEvent;

trait InteractWithSerializerEvent
{
    private string $data;

    public function setSerializedData(string $data): void
    {
        $this->data = $data;
    }

    public function getSerializedData(): string
    {
        return $this->data;
    }

    protected function withEventDispatch(string $subject, string $className): string
    {
        $this->data = $subject;

        SerializedDataEvent::dispatch($this, $className);

        return $this->getSerializedData();
    }
}