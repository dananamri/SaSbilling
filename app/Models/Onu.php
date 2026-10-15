<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Onu extends Model
{
    protected $fillable = ['olt_id', 'customer_id', 'pon_port', 'onu_index', 'serial_number', 'model', 'rx_power', 'tx_power', 'temperature', 'status', 'last_seen_at', 'tenant_id'];

    protected $casts = ['rx_power' => 'decimal:2', 'tx_power' => 'decimal:2', 'temperature' => 'decimal:2', 'last_seen_at' => 'datetime'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function olt(): BelongsTo
    {
        return $this->belongsTo(Olt::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
