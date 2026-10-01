<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\BillingService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $payments = Payment::with('invoice.customer')
            ->when($request->method, fn ($q) => $q->where('method', $request->method))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function create(): View
    {
        $invoices = Invoice::with('customer')
            ->whereIn('status', ['unpaid', 'partial', 'overdue'])
            ->get();

        $methods = PaymentMethod::cases();

        return view('admin.payments.create', compact('invoices', 'methods'));
    }

    public function store(Request $request, BillingService $billingService, NotificationService $notificationService): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'method' => 'required|in:'.implode(',', array_column(PaymentMethod::cases(), 'value')),
            'reference' => 'nullable|string|max:255',
            'paid_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);

        $payment = Payment::create($validated);

        $billingService->updateInvoiceStatus($invoice);

        if ($invoice->isFullyPaid()) {
            $notificationService->sendPaymentReceived($invoice->customer, $invoice, $payment->amount);
        }

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function show(Payment $payment): View
    {
        $payment->load('invoice.customer');

        return view('admin.payments.show', compact('payment'));
    }
}
