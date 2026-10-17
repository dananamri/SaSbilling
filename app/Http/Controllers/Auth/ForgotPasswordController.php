<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm(string $type = 'customer'): View
    {
        return view('auth.forgot-password', [
            'type' => $type === 'admin' ? 'admin' : 'customer',
        ]);
    }

    public function sendResetOtp(Request $request): RedirectResponse
    {
        $type = $request->input('type') === 'admin' ? 'admin' : 'customer';

        $request->validate([
            'identity' => 'required|string',
            'type' => 'required|in:admin,customer',
        ]);

        $identity = trim($request->input('identity'));

        if ($type === 'admin') {
            $user = User::where('email', $identity)
                ->orWhere('username', $identity)
                ->first();

            if (! $user) {
                return back()->withErrors(['identity' => 'Akun Admin/Staf dengan username atau email tersebut tidak ditemukan.'])->withInput();
            }

            $email = $user->email;
        } else {
            $customer = Customer::where('email', $identity)
                ->orWhere('phone', $identity)
                ->orWhere('username', $identity)
                ->first();

            if (! $customer) {
                return back()->withErrors(['identity' => 'Akun Pelanggan dengan data tersebut tidak ditemukan.'])->withInput();
            }

            $email = $customer->email ?: $customer->phone;
        }

        // Generate 6-digit code OTP
        $token = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Simpan sesi untuk verifikasi OTP
        session([
            'reset_password_target' => $email,
            'reset_password_type' => $type,
            'demo_otp' => config('app.debug') ? $token : null, // kemudahan di development
        ]);

        return redirect()->route('password.reset.form')
            ->with('status', 'Kode OTP pemulihan telah disiapkan. Masukkan kode 6 digit untuk mereset kata sandi Anda.')
            ->with('hint_otp', $token);
    }

    public function showResetForm(): View|RedirectResponse
    {
        $target = session('reset_password_target');
        $type = session('reset_password_type', 'customer');

        if (! $target) {
            return redirect()->route('customer.password.request');
        }

        return view('auth.reset-password', [
            'target' => $target,
            'type' => $type,
            'hintOtp' => session('hint_otp'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $target = session('reset_password_target');
        $type = session('reset_password_type', 'customer');

        if (! $target) {
            return redirect()->route('login')->withErrors(['error' => 'Sesi pemulihan telah kedaluwarsa. Silakan ulangi.']);
        }

        $record = DB::table('password_reset_tokens')->where('email', $target)->first();

        if (! $record || ! Hash::check($request->input('otp'), $record->token)) {
            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.'])->withInput();
        }

        // Cek kedaluwarsa (15 menit)
        if (now()->diffInMinutes($record->created_at) > 15) {
            return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa (lebih dari 15 menit). Silakan minta kode baru.'])->withInput();
        }

        if ($type === 'admin') {
            $user = User::where('email', $target)->first();
            if ($user) {
                $user->update(['password' => Hash::make($request->input('password'))]);
            }
        } else {
            $customer = Customer::where('email', $target)
                ->orWhere('phone', $target)
                ->first();
            if ($customer) {
                $customer->update(['password' => Hash::make($request->input('password'))]);
            }
        }

        // Hapus token setelah digunakan
        DB::table('password_reset_tokens')->where('email', $target)->delete();
        session()->forget(['reset_password_target', 'reset_password_type', 'demo_otp', 'hint_otp']);

        return redirect()->route('login', ['role' => $type])
            ->with('success', 'Kata sandi berhasil diperbarui! Silakan masuk dengan kata sandi baru Anda.');
    }
}
