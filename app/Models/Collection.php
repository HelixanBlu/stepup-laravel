<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    use HasFactory;

    protected $table = 'collections';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'description',
        'cover_image',
        'difficulty',
        'estimated_duration',
        'status',
    ];

    public function challenges(): BelongsToMany
    {
        return $this->belongsToMany(Challenge::class, 'collection_challenges', 'collection_id', 'challenge_id')->withTimestamps();
    }

    public function badges(): HasMany
    {
        return $this->hasMany(Badge::class, 'related_collection_id');
    }

    public function userCollections(): HasMany
    {
        return $this->hasMany(UserCollection::class, 'collection_id');
    }
}
