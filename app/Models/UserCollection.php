<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCollection extends Model
{
    use HasFactory;

    protected $table = 'user_collections';

    protected $fillable = [
        'user_id',
        'collection_id',
        'progress_percentage',
        'status',
        'joined_at',
    ];

    protected $casts = [
        'progress_percentage' => 'integer',
        'joined_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }
}
