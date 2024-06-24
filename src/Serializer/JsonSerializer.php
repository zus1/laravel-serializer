<?php

namespace Zus1\Serializer\Serializer;

use Zus1\Serializer\Interface\SerializerInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Zus1\Serializer\Trait\InteractWithSerializerEvent;

class JsonSerializer implements SerializerInterface
{
    private $className;
    
    use InteractWithSerializerEvent;
    
    public function serialize(array|LengthAwarePaginator $normalized): string
    {
        if($normalized instanceof LengthAwarePaginator) {
            $json = $normalized->toJson(JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR);
        } else {
            $json = json_encode($normalized, JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR);
        }

        return $this->withEventDispatch($json, $this->className);
    }
    
    public function setSubjectClassName(string $className): void
    {
        $this->className = $className;
    }
}
