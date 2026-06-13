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
        $request['user_id'] = $request['user_id'] ?? null;
        $request['is_published'] = $request['is_published'] ?? null;

        $query = $this->getModel()
            ->query()
            ->with(['brewer:id,name', 'coffee:id,name', 'user:id,name'])
            ->when(!is_null($request['user_id']), fn($q) => $q->where('user_id', $request['user_id']))
            ->when(!is_null($request['name']), fn($q) => $q->where('name', 'ILIKE', '%' . $request['name'] . '%'))
            ->when(!is_null($request['is_published']), fn($q) => $q->where('is_published', $request['is_published']))
            ->latest();

        return $paginated
            ? $query->paginate($request['per_page'])
            : $query->latest()->get();
    }
}
