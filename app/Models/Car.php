<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'brand', 'model', 'image', 'type', 'color', 'manufacturingYear', 'concesionaire_id' ];

    /**
     * Get the comments for the blog post.
     */
    public function Comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the comments for the blog post.
     */
    public function Brand(): HasOne
    {
        return $this->hasOne( Brand::class, 'id', 'id');
    }

    /**
     * Get the comments for the blog post.
    */
    public function Concessionaire(): BelongsTo
    {
        return $this->belongsTo(Concessionaire::class);
    }


}
