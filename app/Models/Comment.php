<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

use App\Models\Car;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['comment_id', 'car_id', 'comment_text', 'user', 'created_at'];

    /**
     * Get the comments that owns the car.
     */
    public function Car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

}
