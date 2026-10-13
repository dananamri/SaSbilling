<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PaymentTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentTicketController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1000',
            'bank_name' => 'required|string|max:100',
            'account_name' => 'required|string|max:100',
            'payment_date' => 'required|date|before_or_equal:today',
            'proof' => 'required|image|max:4096',
            'notes' => 'nullable|string|max:500',
        ]);

        $customer = auth('customer')->user();
        $invoice = Invoice::where('id', $validated['invoice_id'])->where('customer_id', $customer->id)->firstOrFail();

        $path = $request->file('proof')->store('payment_proofs', 'public');

        PaymentTicket::create([
            'customer_id' => $customer->id,
            'invoice_id' => $invoice->id,
            'amount' => $validated['amount'],
            'bank_name' => $validated['bank_name'],
            'account_name' => $validated['account_name'],
            'payment_date' => $validated['payment_date'],
            'proof_path' => $path,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu verifikasi admin.');
    }
}
