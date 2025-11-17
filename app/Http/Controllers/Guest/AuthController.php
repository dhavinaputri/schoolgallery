<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('guest.login');
    }

    public function showRegisterForm()
    {
        return view('guest.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'Harap centang kotak "Saya bukan robot".',
            'g-recaptcha-response.captcha' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
        ]);

        $credentials = $request->only('email', 'password');

        // Cek user inactive sebelum attempt
        $user = User::where('email', $request->email)->first();
        if ($user && isset($user->is_active) && !$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Akun Anda dinonaktifkan. Silakan hubungi admin.'],
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Cek apakah email sudah diverifikasi
            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('guest.verification.notice')
                    ->with('warning', 'Anda harus memverifikasi email Anda terlebih dahulu sebelum login.');
            }

            // Catat last_login_at
            if (Auth::user()) {
                Auth::user()->forceFill(['last_login_at' => now()])->save();
            }
            
            return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali!');
        }

        throw ValidationException::withMessages([
            'email' => ['Email atau password yang Anda masukkan salah.'],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'Harap centang kotak "Saya bukan robot".',
            'g-recaptcha-response.captcha' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        // Redirect ke halaman notice tanpa auto-login
        return redirect()->route('guest.verification.notice')
            ->with('success', 'Akun berhasil dibuat! Silakan cek email Anda untuk verifikasi.')
            ->with('email', $user->email);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }

    // Forgot Password (Guest)
    public function showForgotPasswordForm()
    {
        return view('guest.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetPasswordForm(string $token)
    {
        return view('guest.reset-password', [
            'token' => $token,
            'email' => request('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('guest.login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    // Email Verification Methods
    public function showVerificationNotice()
    {
        // Jika sudah login dan terverifikasi, redirect ke home
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('guest.verify-email');
    }

    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        // Validasi hash
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()->route('guest.verification.notice')
                ->with('error', 'Link verifikasi tidak valid.');
        }

        // Cek apakah sudah terverifikasi
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('guest.login')
                ->with('info', 'Email Anda sudah diverifikasi sebelumnya. Silakan login.');
        }

        // Mark email as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('guest.login')
            ->with('success', 'Email berhasil diverifikasi! Silakan login untuk melanjutkan.');
    }

    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Email tidak ditemukan.'],
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'Email Anda sudah diverifikasi. Silakan login.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi telah dikirim ulang ke email Anda!');
    }
}
