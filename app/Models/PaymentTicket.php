<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTicket extends Model
{
    protected $fillable = ['ticket_number', 'customer_id', 'invoice_id', 'amount', 'bank_name', 'account_name', 'account_number', 'payment_date', 'proof_path', 'status', 'verified_by', 'verified_at', 'notes', 'rejection_reason', 'tenant_id'];

    protected $casts = ['amount' => 'decimal:2', 'payment_date' => 'date', 'verified_at' => 'datetime'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            if (empty($m->ticket_number)) {
                $m->ticket_number = 'PAYT-'.date('Ymd').'-'.str_pad((string) (self::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
