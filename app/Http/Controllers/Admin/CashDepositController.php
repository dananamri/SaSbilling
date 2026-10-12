<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CashDeposit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashDepositController extends Controller
{
    public function index(): View
    {
        $deposits = CashDeposit::with(['cashier', 'approver'])->orderByDesc('created_at')->paginate(15);

        return view('admin.cash_deposits.index', compact('deposits'));
    }

    public function create(): View
    {
        return view('admin.cash_deposits.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'deposit_date' => 'required|date',
            'notes' => 'nullable|string',
            'proof' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('proof')) {
            $validated['proof_path'] = $request->file('proof')->store('cash_deposits', 'public');
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        $deposit = CashDeposit::create($validated);
        ActivityLog::log('created', 'setoran-kasir', 'Menyerahkan setoran kasir Rp '.number_format($deposit->amount, 0, ',', '.'));

        return redirect()->route('admin.cash-deposits.index')->with('success', 'Setoran kasir berhasil dikirim, menunggu verifikasi / persetujuan admin.');
    }

    public function approve(Request $request, CashDeposit $cashDeposit): RedirectResponse
    {
        $cashDeposit->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        ActivityLog::log('updated', 'setoran-kasir', "Menyetujui setoran {$cashDeposit->deposit_number} senilai Rp ".number_format($cashDeposit->amount, 0, ',', '.'));

        return back()->with('success', 'Setoran disetujui / diverifikasi.');
    }

    public function reject(Request $request, CashDeposit $cashDeposit): RedirectResponse
    {
        $validated = $request->validate(['rejection_reason' => 'required|string']);

        $cashDeposit->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        ActivityLog::log('updated', 'setoran-kasir', "Menolak setoran {$cashDeposit->deposit_number}");

        return back()->with('success', 'Setoran ditolak.');
    }
}
