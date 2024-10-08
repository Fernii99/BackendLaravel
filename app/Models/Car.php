<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = 'car';

    protected $fillable = ['id', 'brand_id', 'image', 'model', 'type', 'color', 'manufacturingYear']; // Adjust according to your table structure

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'car_id');
    }
}
