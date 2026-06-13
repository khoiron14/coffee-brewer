<?php

namespace App\Repositories;

use App\Models\Rating;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RatingRepository extends BaseRepository
{
    /**
     * Specify Model class name
     */
    protected string $model = Rating::class;

    /**
     * Orderable columns.
     */
    protected array $orderable = [
        'created_at'
    ];

    // public function getList(array $request = [], bool $paginated = true): Collection|LengthAwarePaginator
    // {
    //     $request['per_page'] = $request['per_page'] ?? 10;
    //     $request['recipe_id'] = $request['recipe_id'] ?? null;
    //     $request['user_id'] = $request['user_id'] ?? null;

    //     $query = $this->getModel()
    //         ->query()
    //         ->with(['user:id,name', 'recipe:id,name'])
    //         ->when(!is_null($request['recipe_id']), fn($q) => $q->where('recipe_id', $request['recipe_id']))
    //         ->when(!is_null($request['user_id']), fn($q) => $q->where('user_id', $request['user_id']))
    //         ->latest();

    //     return $paginated
    //         ? $query->paginate($request['per_page'])
    //         : $query->latest()->get();
    // }
}
