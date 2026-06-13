<?php

namespace App\Services;

use App\Interfaces\BaseServiceInterface;
use App\Models\Rating;
use App\Repositories\RatingRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RatingService implements BaseServiceInterface
{
    public function __construct(
        public RatingRepository $ratingRepo
    ) {}

    public function getList(array $request = [], bool $paginated = true): Collection|LengthAwarePaginator
    {
        return $this->ratingRepo->getList($request, $paginated);
    }

    public function store(array $data, ?Model $existingModel = null): Model|Exception
    {
        DB::beginTransaction();
        try {
            $rating = $existingModel ?? new Rating();
            $rating->fill($data);
            $rating->save();

            DB::commit();
            return $rating;
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function delete(Model $model): bool|Exception
    {
        DB::beginTransaction();
        try {
            $model->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
}
