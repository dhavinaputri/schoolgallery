@extends('admin.layouts.app')

@section('title', 'Pengelolaan Pengguna')

@section('content')
<div class="mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800 flex items-center">
                <span class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-2 rounded-lg mr-2 shadow"><i class="fas fa-users"></i></span>
                Monitoring Pengguna
            </h1>
            <p class="text-sm text-gray-500 mt-1">Pantau Admin, Petugas, dan Pengguna dalam satu halaman</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white border shadow-sm text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
            <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white shadow hover:bg-blue-700">
                <i class="fas fa-user-shield mr-2"></i> Manajemen Petugas
            </a>
        </div>
    </div>
</div>

<!-- Monitoring Ringkasan (khusus pengguna) -->
<div class="mb-4">
    <p class="text-xs text-gray-500">Monitoring aktivitas pengguna (role: user). Untuk kelola petugas, gunakan menu <a href="{{ route('admin.admins.index') }}" class="text-blue-600 hover:underline">Manajemen Petugas</a>.</p>
    </div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border rounded-lg p-4">
        <div class="text-sm text-gray-500">Total Pengguna</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</div>
    </div>
    <div class="bg-white border rounded-lg p-4">
        <div class="text-sm text-gray-500">Aktif</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['active_users'] }}</div>
    </div>
    <div class="bg-white border rounded-lg p-4">
        <div class="text-sm text-gray-500">Nonaktif</div>
        <div class="text-2xl font-bold text-gray-800">{{ $stats['inactive_users'] }}</div>
    </div>
</div>

<!-- Super Admin Section (Card Grid) -->
<div class="mb-6">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Admin</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse(($superAdmins ?? []) as $sa)
        <div class="bg-white border rounded-xl p-4 shadow-sm hover:shadow transition">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full overflow-hidden bg-red-100 text-red-600 flex items-center justify-center mr-3 font-semibold">
                    @if($sa->avatar)
                        <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($sa->avatar) }}" alt="{{ $sa->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($sa->name,0,1)) }}
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <p class="font-semibold text-gray-800">{{ $sa->name }}</p>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-red-100 text-red-700">ADMIN</span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $sa->email }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="text-gray-500">Belum ada admin.</div>
        @endforelse
    </div>
    @if(($superAdmins ?? collect())->isEmpty())
        <div class="mt-3"></div>
    @endif
</div>

<!-- Admin Section (Card List with pagination) -->
<div class="mb-6">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Petugas</h3>
        <a href="{{ route('admin.admins.index') }}" class="text-xs text-blue-600 hover:underline">Kelola Petugas</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse(($admins ?? []) as $admin)
        <div class="bg-white border rounded-xl p-4 shadow-sm hover:shadow transition">
            <div class="flex items-start">
                <div class="h-10 w-10 rounded-full overflow-hidden bg-blue-100 text-blue-600 flex items-center justify-center mr-3 font-semibold">
                    @if($admin->avatar)
                        <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($admin->avatar) }}" alt="{{ $admin->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($admin->name,0,1)) }}
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $admin->name }}</p>
                            <p class="text-xs text-gray-500">{{ $admin->email }}</p>
                        </div>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">ADMIN</span>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-xs">
                        <span class="px-2 py-1 rounded-full {{ $admin->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        <a href="{{ route('admin.admins.edit', $admin) }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-gray-500">Tidak ada admin.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ ($admins ?? null) ? $admins->links() : '' }}</div>
</div>

<!-- Users Section (Card List with pagination) -->
<div>
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Pengguna</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($users as $user)
        <div class="bg-white border rounded-xl p-4 shadow-sm hover:shadow transition">
            <div class="flex items-start">
                <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-100 text-gray-600 flex items-center justify-center mr-3 font-semibold">
                    @if($user->avatar)
                        <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name,0,1)) }}
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">USER</span>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 rounded-full {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            <span class="text-gray-500"><i class="far fa-clock mr-1"></i>{{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Belum pernah' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" onsubmit="return confirm('Ubah status pengguna ini?')">
                                @csrf
                                @method('PATCH')
                                <button class="{{ $user->is_active ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }}" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="mt-2 text-[11px] text-gray-400">Bergabung {{ $user->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-gray-500">Tidak ada pengguna.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</div>
@endsection
