<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HotspotMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'fullname',
        'phone',
        'profile_id',
        'status',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'expired_at' => 'datetime',
        ];
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(HotspotProfile::class, 'profile_id');
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(HotspotVoucher::class, 'used_by');
    }
}
