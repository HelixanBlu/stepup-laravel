<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Community extends Model
{
    use HasFactory;

    protected $table = 'community';

    protected $fillable = [
        'deskripsi',
        'gambar',
        'langkah',
        'jarak',
        'kalori',
        'user_id',
    ];

    protected $casts = [
        'langkah' => 'integer',
        'jarak' => 'decimal:2',
        'kalori' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'community_id');
    }
}
