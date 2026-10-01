<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotspotVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'code',
        'status',
        'used_at',
        'used_by',
    ];

    protected function casts(): array
    {
        return [
            'used_at' => 'datetime',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(HotspotProfile::class, 'profile_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(HotspotMember::class, 'used_by');
    }
}
