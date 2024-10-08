<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function concessionaires()
    {
        return $this->hasMany(Concessionaire::class); // One brand has many concessionaires
    }


    public function car()
    {
        return $this->belongsToMany(Car::class, 'brand');

    }
}
