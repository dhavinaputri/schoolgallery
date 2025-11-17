@extends('admin.layouts.app')

@section('title', 'Manajemen Petugas')

@section('content')
<!-- Header lebih sederhana dan tombol kembali -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="animate-fade-in">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <span class="bg-blue-600 p-2 rounded-lg text-white mr-3">
                    <i class="fas fa-users-cog"></i>
                </span>
                Manajemen Petugas
            </h1>
            <p class="text-gray-600 mt-2 flex items-center">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                Kelola akun petugas dan izin akses sistem
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.admins.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center transition">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Petugas
            </a>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="bg-white p-4 rounded-xl shadow-sm mb-6 border border-gray-100">
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-64">
            <input type="text" id="searchAdmin" placeholder="Cari petugas..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <select id="filterRole" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                <option value="">Semua Role</option>
                <option value="super_admin">Admin</option>
                <option value="admin">Petugas</option>
            </select>
            <select id="filterStatus" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
            </select>
        </div>
    </div>
</div>

<!-- Alert Messages dengan animasi -->
@if(session('success'))
    @if(!is_array(session('success')))
        <!-- Regular Success -->
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm flex items-center animate-fade-in-down">
            <div class="bg-green-200 rounded-full p-2 mr-3">
                <i class="fas fa-check text-green-600"></i>
            </div>
            <div>
                <h4 class="font-medium">Berhasil!</h4>
                <p>{{ session('success') }}</p>
            </div>
            <button type="button" class="ml-auto text-green-500 hover:text-green-700" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
@endif

@if(session('error'))
    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm flex items-center animate-fade-in-down">
        <div class="bg-red-200 rounded-full p-2 mr-3">
            <i class="fas fa-exclamation text-red-600"></i>
        </div>
        <div>
            <h4 class="font-medium">Error!</h4>
            <p>{{ session('error') }}</p>
        </div>
        <button type="button" class="ml-auto text-red-500 hover:text-red-700" onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif

<!-- Admin Cards Grid dengan animasi hover -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="adminCards">
    @forelse($admins as $admin)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition admin-card border border-gray-200 hover:border-blue-200" 
         data-role="{{ $admin->role }}" 
         data-status="{{ $admin->is_active ? 'active' : 'inactive' }}">
        <!-- Card Header minimalis -->
        <div class="p-5 text-gray-800 border-l-4 border-blue-600 bg-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mr-4 border border-gray-200">
                        <i class="fas {{ $admin->role === 'super_admin' ? 'fa-crown' : 'fa-user-shield' }} text-gray-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl">{{ $admin->name }}</h3>
                        <p class="text-sm opacity-90 flex items-center">
                            <i class="fas fa-envelope mr-1"></i> {{ $admin->email }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $admin->is_active ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $admin->is_active ? 'bg-blue-600' : 'bg-gray-500' }} mr-2"></span>{{ $admin->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Card Body dengan layout rapi -->
        <div class="p-6">
            <!-- Role Badge sederhana -->
            <div class="mb-5">
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                    <i class="fas {{ $admin->role === 'super_admin' ? 'fa-crown' : 'fa-user-shield' }} mr-2"></i>
                    {{ $admin->role === 'super_admin' ? 'Admin' : 'Petugas' }}
                </span>
            </div>

            <!-- Admin Info dengan ikon yang lebih menarik -->
            <div class="space-y-3 mb-5">
                <div class="flex items-center text-sm text-gray-600">
                    <div class="bg-gray-100 rounded-full p-1.5 mr-3">
                        <i class="fas fa-id-badge text-gray-500"></i>
                    </div>
                    <span>ID: <span class="font-medium text-gray-800">{{ $admin->id }}</span></span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <div class="bg-gray-100 rounded-full p-1.5 mr-3">
                        <i class="fas fa-calendar-plus text-gray-500"></i>
                    </div>
                    <span>Dibuat: <span class="font-medium text-gray-800">{{ $admin->created_at->format('d/m/Y H:i') }}</span></span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <div class="bg-gray-100 rounded-full p-1.5 mr-3">
                        <i class="fas fa-clock text-gray-500"></i>
                    </div>
                    <span>Update: <span class="font-medium text-gray-800">{{ $admin->updated_at->diffForHumans() }}</span></span>
                </div>
            </div>

            <!-- Action Buttons sederhana -->
            <div class="flex flex-wrap gap-2">
                <!-- Edit Button -->
                <a href="{{ route('admin.admins.edit', $admin) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2.5 rounded-lg text-sm font-medium transition flex items-center justify-center">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>

                <!-- Reset Password Link -->
                <a href="{{ route('admin.admins.edit', $admin) }}#reset" class="flex-1 bg-white text-blue-600 border border-blue-600 hover:bg-blue-50 px-3 py-2.5 rounded-lg text-sm font-medium transition flex items-center justify-center">
                    <i class="fas fa-key mr-2"></i> Reset
                </a>

                <!-- Toggle Active Button -->
                @if($admin->id !== auth('admin')->id())
                <form action="{{ route('admin.admins.toggle-active', $admin) }}" method="POST" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full {{ $admin->is_active ? 'bg-gray-500 hover:bg-gray-600' : 'bg-blue-600 hover:bg-blue-700' }} text-white px-3 py-2.5 rounded-lg text-sm font-medium transition flex items-center justify-center" onclick="return confirm('Ubah status aktif admin ini?')">
                        <i class="fas {{ $admin->is_active ? 'fa-user-slash' : 'fa-user-check' }} mr-2"></i> {{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
                @endif

                <!-- Delete Button (if not current user) -->
                @if($admin->id !== auth('admin')->id())
                <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-white text-red-600 border border-red-500 hover:bg-red-50 px-3 py-2.5 rounded-lg text-sm font-medium transition flex items-center justify-center" onclick="return confirm('Hapus admin ini? Tindakan ini tidak dapat dibatalkan.')">
                        <i class="fas fa-trash mr-2"></i> Hapus
                    </button>
                </form>
                @else
                <div class="flex-1 bg-gray-200 text-gray-500 px-3 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center cursor-not-allowed">
                    <i class="fas fa-lock mr-2"></i> Diri Sendiri
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <!-- Empty State dengan ilustrasi yang lebih menarik -->
    <div class="col-span-full">
        <div class="bg-white rounded-xl shadow-md p-12 text-center border border-dashed border-gray-300">
            <div class="bg-blue-50 rounded-full p-8 inline-block mb-6 animate-pulse">
                <i class="fas fa-users text-5xl text-blue-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mb-3">Belum Ada Petugas</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">Mulai dengan menambahkan petugas pertama untuk mengelola sistem dan mengatur hak akses</p>
            <a href="{{ route('admin.admins.create') }}" class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-8 py-4 rounded-lg inline-flex items-center transition-all duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Petugas Pertama
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Ringkasan Petugas (layout seragam & netral) -->
@if($admins->count() > 0)
<div class="mt-10 bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
            <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
            Ringkasan Petugas
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <div class="bg-white rounded-xl p-6 text-center border border-gray-200">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 mb-3">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
                <div class="text-2xl font-semibold text-gray-800 mb-1">{{ $admins->where('is_active', true)->count() }}</div>
                <div class="text-sm text-gray-600">Petugas Aktif</div>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center border border-gray-200">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 mb-3">
                    <i class="fas fa-crown text-xl"></i>
                </div>
                <div class="text-2xl font-semibold text-gray-800 mb-1">{{ $admins->where('role', 'super_admin')->count() }}</div>
                <div class="text-sm text-gray-600">Admin</div>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center border border-gray-200">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 mb-3">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
                <div class="text-2xl font-semibold text-gray-800 mb-1">{{ $admins->where('role', 'admin')->count() }}</div>
                <div class="text-sm text-gray-600">Petugas</div>
            </div>
            
            <div class="bg-white rounded-xl p-6 text-center border border-gray-200">
                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-400 mb-3">
                    <i class="fas fa-user-slash text-xl"></i>
                </div>
                <div class="text-2xl font-semibold text-gray-800 mb-1">{{ $admins->where('is_active', false)->count() }}</div>
                <div class="text-sm text-gray-600">Petugas Nonaktif</div>
            </div>
        </div>
        <!-- Charts: Distribusi Role & Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Distribusi Role</h4>
                <canvas id="adminRoleChart" class="w-full h-56"></canvas>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Status Aktif</h4>
                <canvas id="adminStatusChart" class="w-full h-56"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

<!-- JavaScript untuk filter, animasi, dan grafik -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts after 10 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
        alerts.forEach(function(alert) {
            if (!alert.querySelector('#newPassword')) {
                alert.style.opacity = '0';
                setTimeout(() => alert.style.display = 'none', 300);
            }
        });
    }, 10000);

    // Charts (Role & Status)
    const roleCtx = document.getElementById('adminRoleChart')?.getContext('2d');
    const statusCtx = document.getElementById('adminStatusChart')?.getContext('2d');

    if (roleCtx) {
        new Chart(roleCtx, {
            type: 'pie',
            data: {
                labels: ['Admin', 'Petugas'],
                datasets: [{
                    data: [{{ $admins->where('role', 'super_admin')->count() }}, {{ $admins->where('role', 'admin')->count() }}],
                    backgroundColor: ['#3b82f6', '#93c5fd'],
                    borderColor: ['#3b82f6', '#93c5fd']
                }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
    }

    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Nonaktif'],
                datasets: [{
                    data: [{{ $admins->where('is_active', true)->count() }}, {{ $admins->where('is_active', false)->count() }}],
                    backgroundColor: ['#2563eb', '#93c5fd'],
                    borderColor: ['#2563eb', '#93c5fd']
                }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
    }

    // Filter functionality
    const searchInput = document.getElementById('searchAdmin');
    const filterRole = document.getElementById('filterRole');
    const filterStatus = document.getElementById('filterStatus');
    const adminCards = document.querySelectorAll('.admin-card');

    function filterCards() {
        const searchTerm = searchInput.value.toLowerCase();
        const roleFilter = filterRole.value;
        const statusFilter = filterStatus.value;

        adminCards.forEach(card => {
            const cardText = card.textContent.toLowerCase();
            const cardRole = card.dataset.role;
            const cardStatus = card.dataset.status;
            
            const matchesSearch = searchTerm === '' || cardText.includes(searchTerm);
            const matchesRole = roleFilter === '' || cardRole === roleFilter;
            const matchesStatus = statusFilter === '' || cardStatus === statusFilter;
            
            if (matchesSearch && matchesRole && matchesStatus) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterCards);
    filterRole.addEventListener('change', filterCards);
    filterStatus.addEventListener('change', filterCards);
});

// Function untuk copy password
function copyPassword() {
    const passwordElement = document.getElementById('newPassword');
    const password = passwordElement.textContent;
    
    navigator.clipboard.writeText(password).then(function() {
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-1"></i> Copied!';
        button.classList.remove('bg-blue-500', 'hover:bg-blue-600');
        button.classList.add('bg-green-500');
        
        setTimeout(function() {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-500');
            button.classList.add('bg-blue-500', 'hover:bg-blue-600');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert('Gagal menyalin password. Silakan copy manual: ' + password);
    });
}
</script>

<style>
/* Animasi untuk elemen-elemen UI */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in-down {
    animation: fadeInDown 0.5s ease-out;
}

.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}
</style>
@endsection