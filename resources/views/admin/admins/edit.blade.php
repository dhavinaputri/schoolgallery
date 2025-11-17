@extends('admin.layouts.app')

@section('title', 'Edit Admin')

@section('content')
<div class="container-fluid animate-fade-in">
    <!-- Header dengan animasi dan gradien -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <span class="bg-gradient-to-r from-blue-600 to-indigo-600 p-2 rounded-lg text-white mr-3 shadow-lg">
                        <i class="fas fa-user-shield"></i>
                    </span>
                    Edit Admin
                </h1>
                <p class="text-gray-600 mt-2 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                    Kelola informasi dan status akun Admin
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.dashboard') }}" class="bg-white border hover:bg-gray-50 text-gray-700 px-5 py-2 rounded-lg flex items-center transition-all duration-300 shadow-sm">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
                <a href="{{ route('admin.admins.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-lg flex items-center transition-all duration-300 shadow-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Form Card dengan desain yang lebih menarik -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-user-edit text-blue-500 mr-2"></i>
                    Edit Informasi Admin
                </h3>
            </div>
            
            <!-- Form Body -->
            <div class="p-6">
                <form action="{{ route('admin.admins.update', $admin) }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

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
                                    <input id="name" name="name" type="text" required value="{{ old('name', $admin->name) }}" 
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
                                    <input id="email" name="email" type="email" required value="{{ old('email', $admin->email) }}" 
                                        class="pl-10 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all" 
                                        placeholder="contoh@email.com"/>
                                </div>
                                @error('email')<p class="text-sm text-red-600 mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Role & Status Section -->
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                        <h4 class="text-lg font-medium text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-user-shield text-blue-500 mr-2"></i> Role & Status
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user-tag text-gray-400"></i>
                                    </div>
                                    <select id="role" name="role" required 
                                        class="pl-10 block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all">
                                        <option value="" disabled>Pilih Role</option>
                                        <option value="super_admin" {{ old('role', $admin->role) === 'super_admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="admin" {{ old('role', $admin->role) === 'admin' ? 'selected' : '' }}>Petugas</option>
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
                                        <input class="rounded text-blue-600 focus:ring-blue-500 w-5 h-5" type="checkbox" id="is_active" name="is_active" {{ old('is_active', $admin->is_active) ? 'checked' : '' }}>
                                        <span class="ml-2 text-gray-700">Akun Aktif</span>
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
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg shadow transition-all">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Side Card dengan desain yang lebih menarik -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden h-fit">
            <!-- Card Header -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Detail Akun
                </h3>
            </div>
            
            <!-- Card Body -->
            <div class="p-6 space-y-6">
                <!-- Admin Info -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-full p-1.5 mr-3">
                                <i class="fas fa-hashtag text-blue-500"></i>
                            </div>
                            <span class="text-gray-700">ID: <span class="font-semibold text-gray-900">{{ $admin->id }}</span></span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-full p-1.5 mr-3">
                                <i class="fas fa-calendar-plus text-blue-500"></i>
                            </div>
                            <span class="text-gray-700">Dibuat: <span class="font-semibold text-gray-900">{{ $admin->created_at->format('d/m/Y H:i') }}</span></span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-full p-1.5 mr-3">
                                <i class="fas fa-clock text-blue-500"></i>
                            </div>
                            <span class="text-gray-700">Update Terakhir: <span class="font-semibold text-gray-900">{{ $admin->updated_at->diffForHumans() }}</span></span>
                        </div>
                    </div>
                </div>

                <!-- Password Reset Section -->
                @if(session('new_password'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 animate-pulse">
                    <h4 class="font-semibold text-green-800 mb-2 flex items-center">
                        <i class="fas fa-key text-green-600 mr-2"></i> Password Baru:
                    </h4>
                    <div class="flex items-center bg-white p-3 rounded-lg border border-green-200 shadow-inner">
                        <code id="newPassword" class="font-mono text-lg font-bold text-gray-800 break-all">{{ session('new_password') }}</code>
                    </div>
                    <div class="mt-3 flex justify-center">
                        <button onclick="copyPassword()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition-all flex items-center shadow-sm">
                            <i class="fas fa-copy mr-2"></i> Salin Password
                        </button>
                    </div>
                    <p class="text-xs text-green-700 mt-3 text-center"><i class="fas fa-exclamation-triangle mr-1"></i> Simpan password ini dengan aman. Password tidak akan tampil lagi setelah refresh.</p>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <!-- Toggle Active Button -->
                    <form action="{{ route('admin.admins.toggle-active', $admin) }}" method="POST" onsubmit="return confirm('Ubah status aktif akun ini?');">
                        @csrf
                        @method('PATCH')
                        <button class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg {{ $admin->is_active ? 'bg-blue-500 hover:bg-blue-600' : 'bg-blue-600 hover:bg-blue-700' }} text-white transition-all shadow-sm">
                            <i class="fas {{ $admin->is_active ? 'fa-user-slash' : 'fa-user-check' }} mr-2"></i> {{ $admin->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
                        </button>
                    </form>

                    <!-- Reset Password Button -->
                    <form action="{{ route('admin.admins.reset-password', $admin) }}" method="POST" onsubmit="return confirm('Reset password akun ini? Password baru akan digenerate secara otomatis.');">
                        @csrf
                        <button class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-white border border-blue-600 text-blue-700 hover:bg-blue-50 transition-all shadow-sm">
                            <i class="fas fa-key mr-2"></i> Reset Password
                        </button>
                    </form>

                    <!-- Delete Button (if not current user) -->
                    @if($admin->id !== auth('admin')->id())
                    <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus admin ini? Tindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-red-500 hover:bg-red-600 text-white transition-all shadow-sm">
                            <i class="fas fa-trash mr-2"></i> Hapus Admin
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyPassword(){
    const el = document.getElementById('newPassword');
    if(!el) return;
    const text = el.textContent;
    navigator.clipboard.writeText(text)
        .then(() => {
            // Show success message
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-2"></i> Password Disalin!';
            button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
            button.classList.add('bg-green-500');
            
            // Reset button after 2 seconds
            setTimeout(function() {
                button.innerHTML = originalText;
                button.classList.remove('bg-green-500');
                button.classList.add('bg-blue-500', 'hover:bg-blue-600');
            }, 2000);
        })
        .catch(() => {
            alert('Gagal menyalin password. Silakan copy manual: ' + text);
        });
}
</script>

<style>
/* Animasi untuk elemen-elemen UI */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
@endsection
