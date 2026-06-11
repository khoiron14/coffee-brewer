<?php

namespace App\Services;

use App\Interfaces\BaseServiceInterface;
use App\Models\Coffee;
use App\Repositories\CoffeeRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CoffeeService implements BaseServiceInterface
{
    public function __construct(
        public CoffeeRepository $coffeeRepo
    ) {}

    public function getList(array $request = [], bool $paginated = true): Collection|LengthAwarePaginator
    {
        return $this->coffeeRepo->getList($request, $paginated);
    }

    public function store(array $data, ?Model $existingModel = null): Model|Exception
    {
        DB::beginTransaction();
        try {
            $coffee = $existingModel ?? new Coffee();

            $coffee->fill($data);
            $coffee->save();

            DB::commit();
            return $coffee;
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function delete(Model $model): bool|Exception
    {
        DB::beginTransaction();
        try {
            // automatically deletes related recipe steps because of cascade delete in the database
            $model->delete();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }


}
