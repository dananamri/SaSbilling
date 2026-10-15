<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BandwidthSnapshot extends Model
{
    protected $fillable = ['router_id', 'interface_name', 'rx_bytes', 'tx_bytes', 'rx_bps', 'tx_bps', 'recorded_at', 'tenant_id'];

    protected $casts = ['recorded_at' => 'datetime'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }
}
