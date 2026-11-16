<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Yajra\Oci8\Eloquent\OracleEloquent as Model;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'rewards';
    protected $primaryKey = 'id_reward';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name_reward',
        'description',
        'poin',
        'gambar',
        'stok',
    ];

    protected $casts = [
        'poin' => 'integer',
        'stok' => 'integer',
    ];

    public function redemptions()
    {
        return $this->hasMany(Redemption::class, 'reward_id', 'id_reward');
    }
}
