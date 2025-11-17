<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        // Users query with filters (monitoring role user pada section Pengguna)
        $query = User::query()->where('role', 'user');

        // Filters
        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        // Role tidak ditampilkan di halaman ini; fokus ke user saja
        if ($request->filled('active')) {
            $active = $request->boolean('active');
            $query->where('is_active', $active);
        }

        $users = $query->latest()->paginate(12)->withQueryString();

        // Datasets untuk Super Admin dan Admin
        $superAdmins = Admin::where('role', 'super_admin')->latest()->get();
        $admins = Admin::where('role', 'admin')->latest()->paginate(12, ['*'], 'admins_page')->withQueryString();

        // Stats gabungan untuk ringkasan
        $stats = [
            'total_super_admins' => Admin::where('role', 'super_admin')->count(),
            'total_admins' => Admin::where('role', 'admin')->count(),
            'total_users' => User::where('role', 'user')->count(),
            'active_users' => User::where('role', 'user')->where('is_active', true)->count(),
            'inactive_users' => User::where('role', 'user')->where('is_active', false)->count(),
        ];

        return view('admin.users.index', compact('users', 'admins', 'superAdmins', 'stats'));
    }

    public function edit(User $user)
    {
        $roles = ['super_admin' => 'Super Admin', 'admin' => 'Admin', 'user' => 'User'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'is_active' => 'nullable|boolean',
        ]);

        // Monitoring Pengguna tidak mengubah role user; hanya status aktif
        $user->is_active = $request->boolean('is_active', true);
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Status pengguna berhasil diperbarui.');
    }

    public function toggleActive(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();
        return back()->with('success', 'Status pengguna diperbarui.');
    }
}
