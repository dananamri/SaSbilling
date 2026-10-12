<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BookEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookEntryController extends Controller
{
    public function index(Request $request): View
    {
        $query = BookEntry::with('user');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $entries = (clone $query)->orderByDesc('date')->orderByDesc('id')->paginate(15);

        $totalIncome = (float) BookEntry::where('type', 'income')->sum('amount');
        $totalExpense = (float) BookEntry::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('admin.book_entries.index', compact('entries', 'totalIncome', 'totalExpense', 'balance'));
    }

    public function create(): View
    {
        return view('admin.book_entries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'receipt' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('receipt')) {
            $validated['receipt_path'] = $request->file('receipt')->store('receipts', 'public');
        }

        $validated['user_id'] = auth()->id();

        $entry = BookEntry::create($validated);
        ActivityLog::log('created', 'pembukuan', "Menambah kas {$entry->type}: Rp ".number_format($entry->amount, 0, ',', '.')." - {$entry->category}");

        return redirect()->route('admin.book-entries.index')->with('success', 'Catatan kas pembukuan berhasil disimpan.');
    }

    public function destroy(BookEntry $bookEntry): RedirectResponse
    {
        $desc = "{$bookEntry->entry_number} ({$bookEntry->category})";
        $bookEntry->delete();
        ActivityLog::log('deleted', 'pembukuan', "Menghapus catatan kas: {$desc}");

        return redirect()->route('admin.book-entries.index')->with('success', 'Catatan berhasil dihapus.');
    }
}
