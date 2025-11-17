@extends('admin.layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container-fluid animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <span class="bg-gradient-to-r from-blue-500 to-indigo-600 p-2 rounded-lg text-white mr-3 shadow-lg">
                        <i class="fas fa-user-cog"></i>
                    </span>
                    Edit Pengguna
                </h1>
                <p class="text-gray-600 mt-2 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Kelola status akun pengguna dan lihat informasi profil
                </p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg flex items-center transition-all duration-300 shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Monitoring
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg border border-red-200">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left: Profile & Form -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-id-card text-blue-500 mr-2"></i>
                    Informasi Pengguna
                </h3>
            </div>

            <div class="p-6 space-y-8">
                <!-- Profile block -->
                <div class="flex items-start">
                    <div class="h-14 w-14 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center mr-4 text-xl font-semibold">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center flex-wrap gap-2">
                            <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-700">USER</span>
                            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $user->email }}</p>
                        <div class="mt-2 text-xs text-gray-500 flex items-center gap-4">
                            <span class="flex items-center"><i class="far fa-calendar-plus mr-1"></i> Bergabung {{ $user->created_at->format('d M Y') }}</span>
                            <span class="flex items-center"><i class="far fa-clock mr-1"></i> Terakhir login: {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Belum pernah' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Update form: active only -->
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                        <h4 class="text-base font-medium text-blue-800 mb-3 flex items-center">
                            <i class="fas fa-user-shield text-blue-500 mr-2"></i> Status Akun
                        </h4>
                        <label class="inline-flex items-center select-none cursor-pointer">
                            <input class="rounded text-blue-600 focus:ring-blue-500 w-5 h-5" type="checkbox" id="is_active" name="is_active" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">Akun Aktif</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-2"><i class="fas fa-info-circle mr-1"></i> Pengguna yang nonaktif tidak dapat login ke sistem.</p>
                    </div>

                    <div class="flex items-center justify-end pt-2">
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-all mr-3">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg shadow transition-all">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Quick actions -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden h-fit">
            <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-bolt text-indigo-500 mr-2"></i>
                    Aksi Cepat
                </h3>
            </div>
            <div class="p-6 space-y-3">
                <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" onsubmit="return confirm('Ubah status aktif akun ini?');">
                    @csrf
                    @method('PATCH')
                    <button class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg {{ $user->is_active ? 'bg-gray-500 hover:bg-gray-600' : 'bg-green-600 hover:bg-green-700' }} text-white transition-all shadow-sm">
                        <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }} mr-2"></i> {{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
.animate-fade-in { animation: fadeIn 0.4s ease-out; }
</style>

@endsection
