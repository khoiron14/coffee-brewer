<?php

namespace App\Interfaces;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseServiceInterface
{
    public function getList(array $request = [], bool $paginated = true): Collection|LengthAwarePaginator;
    public function store(array $data, ?Model $existingModel = null): Model|Exception;
    public function delete(Model $model): bool|Exception;
}
