<?php

namespace Zus1\Serializer\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
class Attributes
{
    private array $mappings = [];

    public function __construct(array $groups)
    {
        array_walk($groups, function (array $group) {
            $property = array_shift($group);
            $this->mappings[$property] = $group;
        });
    }

    public function getMappings(): array
    {
        return $this->mappings;
    }
}
