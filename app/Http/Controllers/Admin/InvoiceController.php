<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CustomerStatus;
use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Services\BillingService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $invoices = Invoice::with('customer.package')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->period, fn ($q) => $q->where('period', $request->period))
            ->when($request->search, fn ($q) => $q->where(function ($query) use ($request) {
                $query->where('invoice_number', 'like', "%{$request->search}%")
                    ->orWhereHas('customer', fn ($cq) => $cq->where('name', 'like', "%{$request->search}%"));
            }))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['customer.package', 'payments']);

        return view('admin.invoices.show', compact('invoice'));
    }

    public function create(): View
    {
        $customers = Customer::where('status', CustomerStatus::Active)->get();

        return view('admin.invoices.create', compact('customers'));
    }

    public function store(Request $request, BillingService $billingService, NotificationService $notificationService): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'period' => 'required|string|max:7',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);

        $invoice = Invoice::create([
            'invoice_number' => $billingService->generateInvoiceNumber(),
            'customer_id' => $customer->id,
            'period' => $validated['period'],
            'amount' => $validated['amount'],
            'status' => InvoiceStatus::Unpaid,
            'due_date' => $validated['due_date'],
        ]);

        if ($request->boolean('send_notification')) {
            $notificationService->sendInvoiceNotification($customer, $invoice);
        }

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice berhasil dibuat.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        if ($invoice->payments()->exists()) {
            return back()->with('error', 'Invoice tidak dapat dihapus karena sudah memiliki pembayaran.');
        }

        $invoice->delete();

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice berhasil dihapus.');
    }
}
