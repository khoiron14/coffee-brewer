<?php

namespace App\Repositories;

use App\Models\Recipe;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class RecipeRepository extends BaseRepository
{
    /**
     * Specify Model class name
     */
    protected string $model = Recipe::class;

    /**
     * Orderable columns.
     */
    protected array $orderable = [
        'created_at'
    ];

    public function getList(array $request = [], bool $paginated = true): Collection|LengthAwarePaginator
    {
        $request['per_page'] = $request['per_page'] ?? 10;
        $request['name'] = $request['name'] ?? null;

        $query = $this->getModel()
            ->query()
            ->when(!is_null($request['name']), fn($q) => $q->where('name', 'ILIKE', '%' . $request['name'] . '%'))
            ->latest();

        return $paginated
            ? $query->paginate($request['per_page'])
            : $query->latest()->get();
    }
}
