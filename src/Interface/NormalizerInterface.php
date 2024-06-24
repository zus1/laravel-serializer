<?php

namespace Zus1\Serializer\Interface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface NormalizerInterface
{
    public function normalize(
        Collection|Model|LengthAwarePaginator $subject,
        array|string $groups = [],
        ?bool $nested = false
    ): array|LengthAwarePaginator;
}
