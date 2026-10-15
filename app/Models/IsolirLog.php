<?php

namespace App\Models;

use App\Enums\IsolirAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IsolirLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'action',
        'reason',
        'is_automatic',
        'tenant_id',
    ];

    protected function casts(): array
    {
        return [
            'action' => IsolirAction::class,
            'is_automatic' => 'boolean',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
