<?php

namespace Zus1\Serializer\Interface;

use Illuminate\Pagination\LengthAwarePaginator;

interface SerializerInterface
{
    public function serialize(array|LengthAwarePaginator $normalized): string;

    public function setSubjectClassName(string $className): void;
}
