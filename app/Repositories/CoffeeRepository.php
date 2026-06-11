<?php

namespace App\Repositories;

use App\Models\Coffee;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CoffeeRepository extends BaseRepository
{
    /**
     * Specify Model class name
     */
    protected string $model = Coffee::class;

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
        $request['roastery'] = $request['roastery'] ?? null;
        $request['roast_level'] = $request['roast_level'] ?? null;
        $request['user_id'] = $request['user_id'] ?? null;

        $query = $this->getModel()
            ->query()
            ->when(!is_null($request['user_id']), fn($q) => $q->where('user_id', $request['user_id']))
            ->when(!is_null($request['name']), fn($q) => $q->where('name', 'ILIKE', '%' . $request['name'] . '%'))
            ->when(!is_null($request['roastery']), fn($q)=> $q->where('roastery', 'ILIKE', '%', $request['roastery'].'%'))
            ->when(!is_null($request['roast_level']), fn($q)=> $q->where('roast_level', 'ILIKE', '%', $request['roast_level'].'%'))
            ->latest();

        return $paginated
            ? $query->paginate($request['per_page'])
            : $query->latest()->get();
    }
}