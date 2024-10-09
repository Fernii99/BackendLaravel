<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConcessionaireBrand extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'brand_id', 'concessionaire_id'];



}
