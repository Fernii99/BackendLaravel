<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

use App\Models\Concessionaire;
use App\Models\Car;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name'];

    /**
     * Get the user that owns the phone.
    */
    public function id(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * Get the comments for the blog post.
     */
    public function Cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    /**
     * The users that belong to the role.
     */
    public function Concessionaire (): BelongsToMany
    {
        return $this->belongsToMany(ConcessionaireBrand::class, 'concessionaires_brands', 'brand_id', 'concessionaire_id');
    }
}
