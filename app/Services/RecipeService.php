<?php

namespace App\Services;

use App\Interfaces\BaseServiceInterface;
use App\Models\Recipe;
use App\Repositories\RecipeRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RecipeService implements BaseServiceInterface
{
    public function __construct(
        public RecipeRepository $recipeRepo
    ) {}

    public function getList(array $request = [], bool $paginated = true): Collection|LengthAwarePaginator
    {
        return $this->recipeRepo->getList($request, $paginated);
    }

    public function store(array $data, ?Model $existingModel = null): Model|Exception
    {
        DB::beginTransaction();
        try {
            $stepsData = Arr::get($data, 'steps', []);
            $recipeData = Arr::except($data, ['steps']);

            $recipe = $existingModel ?? new Recipe();
            $recipe->fill($recipeData);
            $recipe->save();

            if ($existingModel) {
                $recipe->recipeSteps()->delete();
            }

            foreach ($stepsData as $stepData) {
                $recipe->recipeSteps()->create($stepData);
            }

            DB::commit();
            return $recipe;
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
