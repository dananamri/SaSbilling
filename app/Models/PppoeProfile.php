<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PppoeProfile extends Model
{
    protected $fillable = ['router_id', 'name', 'local_address', 'remote_address', 'rate_limit', 'dns_server', 'tenant_id'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }

    public function secrets(): HasMany
    {
        return $this->hasMany(PppoeSecret::class);
    }
}
