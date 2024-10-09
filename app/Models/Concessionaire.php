<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

use App\Models\Car;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Concessionaire extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'brands', 'cars'];

    public function Cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function Brand(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class, 'ConcessionaireBrand', 'concessionaire_id', 'brand_id');
    }

}
