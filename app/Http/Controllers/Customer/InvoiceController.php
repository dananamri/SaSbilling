<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $customer = auth('customer')->user();

        $invoices = Invoice::where('customer_id', $customer->id)
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('customer.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice): View
    {
        $customer = auth('customer')->user();
        abort_unless($invoice->customer_id === $customer->id, 403);

        $invoice->load(['customer.package', 'payments']);

        return view('customer.invoices.show', compact('invoice'));
    }

    public function download(Invoice $invoice)
    {
        $customer = auth('customer')->user();
        abort_unless($invoice->customer_id === $customer->id, 403);

        $pdf = new Dompdf;
        $pdf->loadHtml(view('customer.invoices.pdf', compact('invoice'))->render());
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->stream('invoice-'.$invoice->invoice_number.'.pdf');
    }

    public function print(Invoice $invoice)
    {
        $customer = auth('customer')->user();
        abort_unless($invoice->customer_id === $customer->id, 403);

        return view('customer.invoices.print', compact('invoice'));
    }
}
