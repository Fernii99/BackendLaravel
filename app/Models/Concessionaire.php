<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class concessionaire extends Model
{
    use HasFactory;

    protected $fillable = ['Concessionaire'];

    public function brand()
    {
        return $this->belongsTo(Brand::class); // Each concessionaire belongs to one brand
    }

    public function vehicle()
    {
        return $this->belongsTo(Brand::class); // Each concessionaire belongs to one brand
    }
}
