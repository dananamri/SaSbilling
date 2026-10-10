<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Enums\CustomerStatus;
use App\Enums\InvoiceStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Setting;
use App\Services\BillingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('customer.auth.login');
    }

    public function showRegisterForm(): View
    {
        return view('customer.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:customers,username',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|string|max:20|unique:customers,phone',
            'address' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
            'package_id' => 'required|exists:packages,id',
            'payment_method' => 'required|string',
        ]);

        $package = Package::findOrFail($validated['package_id']);

        $customer = Customer::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
            'package_id' => $validated['package_id'],
            'status' => CustomerStatus::Pending,
            'billing_day' => now()->day,
            'joined_at' => now(),
        ]);

        // Buat invoice perdana pendaftaran
        $billingService = app(BillingService::class);
        $invoice = Invoice::create([
            'invoice_number' => $billingService->generateInvoiceNumber(),
            'customer_id' => $customer->id,
            'period' => now()->format('Y-m'),
            'amount' => $package->price,
            'status' => InvoiceStatus::Unpaid,
            'due_date' => now()->addDays(3),
        ]);

        // Simpan payment pending
        Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $package->price,
            'method' => $validated['payment_method'],
            'status' => 'pending',
            'paid_by' => $customer->name,
            'notes' => 'Pembayaran pendaftaran baru via '.$validated['payment_method'],
        ]);

        return redirect()->route('customer.pending-approval', ['customer' => $customer->id]);
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        $credentials = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $login, 'password' => $password]
            : (is_numeric($login) && strlen($login) >= 10
                ? ['phone' => $login, 'password' => $password]
                : ['username' => $login, 'password' => $password]);

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::guard('customer')->user();

            if ($user->status === CustomerStatus::Pending) {
                Auth::guard('customer')->logout();

                return redirect()->route('customer.pending-approval', ['customer' => $user->id])
                    ->with('warning', 'Akun Anda sedang menunggu persetujuan admin.');
            }

            if ($user->status === CustomerStatus::Inactive) {
                Auth::guard('customer')->logout();

                return back()->withErrors(['login' => 'Akun Anda dinonaktifkan. Silakan hubungi admin.'])->onlyInput('login');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors([
            'login' => 'Username/telepon atau password salah.',
        ])->onlyInput('login');
    }

    public function pendingApproval(Request $request): View
    {
        $customerId = $request->query('customer');
        $customer = $customerId ? Customer::with(['package', 'invoices.payments'])->find($customerId) : null;

        $settings = Setting::all()->pluck('value', 'key');

        return view('customer.auth.pending-approval', compact('customer', 'settings'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login');
    }
}
