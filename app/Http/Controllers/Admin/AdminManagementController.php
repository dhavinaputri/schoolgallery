<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminPasswordReseted;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = Admin::where('role', '!=', 'super_admin')->get();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:super_admin,admin',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'email_verified_at' => now(),
        ]);

        // Log aktivitas
        $this->logActivity('create', $admin);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'role' => 'required|in:super_admin,admin',
            'is_active' => 'boolean',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ]);

        // Log aktivitas
        $this->logActivity('update', $admin);

        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(Admin $admin)
    {
        // Prevent deleting own account
        if ($admin->id === auth('admin')->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Log aktivitas sebelum menghapus
        $this->logActivity('delete', $admin);

        $admin->delete();
        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }

    public function toggleActive(Admin $admin)
    {
        if ($admin->id === auth('admin')->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah status akun sendiri.');
        }

        $admin->is_active = !$admin->is_active;
        $admin->save();

        $this->logActivity('update', $admin);

        return back()->with('success', 'Status akun ' . $admin->name . ' diubah menjadi ' . ($admin->is_active ? 'Aktif' : 'Nonaktif') . '.');
    }

    public function resetPassword(Admin $admin)
    {
        // Generate new password dengan format yang lebih mudah diingat
        $newPassword = $this->generateSecurePassword();
        
        // Update password
        $admin->update([
            'password' => $newPassword,
        ]);

        // Log aktivitas (jika ada ActivityLog model)
        $this->logActivity('reset_password', $admin);

        // Kirim email ke admin yang direset
        try {
            Mail::to($admin->email)->send(new AdminPasswordReseted($admin, $newPassword));
        } catch (\Throwable $e) {
            // Abaikan kegagalan email agar UX tetap lanjut
        }

        return back()
            ->with('success', 'Password untuk admin ' . $admin->name . ' berhasil direset.')
            ->with('success_title', 'Password Berhasil Direset!')
            ->with('new_password', $newPassword)
            ->with('reset_admin_name', $admin->name)
            ->with('reset_admin_email', $admin->email);
    }

    private function generateSecurePassword()
    {
        // Generate password dengan format: 2 huruf besar + 2 huruf kecil + 2 angka + 2 simbol
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*';
        
        $password = '';
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $symbols[rand(0, strlen($symbols) - 1)];
        $password .= $symbols[rand(0, strlen($symbols) - 1)];
        
        // Shuffle the password
        return str_shuffle($password);
    }

    private function logActivity($action, $admin)
    {
        // Simpan aktivitas ke ActivityLog
        $currentAdmin = auth('admin')->user();
        
        $descriptions = [
            'reset_password' => 'Reset password untuk admin ' . $admin->name,
            'create' => 'Membuat admin baru: ' . $admin->name,
            'update' => 'Memperbarui data admin: ' . $admin->name,
            'delete' => 'Menghapus admin: ' . $admin->name,
        ];
        
        ActivityLog::log(
            $action,
            $descriptions[$action] ?? 'Melakukan aksi pada admin: ' . $admin->name,
            $currentAdmin->id,
            $admin,
            [
                'target_admin_name' => $admin->name,
                'target_admin_email' => $admin->email,
                'performed_by' => $currentAdmin->name
            ]
        );
    }
}
