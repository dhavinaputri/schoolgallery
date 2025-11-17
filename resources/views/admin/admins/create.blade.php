@extends('admin.layouts.app')

@section('title', 'Tambah Admin')

@section('content')
<div class="container-fluid animate-fade-in">
    <!-- Header dengan animasi dan gradien -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <span class="bg-gradient-to-r from-blue-500 to-indigo-600 p-2 rounded-lg text-white mr-3 shadow-lg">
                        <i class="fas fa-user-plus"></i>
                    </span>
                    Tambah Admin
                </h1>
                <p class="text-gray-600 mt-2 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Buat akun Admin baru untuk mengelola sistem
                </p>
            </div>
            <a href="{{ route('admin.admins.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg flex items-center transition-all duration-300 shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    <!-- Form Card dengan desain yang lebih menarik -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800 flex items-center">
                <i class="fas fa-user-shield text-blue-500 mr-2"></i>
                Informasi Admin Baru
            </h3>
        </div>
        
        <!-- Form Body -->
        <div class="p-6">
            <form action="{{ route('admin.admins.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Personal Information Section -->
                <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                    <h4 class="text-lg font-medium text-blue-800 mb-4 flex items-center">
                        <i class="fas fa-id-card text-blue-500 mr-2"></i> Informasi Personal
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input id="name" name="name" type="text" required value="{{ old('name') }}" 
                                    class="pl-10 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all" 
                                    placeholder="Masukkan nama lengkap"/>
                            </div>
                            @error('name')<p class="text-sm text-red-600 mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input id="email" name="email" type="email" required value="{{ old('email') }}" 
                                    class="pl-10 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all" 
                                    placeholder="contoh@email.com"/>
                            </div>
                            @error('email')<p class="text-sm text-red-600 mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="bg-purple-50 rounded-xl p-6 border border-purple-100">
                    <h4 class="text-lg font-medium text-purple-800 mb-4 flex items-center">
                        <i class="fas fa-lock text-purple-500 mr-2"></i> Keamanan
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password" name="password" type="password" required 
                                    class="pl-10 block w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all" 
                                    placeholder="Minimal 8 karakter"/>
                            </div>
                            @error('password')<p class="text-sm text-red-600 mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                            <p class="text-xs text-gray-500 mt-1"><i class="fas fa-info-circle mr-1"></i> Gunakan kombinasi huruf, angka, dan simbol untuk keamanan yang lebih baik</p>
                        </div>
                        
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password_confirmation" name="password_confirmation" type="password" required 
                                    class="pl-10 block w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 transition-all" 
                                    placeholder="Masukkan password yang sama"/>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role & Status Section -->
                <div class="bg-green-50 rounded-xl p-6 border border-green-100">
                    <h4 class="text-lg font-medium text-green-800 mb-4 flex items-center">
                        <i class="fas fa-user-shield text-green-500 mr-2"></i> Role & Status
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tag text-gray-400"></i>
                                </div>
                                <select id="role" name="role" required 
                                    class="pl-10 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 transition-all">
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Petugas</option>
                                </select>
                            </div>
                            @error('role')<p class="text-sm text-red-600 mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                            
                            <div class="mt-3 text-xs text-gray-500">
                                <p class="mb-1"><i class="fas fa-crown text-amber-500 mr-1"></i> <strong>Admin:</strong> Akses penuh ke semua fitur</p>
                                <p><i class="fas fa-user-shield text-blue-500 mr-1"></i> <strong>Petugas:</strong> Akses terbatas sesuai izin</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col justify-end">
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                                <label class="inline-flex items-center select-none cursor-pointer">
                                    <input class="rounded text-green-600 focus:ring-green-500 w-5 h-5" type="checkbox" id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <span class="ml-2 text-gray-700">Aktifkan akun ini</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2"><i class="fas fa-info-circle mr-1"></i> Admin yang tidak aktif tidak dapat login ke sistem</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-all mr-4">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-lg shadow transition-all">
                        <i class="fas fa-save mr-2"></i> Simpan Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Animasi untuk elemen-elemen UI */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}
</style>
@endsection
