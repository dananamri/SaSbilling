<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'period',
        'amount',
        'late_fee',
        'discount',
        'status',
        'due_date',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'late_fee' => 'decimal:2',
            'discount' => 'decimal:2',
            'status' => InvoiceStatus::class,
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Total tagihan akhir setelah denda dan diskon.
     */
    public function totalDue(): float
    {
        return (float) $this->amount + (float) $this->late_fee - (float) $this->discount;
    }

    public function totalPaid(): float
    {
        return (float) $this->payments()->where('status', 'success')->sum('amount');
    }

    public function remaining(): float
    {
        return max(0, $this->totalDue() - $this->totalPaid());
    }

    public function isFullyPaid(): bool
    {
        return $this->remaining() <= 0;
    }

    public function isOverdue(): bool
    {
        return $this->status !== InvoiceStatus::Paid && $this->due_date->isPast();
    }
}
