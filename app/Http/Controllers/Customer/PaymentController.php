<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentTicket;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        $customer = auth('customer')->user();

        // Tagihan yang perlu dibayar (unpaid, partial, overdue)
        $unpaidInvoices = Invoice::where('customer_id', $customer->id)
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->orderBy('due_date')
            ->get();

        // Tiket bukti bayar yang sedang pending verifikasi atau baru diajukan
        $pendingTickets = PaymentTicket::where('customer_id', $customer->id)
            ->with('invoice')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Riwayat pembayaran berhasil
        $payments = Payment::whereHas('invoice', fn ($q) => $q->where('customer_id', $customer->id))
            ->with('invoice')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('customer.payments.index', compact('customer', 'unpaidInvoices', 'pendingTickets', 'payments'));
    }

    public function show(Payment $payment): View
    {
        $customer = auth('customer')->user();
        abort_unless($payment->invoice->customer_id === $customer->id, 403);

        $payment->load('invoice.package');

        return view('customer.payments.show', compact('payment'));
    }

    public function downloadReceipt(Payment $payment)
    {
        $customer = auth('customer')->user();
        abort_unless($payment->invoice->customer_id === $customer->id, 403);

        $payment->load('invoice.package', 'invoice.customer');

        return view('customer.payments.receipt', compact('payment'));
    }
}
