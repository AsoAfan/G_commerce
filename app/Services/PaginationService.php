<?php

// app/Services/PaginationService.php

namespace App\Services;

//use Illuminate\Database\Schema\Builder;

use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Builder;

class PaginationService
{
    /**
     * @throws NotFoundException
     */
    public function paginate(Builder $queryBuilder, array|string $relations = [])
    {

        $page = request()->query('page') ?? 1;
        $take = request()->query('limit') ?? 2;


        /*
        $data = $queryBuilder->with($relations)->simplePaginate($take, page: $page);
        */

        $skip = ($page - 1) * $take;
        $data = $queryBuilder->skip($skip)->take($take + 1)->with($relations)->get();
//
//        if (!$data->count() && $page > 1) {
//            throw new NotFoundException();
//        }

        return [
            'data' => $data,
            'hasNextPage' => $data->count() > $take
        ];
    }
}
