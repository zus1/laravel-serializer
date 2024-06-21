<?php

namespace Zus1\Serializer\Facade;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array|Collection|LengthAwarePaginator normalize(Collection|Model|LengthAwarePaginator $subject, array|string $groups = [])
 * @method static string serialize(Collection|Model|LengthAwarePaginator $subject, array|string $groups = [])
 */
class Serializer extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return \Zus1\Serializer\Serializer::class;
    }
}
