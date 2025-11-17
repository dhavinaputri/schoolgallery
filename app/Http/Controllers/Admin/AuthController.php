<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Disable any potential errors from view rendering
        try {
            return view('admin.login'); 
        } catch (\Exception $e) {
            \Log::error('Admin login view error: ' . $e->getMessage());
            return response('Login page temporarily unavailable', 503);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        // Cari admin berdasarkan email
        $candidate = Admin::where('email', $credentials['email'] ?? '')->first();

        if ($candidate) {
            $storedPassword = (string) ($candidate->password ?? '');
            $isBcrypt = str_starts_with($storedPassword, '$2y$');

            // Jika password di DB masih plaintext, jangan panggil attempt() dulu
            if (!$isBcrypt) {
                if (hash_equals($storedPassword, (string) ($credentials['password'] ?? ''))) {
                    // Upgrade ke bcrypt lalu login manual
                    $candidate->password = Hash::make($credentials['password']);
                    $candidate->save();

                    Auth::guard('admin')->login($candidate);

                    $admin = Auth::guard('admin')->user();
                    if (!$admin->is_active) {
                        Auth::guard('admin')->logout();
                        return back()->withErrors([
                            'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                        ]);
                    }

                    try {
                        ActivityLog::log(
                            'login',
                            'Login ke sistem admin',
                            $admin->id,
                            null,
                            [
                                'login_time' => now()->toDateTimeString(),
                                'ip_address' => $request->ip(),
                                'user_agent' => $request->userAgent()
                            ]
                        );
                    } catch (\Exception $e) {
                        // Log silently if activity log fails
                        \Log::warning('Failed to log admin login activity: ' . $e->getMessage());
                    }

                    return redirect()->route('admin.dashboard');
                }

                // Plaintext tapi tidak cocok
                return back()->withErrors([
                    'email' => 'Email atau password salah.',
                ]);
            }
        }

        // Jalur normal untuk password bcrypt
        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();

            if (!$admin->is_active) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                ]);
            }
            
            // Log aktivitas login
            try {
                ActivityLog::log(
                    'login',
                    'Login ke sistem admin',
                    $admin->id,
                    null,
                    [
                        'login_time' => now()->toDateTimeString(),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent()
                    ]
                );
            } catch (\Exception $e) {
                // Log silently if activity log fails
                \Log::warning('Failed to log admin login activity: ' . $e->getMessage());
            }
            
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout()
    {
        $admin = Auth::guard('admin')->user();
        
        // Log aktivitas logout
        if ($admin) {
            try {
                ActivityLog::log(
                    'logout',
                    'Logout dari sistem admin',
                    $admin->id,
                    null,
                    [
                        'logout_time' => now()->toDateTimeString(),
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent()
                    ]
                );
            } catch (\Exception $e) {
                // Log silently if activity log fails
                \Log::warning('Failed to log admin logout activity: ' . $e->getMessage());
            }
        }
        
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    // =====================
    // Forgot + Reset Password
    // =====================

    // Tampilkan form lupa password
    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    // Kirim email reset password
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Tampilkan form reset password
    public function showResetPasswordForm($token)
    {
        return view('admin.auth.reset-password', ['token' => $token]);
    }

    // Proses reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin, $password) {
                $admin->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                Auth::guard('admin')->login($admin);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.dashboard')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}