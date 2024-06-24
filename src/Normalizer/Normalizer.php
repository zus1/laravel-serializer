<?php

namespace Zus1\Serializer\Normalizer;

use Zus1\Serializer\Attributes\Attributes;
use Zus1\Serializer\Interface\NormalizerInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Zus1\Serializer\Trait\InteractWithNormalizerEvent;

class Normalizer implements NormalizerInterface
{
    use InteractWithNormalizerEvent;

    public function normalize(
        Collection|Model|LengthAwarePaginator $subject,
        array|string $groups = [],
        ?bool $nested = false
    ): array|LengthAwarePaginator
    {
        if($subject instanceof Collection || $subject instanceof LengthAwarePaginator) {
            $className = '';

            /** @var Model $model */
            foreach ($subject as $key => $model) {
                if($key === 0) {
                    $className = $model::class;
                }
                $subject[$key] = $this->normalize($model, $groups, true);
            }

            return $nested === true ? $subject->all() :
                $this->withEventDispatch($subject instanceof Collection ? $subject->all() : $subject, $className);
        }

        $groups = Arr::wrap($groups);
        if($groups === []) {
            return $subject->getAttributes();
        }

        $modelAttributes = $subject->getAttributes();
        $mappings = $this->getAttributeMappings($subject);

        $includedAttributes = $this->getIncludedAttributes($mappings, $groups, $modelAttributes);

        $relationMappings = array_filter(array_diff_key($mappings, $includedAttributes), function (array $mappedGroups) use($groups) {
            return array_intersect($groups, $mappedGroups);
        });

        foreach ($relationMappings as $key => $group) {
            if(!method_exists($subject, $key)) {
                $includedAttributes[$key] = null;
                continue;
            }

            if(Str::singular($key) === $key) {
                $this->handleNestedModelMapping($subject, $key, $groups, $includedAttributes);
            } else {
                $this->handleNestedCollectionMapping($subject, $key, $groups, $includedAttributes);
            }
        }

        return $nested === true ? $includedAttributes : $this->withEventDispatch($includedAttributes, $subject::class);
    }

    private function handleNestedCollectionMapping(Model $model, string $key, array $groups, array &$includedAttributes): void
    {
        /** @var Collection $collection */
        $collection = $model->$key()->get();

        $includedAttributes[$key] = $this->normalize($collection, $groups, true);
    }


    private function handleNestedModelMapping(Model $model, string $key, array $groups, array &$includedAttributes): void
    {
        /** @var Model $nestedModel */
        $nestedModel = $model->$key()->first();

        $includedAttributes[$key] = $this->normalize($nestedModel, $groups, true);
    }

    private function getAttributeMappings(Model $model): array
    {
        $rf = new \ReflectionClass($model);

        /** @var Attributes $attribute */
        $attribute = $rf->getAttributes(Attributes::class)[0]->newInstance();

        return $attribute->getMappings();
    }

    private function getIncludedAttributes(array $mappings, array $groups, array $modelAttributes): array
    {
        $includedAttributes = [];
        array_walk($mappings, function (array $mappingGroups, string $property) use(&$includedAttributes, $groups, $modelAttributes) {
            if(array_intersect($groups, $mappingGroups) !== []) {
                if(array_key_exists($property, $modelAttributes)) {
                    $includedAttributes[$property] = $modelAttributes[$property];
                }
            }
        });

        return $includedAttributes;
    }
}