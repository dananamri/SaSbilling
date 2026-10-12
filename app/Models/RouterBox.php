<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouterBox extends Model
{
    protected $fillable = ['router_id', 'box_id', 'token', 'status', 'remote_ip', 'last_heartbeat_at'];

    protected $casts = ['last_heartbeat_at' => 'datetime'];

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }
}
