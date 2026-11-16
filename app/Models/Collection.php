<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Yajra\Oci8\Eloquent\OracleEloquent as Model;

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

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'collection_challenges', 'collection_id', 'challenge_id')->withTimestamps();
    }

    public function badges()
    {
        return $this->hasMany(Badge::class, 'related_collection_id');
    }

    public function userCollections()
    {
        return $this->hasMany(UserCollection::class, 'collection_id');
    }
}
