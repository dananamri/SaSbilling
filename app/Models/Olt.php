<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Olt extends Model
{
    protected $fillable = ['name', 'brand', 'host', 'snmp_community', 'snmp_port', 'telnet_username', 'telnet_password_encrypted', 'location', 'is_active', 'last_polled_at', 'last_status'];

    protected $casts = ['is_active' => 'boolean', 'last_polled_at' => 'datetime', 'last_status' => 'array'];

    public function onus(): HasMany
    {
        return $this->hasMany(Onu::class);
    }
}
