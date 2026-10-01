<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $customer = auth('customer')->user();

        $payments = Payment::whereHas('invoice', fn ($q) => $q->where('customer_id', $customer->id))
            ->with('invoice')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->date_from, fn ($q) => $q->whereDate('created_at', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('created_at', '<=', $request->date_to))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('customer.payments.index', compact('payments'));
    }

    public function show(Payment $payment): View
    {
        $this->authorize('view', $payment);

        $payment->load('invoice.customer');

        return view('customer.payments.show', compact('payment'));
    }

    public function downloadReceipt(Payment $payment)
    {
        $this->authorize('view', $payment);

        $pdf = new Dompdf();
        $pdf->loadHtml(view('customer.payments.receipt', compact('payment'))->render());
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->stream('bukti-pembayaran-'.$payment->id.'.pdf');
    }
}
