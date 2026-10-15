<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PppoeSecret extends Model
{
    protected $fillable = ['router_id', 'customer_id', 'pppoe_profile_id', 'username', 'password', 'service', 'local_address', 'remote_address', 'is_active', 'comment', 'last_connected_at', 'tenant_id'];

    protected $casts = ['is_active' => 'boolean', 'last_connected_at' => 'datetime', 'password' => 'hashed'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(PppoeProfile::class, 'pppoe_profile_id');
    }
}
