@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}! <span class="animate-pulse">👋</span></h1>
            <p class="text-gray-600 mt-1">Ringkasan aktivitas dan statistik terbaru</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.reports.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-green-700 transition-colors flex items-center">
                <i class="fas fa-chart-line mr-2"></i>
                Laporan & Export
            </a>
            <div class="text-sm bg-white px-4 py-2 rounded-lg shadow-sm flex items-center">
                <i class="far fa-calendar-alt text-blue-500 mr-2"></i>
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </div>
    <div class="mt-6 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold">Dashboard Overview</h2>
                <p class="text-blue-100 mt-1">Pantau semua aktivitas website sekolah Anda</p>
            </div>
            <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Berita -->
    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-b-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Berita</p>
                <div class="flex items-baseline mt-2">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalBerita ?? 0 }}</h3>
                    <span class="ml-2 text-xs font-medium text-green-600 bg-green-100 py-0.5 px-1.5 rounded-full flex items-center">
                        <i class="fas fa-arrow-up mr-1"></i> 12%
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Dibandingkan bulan lalu</p>
            </div>
            <div class="p-4 rounded-full bg-blue-500 bg-opacity-10 text-blue-600">
                <i class="fas fa-newspaper text-2xl"></i>
            </div>
        </div>
                @if(Auth::guard('admin')->user()->role === 'super_admin')
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.statistics.detail', 'news') }}" class="text-xs text-blue-600 hover:text-blue-800 flex items-center">
                        Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @endif
    </div>

    <!-- Total Galeri -->
    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-b-4 border-indigo-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Galeri</p>
                <div class="flex items-baseline mt-2">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalGaleri ?? 0 }}</h3>
                    <span class="ml-2 text-xs font-medium text-green-600 bg-green-100 py-0.5 px-1.5 rounded-full flex items-center">
                        <i class="fas fa-arrow-up mr-1"></i> 5%
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Dibandingkan bulan lalu</p>
            </div>
            <div class="p-4 rounded-full bg-indigo-500 bg-opacity-10 text-indigo-600">
                <i class="fas fa-images text-2xl"></i>
            </div>
        </div>
                @if(Auth::guard('admin')->user()->role === 'super_admin')
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.statistics.detail', 'galleries') }}" class="text-xs text-indigo-600 hover:text-indigo-800 flex items-center">
                        Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @endif
    </div>

    <!-- Total Kunjungan -->
    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border-b-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pengunjung Bulan Ini</p>
                <div class="flex items-baseline mt-2">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $totalPengunjung ?? '1.2K' }}</h3>
                    <span class="ml-2 text-xs font-medium text-green-600 bg-green-100 py-0.5 px-1.5 rounded-full flex items-center">
                        <i class="fas fa-arrow-up mr-1"></i> 8%
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Dibandingkan bulan lalu</p>
            </div>
            <div class="p-4 rounded-full bg-green-500 bg-opacity-10 text-green-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
                @if(Auth::guard('admin')->user()->role === 'super_admin')
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.statistics.detail', 'visitors') }}" class="text-xs text-green-600 hover:text-green-800 flex items-center">
                        Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @endif
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Berita Terbaru -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-2 rounded-lg bg-blue-500 text-white mr-3">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Berita Terbaru</h3>
                </div>
                <a href="{{ route('admin.news.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center bg-white py-1 px-3 rounded-full shadow-sm transition-all hover:shadow">
                    Lihat Semua <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </a>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentBerita as $berita)
                <a href="{{ route('admin.news.edit', $berita->id) }}" class="block hover:bg-blue-50 transition-colors duration-300 p-4 group">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 h-20 w-20 rounded-lg bg-gray-100 overflow-hidden shadow-sm group-hover:shadow transition-all">
                            @if($berita->image)
                                <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($berita->image) }}" alt="{{ $berita->title }}" class="h-full w-full object-cover">
                            @else
                                <div class="h-full w-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-400">
                                    <i class="fas fa-newspaper text-xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex justify-between">
                                <h4 class="text-sm font-medium text-gray-900 line-clamp-1 group-hover:text-blue-700 transition-colors">{{ $berita->title }}</h4>
                                <span class="text-xs px-2 py-1 rounded-full {{ $berita->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $berita->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ Str::limit(strip_tags($berita->content), 100) }}</p>
                            <div class="mt-2 flex items-center text-xs text-gray-400">
                                <span class="flex items-center"><i class="far fa-clock mr-1"></i> {{ $berita->created_at->diffForHumans() }}</span>
                                <span class="mx-2">•</span>
                                <span class="flex items-center"><i class="far fa-folder mr-1"></i> {{ $berita->newsCategory->name ?? 'Tanpa Kategori' }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center">
                    <div class="bg-blue-50 rounded-lg p-4 inline-block mb-3">
                        <i class="fas fa-newspaper text-blue-400 text-3xl"></i>
                    </div>
                    <p class="text-gray-500">Belum ada berita yang ditambahkan</p>
                    <a href="{{ route('admin.news.create') }}" class="mt-3 inline-block text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Berita Baru
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-2 rounded-lg bg-purple-500 text-white mr-3">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                </div>
                <div class="text-xs bg-purple-100 text-purple-800 px-3 py-1 rounded-full">
                    Hari Ini
                </div>
            </div>
        </div>
        <div class="divide-y divide-gray-100 max-h-[400px] overflow-y-auto">
            @forelse($recentActivities as $activity)
                <div class="p-4 hover:bg-purple-50 transition-colors duration-300">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-{{ $activity['color'] }}-100 text-{{ $activity['color'] }}-600 flex items-center justify-center shadow-sm">
                                <i class="fas fa-{{ $activity['icon'] }}"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex justify-between">
                                <div class="text-sm font-medium text-gray-900">{{ $activity['description'] }}</div>
                                <div class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                    {{ $activity['time'] }}
                                </div>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $activity['color'] }}-100 text-{{ $activity['color'] }}-800">
                                    <i class="fas fa-{{ $activity['icon'] }} mr-1"></i>
                                    {{ ucfirst($activity['type'] ?? 'activity') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="bg-purple-50 rounded-lg p-4 inline-block mb-3">
                        <i class="fas fa-history text-purple-400 text-3xl"></i>
                    </div>
                    <p class="text-gray-500">Belum ada aktivitas tercatat</p>
                    <p class="mt-2 text-xs text-gray-400">Aktivitas akan muncul saat Anda melakukan perubahan</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@if(Auth::guard('admin')->user()->role === 'super_admin')
<!-- Users Monitoring (Super Admin Only) -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-user-friends text-blue-500 mr-2"></i> Monitoring Pengguna
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow p-5 border-b-4 border-blue-500">
            <div class="text-sm text-gray-500">Total Pengguna</div>
            <div class="mt-2 text-3xl font-bold text-gray-800">{{ $userStats['total_users'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border-b-4 border-green-500">
            <div class="text-sm text-gray-500">Aktif</div>
            <div class="mt-2 text-3xl font-bold text-gray-800">{{ $userStats['active_users'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-xl shadow p-5 border-b-4 border-gray-400">
            <div class="text-sm text-gray-500">Nonaktif</div>
            <div class="mt-2 text-3xl font-bold text-gray-800">{{ $userStats['inactive_users'] ?? 0 }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center">
                <div class="p-2 rounded-lg bg-blue-500 text-white mr-3">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h4 class="text-md font-semibold text-gray-800">Login Terakhir Pengguna</h4>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Kelola Pengguna</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse(($recentUserLogins ?? []) as $u)
                <div class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <div class="font-medium text-gray-800">{{ $u->name }}</div>
                        <div class="text-xs text-gray-500">{{ $u->email }}</div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs px-2 py-1 rounded-full {{ $u->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        <div class="text-xs text-gray-500 flex items-center"><i class="far fa-clock mr-1"></i> {{ $u->last_login_at ? \Carbon\Carbon::parse($u->last_login_at)->diffForHumans() : 'Belum pernah' }}</div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">Belum ada data login pengguna.</div>
            @endforelse
        </div>
    </div>
</div>
@endif

@if(Auth::guard('admin')->user()->role === 'super_admin')
<!-- Tambahan Statistik -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-chart-pie text-blue-500 mr-2"></i> Statistik Website
    </h3>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Galeri Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 border-b-4 border-green-500">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Galeri</h3>
                        <div class="mt-2 flex items-baseline">
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_galleries'] }}</p>
                            <span class="ml-2 text-xs font-medium text-green-600 bg-green-100 py-0.5 px-1.5 rounded-full flex items-center">
                                {{ $stats['published_galleries'] }} dipublikasi
                            </span>
                        </div>
                    </div>
                    <div class="p-4 rounded-full bg-green-500 bg-opacity-10 text-green-600">
                        <i class="fas fa-images text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.galleries.index') }}" class="text-xs text-green-600 hover:text-green-800 flex items-center">
                        Kelola Galeri <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        @if(Auth::guard('admin')->user()->role === 'super_admin')
        <!-- Profil Sekolah Card (Super Admin Only) -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 border-b-4 border-purple-500">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Profil Sekolah</h3>
                        <div class="mt-2">
                            <p class="text-xl font-bold text-gray-900 truncate max-w-[180px]">{{ $schoolProfile->school_name ?? 'Belum diatur' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $schoolProfile->address ?? 'Alamat belum diatur' }}</p>
                        </div>
                    </div>
                    <div class="p-4 rounded-full bg-purple-500 bg-opacity-10 text-purple-600">
                        <i class="fas fa-school text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.school-profile.edit') }}" class="text-xs text-purple-600 hover:text-purple-800 flex items-center">
                        Edit Profil <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if(Auth::guard('admin')->user()->role === 'super_admin')
        <!-- Admin Card (Super Admin Only) -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 border-b-4 border-yellow-500">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Admin</h3>
                        <div class="mt-2 flex items-baseline">
                            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_admins'] ?? 0 }}</p>
                            <span class="ml-2 text-xs font-medium text-yellow-600 bg-yellow-100 py-0.5 px-1.5 rounded-full flex items-center">
                                {{ $stats['active_admins'] ?? 0 }} aktif
                            </span>
                        </div>
                    </div>
                    <div class="p-4 rounded-full bg-yellow-500 bg-opacity-10 text-yellow-600">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-gray-100">
                    <a href="{{ route('admin.statistics.detail', 'admins') }}" class="text-xs text-yellow-600 hover:text-yellow-800 flex items-center">
                        Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif

@if(Auth::guard('admin')->user()->role === 'super_admin')
<!-- Super Admin Section -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
        <i class="fas fa-crown text-red-500 mr-2"></i> Super Admin Panel
    </h3>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Petugas Management Card -->
        <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-xl shadow-md overflow-hidden border border-red-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-red-800">Manajemen Petugas</h3>
                        <p class="text-sm text-red-600 mt-1">Kelola akun petugas dan izin akses</p>
                        <div class="mt-3 flex items-center space-x-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-700">{{ $stats['total_admins'] ?? 0 }}</div>
                                <div class="text-xs text-red-600">Total Petugas</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-700">{{ $stats['active_admins'] ?? 0 }}</div>
                                <div class="text-xs text-green-600">Aktif</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 rounded-full bg-red-500 bg-opacity-20 text-red-600">
                        <i class="fas fa-users-cog text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-red-200">
                    <a href="{{ route('admin.admins.index') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-cog mr-2"></i> Kelola Petugas
                    </a>
                </div>
            </div>
        </div>

        <!-- System Statistics Card -->
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl shadow-md overflow-hidden border border-blue-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-blue-800">Statistik Sistem</h3>
                        <p class="text-sm text-blue-600 mt-1">Pantau performa website secara menyeluruh</p>
                        <div class="mt-3 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-blue-700">Total Kunjungan:</span>
                                <span class="font-semibold text-blue-800">{{ $totalPengunjung ?? '1.2K' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-blue-700">Berita Dipublikasi:</span>
                                <span class="font-semibold text-blue-800">{{ $totalBerita ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-blue-700">Galeri Aktif:</span>
                                <span class="font-semibold text-blue-800">{{ $totalGaleri ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 rounded-full bg-blue-500 bg-opacity-20 text-blue-600">
                        <i class="fas fa-chart-bar text-2xl"></i>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-blue-200">
                    <a href="{{ route('admin.statistics.detail', 'visitors') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-chart-line mr-2"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
