<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Router extends Model
{
    protected $fillable = ['name', 'host', 'port', 'username', 'password_encrypted', 'description', 'is_active', 'last_checked_at', 'last_status', 'tenant_id'];

    protected $casts = ['port' => 'integer', 'is_active' => 'boolean', 'last_checked_at' => 'datetime', 'last_status' => 'array'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function pppoeProfiles(): HasMany
    {
        return $this->hasMany(PppoeProfile::class);
    }

    public function pppoeSecrets(): HasMany
    {
        return $this->hasMany(PppoeSecret::class);
    }

    public function routerBoxes(): HasMany
    {
        return $this->hasMany(RouterBox::class);
    }

    public function bandwidthSnapshots(): HasMany
    {
        return $this->hasMany(BandwidthSnapshot::class);
    }
}
