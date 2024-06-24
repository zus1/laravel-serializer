<?php

namespace Zus1\Serializer;

use Zus1\Serializer\Interface\NormalizerInterface;
use Zus1\Serializer\Interface\SerializerInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class Serializer
{
    public function __construct(
        private NormalizerInterface $normalizer,
    ){
    }

    public function serialize(Collection|Model|LengthAwarePaginator $subject, array|string $groups, string $type = '',): string
    {
        $serializer = $this->getSerializer($type);
        
        $this->setSubjectClassName($serializer, $subject);
        
        $normalized = $this->normalizer->normalize($subject, $groups);

        return $serializer->serialize($normalized);
    }

    public function normalize(Collection|Model|LengthAwarePaginator $subject, array|string $groups): array|LengthAwarePaginator
    {
        return $this->normalizer->normalize($subject, $groups);
    }

    private function setSubjectClassName(SerializerInterface $serializer, Collection|Model|LengthAwarePaginator $subject): void
    {
        if($subject instanceof Collection || $subject instanceof LengthAwarePaginator) {
            $serializer->setSubjectClassName($subject->get(0)::class);

            return;
        }

        $serializer->setSubjectClassName($subject::class);
    }

    private function getSerializer(string $type): SerializerInterface
    {
        if($type === '') {
            $serializerType = Config::get('serializer.default');
        } else {
            $serializerType = $type;
        }

        if(!Config::has('serializer.serializers.'.$serializerType)) {
            throw new \HttpException('Unknown serializer type '.$serializerType,500);
        }

        return App::make(Config::get(sprintf('serializer.serializers.%s.class', $serializerType)));
    }
}
