<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Yajra\Oci8\Eloquent\OracleEloquent as Model;

class Badge extends Model
{
    use HasFactory;

    protected $table = 'badges';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'description',
        'badge_icon',
        'category',
        'rarity',
        'unlock_requirement',
        'related_challenge_id',
        'related_collection_id',
        'status',
    ];

    protected $casts = [
        'related_challenge_id' => 'integer',
        'related_collection_id' => 'integer',
    ];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class, 'related_challenge_id');
    }

    public function collection()
    {
        return $this->belongsTo(Collection::class, 'related_collection_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges', 'badge_id', 'user_id')
            ->withPivot('earned_at')
            ->withTimestamps();
    }
}
