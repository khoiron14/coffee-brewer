<?php

namespace App\Models;

use App\Models\Brewer;
use App\Models\Coffee;
use App\Models\Rating;
use App\Models\RecipeStep;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'brewer_id',
        'coffee_id',
        'name',
        'description',
        'coffee_weight',
        'water_weight',
        'grind_size',
        'temperature',
        'total_duration',
        'is_published',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function brewer(): BelongsTo
    {
        return $this->belongsTo(Brewer::class);
    }

    public function coffee(): BelongsTo
    {
        return $this->belongsTo(Coffee::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function recipeSteps(): HasMany
    {
        return $this->hasMany(RecipeStep::class);
    }
}
