<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RadiusServer extends Model
{
    protected $fillable = ['name', 'host', 'auth_port', 'acct_port', 'secret', 'is_active', 'tenant_id'];

    protected $casts = ['auth_port' => 'integer', 'acct_port' => 'integer', 'is_active' => 'boolean'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
