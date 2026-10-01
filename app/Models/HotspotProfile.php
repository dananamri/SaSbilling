<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HotspotProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'price',
        'speed',
        'validity',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(HotspotVoucher::class, 'profile_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(HotspotMember::class, 'profile_id');
    }
}
