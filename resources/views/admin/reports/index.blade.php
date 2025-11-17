@extends('admin.layouts.app')

@section('title', 'Laporan & Export')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan & Export</h1>
                <p class="text-gray-600 mt-1">Generate dan export laporan statistik website</p>
            </div>
            <div class="flex items-center space-x-2">
                <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pengunjung</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_visitors']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-newspaper text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Berita</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_news']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-images text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Galeri</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_galleries']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Types -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Visitor Statistics Report -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 rounded-lg bg-blue-100 text-blue-600 mr-3">
                    <i class="fas fa-chart-line text-lg"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Laporan Statistik Kunjungan</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">Export data kunjungan website dengan analisis harian, mingguan, dan bulanan</p>
            
            <form action="{{ route('admin.reports.export-visitor-stats') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Mulai</label>
                    <input type="date" name="start_date" value="{{ now()->subMonth()->format('Y-m-d') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Akhir</label>
                    <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Format Export</label>
                    <select name="format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel (CSV)</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-download mr-2"></i> Export Laporan
                </button>
            </form>
        </div>

        <!-- Content Statistics Report -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 rounded-lg bg-green-100 text-green-600 mr-3">
                    <i class="fas fa-file-alt text-lg"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Laporan Statistik Konten</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">Export data berita dan galeri dengan analisis kategori dan status publikasi</p>
            
            <form action="{{ route('admin.reports.export-content-stats') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Mulai</label>
                    <input type="date" name="start_date" value="{{ now()->subMonth()->format('Y-m-d') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Akhir</label>
                    <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Format Export</label>
                    <select name="format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel (CSV)</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors">
                    <i class="fas fa-download mr-2"></i> Export Laporan
                </button>
            </form>
        </div>

        <!-- Admin Activity Report -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 rounded-lg bg-purple-100 text-purple-600 mr-3">
                    <i class="fas fa-user-cog text-lg"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Laporan Aktivitas Admin</h3>
            </div>
            <p class="text-gray-600 text-sm mb-4">Export log aktivitas admin dengan analisis performa dan audit trail</p>
            
            <form action="{{ route('admin.reports.export-admin-activity') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Mulai</label>
                    <input type="date" name="start_date" value="{{ now()->subMonth()->format('Y-m-d') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Akhir</label>
                    <input type="date" name="end_date" value="{{ now()->format('Y-m-d') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Format Export</label>
                    <select name="format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel (CSV)</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors">
                    <i class="fas fa-download mr-2"></i> Export Laporan
                </button>
            </form>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.reports.export-visitor-stats.quick') }}?start_date={{ now()->subMonth()->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}&format=pdf" 
               class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                <div>
                    <p class="font-medium text-gray-900">Kunjungan Bulan Ini</p>
                    <p class="text-sm text-gray-600">PDF</p>
                </div>
            </a>

            <a href="{{ route('admin.reports.export-content-stats.quick') }}?start_date={{ now()->subMonth()->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}&format=excel" 
               class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                <i class="fas fa-file-excel text-green-600 mr-3"></i>
                <div>
                    <p class="font-medium text-gray-900">Konten Bulan Ini</p>
                    <p class="text-sm text-gray-600">Excel</p>
                </div>
            </a>

            <a href="{{ route('admin.reports.export-admin-activity.quick') }}?start_date={{ now()->subWeek()->format('Y-m-d') }}&end_date={{ now()->format('Y-m-d') }}&format=pdf" 
               class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                <i class="fas fa-user-cog text-purple-600 mr-3"></i>
                <div>
                    <p class="font-medium text-gray-900">Aktivitas Minggu Ini</p>
                    <p class="text-sm text-gray-600">PDF</p>
                </div>
            </a>

            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-arrow-left text-gray-600 mr-3"></i>
                <div>
                    <p class="font-medium text-gray-900">Kembali ke Dashboard</p>
                    <p class="text-sm text-gray-600">Menu utama</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
