<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashDeposit extends Model
{
    protected $fillable = ['deposit_number', 'user_id', 'amount', 'deposit_date', 'status', 'notes', 'proof_path', 'approved_by', 'approved_at', 'rejection_reason', 'tenant_id'];

    protected $casts = ['amount' => 'decimal:2', 'deposit_date' => 'date', 'approved_at' => 'datetime'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (empty($m->deposit_number)) {
                $m->deposit_number = 'STR-'.date('Ymd').'-'.str_pad((string) (self::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
