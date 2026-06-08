<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brewer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'shape_type'
    ];

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }
}
