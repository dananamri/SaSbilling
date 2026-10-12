<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Payment;
use App\Models\PaymentTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentTicketController extends Controller
{
    public function index(): View
    {
        $tickets = PaymentTicket::with(['customer', 'invoice'])->orderByDesc('created_at')->paginate(15);

        return view('admin.payment_tickets.index', compact('tickets'));
    }

    public function show(PaymentTicket $paymentTicket): View
    {
        return view('admin.payment_tickets.show', compact('paymentTicket'));
    }

    public function approve(PaymentTicket $paymentTicket): RedirectResponse
    {
        // Buat payment jika terkait invoice
        if ($paymentTicket->invoice_id) {
            Payment::create([
                'invoice_id' => $paymentTicket->invoice_id,
                'customer_id' => $paymentTicket->customer_id,
                'user_id' => auth()->id(),
                'amount' => $paymentTicket->amount,
                'method' => 'transfer',
                'status' => 'success',
                'notes' => 'Verifikasi tiket pembayaran '.$paymentTicket->ticket_number,
                'paid_at' => now(),
            ]);

            $paymentTicket->invoice->update(['status' => InvoiceStatus::Paid, 'paid_at' => now()]);
        }

        $paymentTicket->update([
            'status' => 'approved',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);
        ActivityLog::log('updated', 'tiket-pembayaran', "Menyetujui tiket pembayaran {$paymentTicket->ticket_number} pelanggan #{$paymentTicket->customer_id}");

        return redirect()->route('admin.payment-tickets.index')->with('success', 'Tiket pembayaran disetujui & invoice dilunasi otomatis.');
    }

    public function reject(Request $request, PaymentTicket $paymentTicket): RedirectResponse
    {
        $validated = $request->validate(['rejection_reason' => 'required|string']);

        $paymentTicket->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);
        ActivityLog::log('updated', 'tiket-pembayaran', "Menolak tiket pembayaran {$paymentTicket->ticket_number}");

        return redirect()->route('admin.payment-tickets.index')->with('success', 'Tiket pembayaran ditolak.');
    }
}
