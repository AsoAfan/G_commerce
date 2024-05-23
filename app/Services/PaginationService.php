<?php

// app/Services/PaginationService.php

namespace App\Services;

//use Illuminate\Database\Schema\Builder;

use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;


class PaginationService
{
    /**
     * @throws NotFoundException
     */
    public function paginate(Builder|Collection $queryBuilder, array|string $relations = [], $limit = 20): array
    {

        $page = request()->query('page') ?? 1;
        $take = request()->query('limit') ?? $limit;

        $skip = ($page - 1) * $take;

        $data = $queryBuilder->skip($skip)->take($take + 1);

        if ($queryBuilder instanceof Builder) {
            $data = $data->with($relations)->get();
        }


        if (!$data->count() && $page > 1) {
            throw new NotFoundException();
        }

        return [
            'data' => $data->slice(0, $take),
            'hasNextPage' => $data->count() > $take
        ];
    }
}
