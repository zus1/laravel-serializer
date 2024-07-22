<?php

namespace Zus1\Serializer\Helper;

class Json
{
    public static function sanitize(mixed $payload): mixed
    {
        if(in_array($payload, ['', null]) || is_bool($payload)) {
            return $payload;
        }

        try {
            $decoded = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return $payload;
        }

        return $decoded;
    }
}
