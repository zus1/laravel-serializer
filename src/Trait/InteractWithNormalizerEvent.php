<?php

namespace Zus1\Serializer\Trait;

use Zus1\Serializer\Event\NormalizedDataEvent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait InteractWithNormalizerEvent
{
    private array $normalizedData = [];
    private Collection|LengthAwarePaginator $collection;

    public function getNormalizedData(): array
    {
        return $this->normalizedData;
    }

    public function setNormalizedData(array $normalizedData): void
    {
        $this->normalizedData = $normalizedData;
    }

    public function setPaginatedCollection(LengthAwarePaginator $collection): void
    {
        $this->collection = $collection;
    }

    public function getPaginatedCollection(): LengthAwarePaginator
    {
        return $this->collection;
    }

    protected function withEventDispatch(array|LengthAwarePaginator $subject, string $className): array|Collection|LengthAwarePaginator
    {
        $this->setData($subject);

        NormalizedDataEvent::dispatch($this, $className);

        return $this->returnData($subject);
    }

    private function setData(array|LengthAwarePaginator $subject): void
    {
        if($subject instanceof LengthAwarePaginator) {
            $this->collection = $subject;
        } else {
            $this->normalizedData = $subject;
        }
    }

    private function returnData(array|LengthAwarePaginator $subject): array|LengthAwarePaginator
    {
        if($subject instanceof LengthAwarePaginator) {
            return $this->collection;
        } else {
            return $this->normalizedData;
        }
    }
}