<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'car_comments';
    protected $fillable = ['comment_id', 'car_id', 'comment_text', 'user', 'created_at'];

    // Define the relationship to the Car model
    public function car()
    {
        return $this->belongsTo(Car::class, 'id');
    }
}
