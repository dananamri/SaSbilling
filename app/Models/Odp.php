<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Odp extends Model
{
    protected $fillable = ['code', 'name', 'address', 'latitude', 'longitude', 'capacity', 'used_ports', 'status'];

    protected $casts = ['latitude' => 'decimal:7', 'longitude' => 'decimal:7'];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
