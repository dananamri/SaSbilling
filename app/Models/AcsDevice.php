<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcsDevice extends Model
{
    protected $fillable = ['customer_id', 'serial_number', 'oui', 'product_class', 'model_name', 'firmware_version', 'ssid_2g', 'ssid_5g', 'last_inform_at', 'parameters', 'tenant_id'];

    protected $casts = ['last_inform_at' => 'datetime', 'parameters' => 'array'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
