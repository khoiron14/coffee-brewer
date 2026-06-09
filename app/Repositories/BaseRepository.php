<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Torann\LaravelRepository\Repository;

class BaseRepository extends Repository
{
    protected function applyWhere($query, array $where)
    {
        foreach ($where as $key => $value) {
            if (is_array($value)) {
                [$field, $operator, $val] = $value;
                $query->where($field, $operator, $val);
            } elseif ($value === null) {
                $query->whereNull($key);
            } else {
                $query->where($key, '=', $value);
            }
        }
        
        return $query;
    }

    public function findOnlyTrashed(string $id, array $columns = ['*'])
    {
        return $this->getModel()
            ->onlyTrashed()
            ->find($id, $columns);
    }

    public function findWithTrashed(string $id, array $columns = ['*'])
    {
        return $this->getModel()
            ->withTrashed()
            ->find($id, $columns);
    }

    public function updateWhere(array $where, array $request)
    {
        $query = $this->applyWhere($this->getModel()->newQuery(), $where);

        return $query->update($request);
    }

    public function deleteWhere(array $where)
    {
        $query = $this->applyWhere($this->getModel()->newQuery(), $where);

        return $query->delete();
    }

    public function forceDeleteWhere(array $where)
    {
        $query = $this->applyWhere($this->getModel()->newQuery(), $where);

        return $query->forceDelete();
    }

    public function findWhereFirstOnlyTrashed(array $where, array $columns = ['*'])
    {
        $query = $this->applyWhere(
            $this->getModel()->onlyTrashed(),
            $where
        );

        return $query->first($columns);
    }

    public function findWhereFirstWithTrashed(array $where, array $columns = ['*'])
    {
        $query = $this->applyWhere(
            $this->getModel()->withTrashed(),
            $where
        );

        return $query->first($columns);
    }

    public function findRandomOrCreate()
    {
        return $this->getModel()->inRandomOrder()->first() ?: $this->getModel()->factory()->create();
    }

    public function factoryCreate(array $data = [])
    {
        return $this->getModel()->factory()->create($data);
    }

    public function factoryCountCreate(array $data = [], int $count = 1)
    {
        return $this->getModel()->factory()->count($count)->create($data);
    }

    public function firstOrCreate(array $exists = [], array $request = [])
    {
        return $this->getModel()->firstOrCreate($exists, $request);
    }

    public function updateOrCreate(array $exists = [], array $request = [])
    {
        return $this->getModel()->updateOrCreate($exists, $request);
    }

    public function findWhereFirst(array $where = [], array $columns = ['*'])
    {
        $this->newQuery();
        $this->applyWhere($this->query, $where);

        return $this->query->first($columns);
    }

    public function findWhereFirstOrFail(array $where, array $columns = ['*'], ?array $order = null)
    {
        $this->newQuery();
        $this->applyWhere($this->query, $where);

        if ($order) {
            foreach ($order as $field => $direction) {
                $this->query->orderBy($field, $direction);
            }
        }

        return $this->query->firstOrFail($columns);
    }

    public function factoryMake(array $request)
    {
        return $this->getModel()->factory()->make($request);
    }

    public function deleteById(string $id)
    {
        $record = $this->getModel()->findOrFail($id);

        return $record->delete();
    }

    public function findForUpdate(string $id)
    {
        return $this->getModel()->where('id', $id)->lockForUpdate()->first();
    }

    public function findWhereFirstForUpdate(array $where)
    {
        $query = $this->getModel()->lockForUpdate();
        $query = $this->applyWhere($query, $where);
        
        return $query->orderBy('id', 'desc')->first();
    }

    protected function extractInsertDataFromWhere(array $where): array
    {
        $insert = [];

        foreach ($where as $k => $v) {
            // case: 'field' => 'value'  (string key)
            if (is_string($k) && !is_array($v)) {
                $insert[$k] = $v;
                continue;
            }

            // case: ['field', '=', 'value']  (numeric key but operator '=')
            if (is_array($v) && count($v) === 3 && $v[1] === '=') {
                $insert[$v[0]] = $v[2];
                continue;
            }

            // ignore other types (ranges, date comparisons, etc.)
        }

        return $insert;
    }

    /**
     * Safely update an existing record or create a new one using row-level locking.
     *
     * Automatically handles duplicate key conflicts caused by concurrent inserts.
     * Will reuse an existing transaction if already active.
     *
     * @param array $where Conditions to uniquely identify the record (must be indexed).
     * @param array $data  Data to update or insert.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateOrCreateWithLock(array $where, array $data, $record = null)
    {
        $callback = function () use ($where, $data, $record) {
            $record = $record ?? $this->findWhereFirstForUpdate($where);

            if ($record) {
                $record->update($data);
                return $record;
            }

            $createData = array_merge($this->extractInsertDataFromWhere($where), $data);
            try {
                return $this->create($createData);
            } catch (Exception $e) {
                Log::warning('Failed to create record in updateOrCreateWithLock: ', [
                    'where' => $where,
                    'create' => $createData,
                    'message' => $e->getMessage(),
                ]);

                return $this->findWhereFirstForUpdate($where);
            }
        };

        if (DB::transactionLevel() > 0) {
            return $callback();
        }

        return DB::transaction($callback);
    }
}
