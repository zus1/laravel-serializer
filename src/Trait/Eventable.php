<?php

namespace Zus1\Serializer\Trait;

use Zus1\Serializer\Event\NormalizedDataEvent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait Eventable
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

    public function setNormalizedCollection(Collection|LengthAwarePaginator $collection): void
    {
        $this->collection = $collection;
    }

    public function getNormalizedCollection(): Collection|LengthAwarePaginator
    {
        return $this->collection;
    }

    protected function withEventDispatch(array|Collection|LengthAwarePaginator $subject, string $className): array|Collection|LengthAwarePaginator
    {
        $this->setData($subject);

        NormalizedDataEvent::dispatch($this, $className);

        return $this->returnData($subject);
    }

    private function setData(array|Collection|LengthAwarePaginator $subject): void
    {
        if($subject instanceof Collection || $subject instanceof LengthAwarePaginator) {
            $this->collection = $subject;
        } else {
            $this->normalizedData = $subject;
        }
    }

    private function returnData(array|Collection|LengthAwarePaginator $subject): array|Collection|LengthAwarePaginator
    {
        if($subject instanceof Collection || $subject instanceof LengthAwarePaginator) {
            return $this->collection;
        } else {
            return $this->normalizedData;
        }
    }
}
