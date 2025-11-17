@extends('admin.layouts.app')

@section('title', 'Detail Statistik - ' . ucfirst($type))

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-chart-bar text-blue-500 mr-3"></i>
                    Detail Statistik {{ ucfirst($type) }}
                </h1>
                <p class="text-gray-600 mt-1">Analisis mendalam data {{ $type }} sistem</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @if($type === 'news')
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Berita</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['total'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-blue-500 bg-opacity-10 text-blue-600">
                        <i class="fas fa-newspaper text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Dipublikasi</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['published'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-green-500 bg-opacity-10 text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Draft</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['draft'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-yellow-500 bg-opacity-10 text-yellow-600">
                        <i class="fas fa-edit text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Bulan Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['this_month'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-purple-500 bg-opacity-10 text-purple-600">
                        <i class="fas fa-calendar text-2xl"></i>
                    </div>
                </div>
            </div>

        @elseif($type === 'galleries')
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Galeri</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['total'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-indigo-500 bg-opacity-10 text-indigo-600">
                        <i class="fas fa-images text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Dipublikasi</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['published'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-green-500 bg-opacity-10 text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Draft</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['draft'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-yellow-500 bg-opacity-10 text-yellow-600">
                        <i class="fas fa-edit text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Bulan Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['this_month'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-purple-500 bg-opacity-10 text-purple-600">
                        <i class="fas fa-calendar text-2xl"></i>
                    </div>
                </div>
            </div>

        @elseif($type === 'admins')
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Admin</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['total'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-red-500 bg-opacity-10 text-red-600">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Aktif</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['active'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-green-500 bg-opacity-10 text-green-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Super Admin</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['super_admin'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-yellow-500 bg-opacity-10 text-yellow-600">
                        <i class="fas fa-crown text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Admin Biasa</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['regular_admin'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-blue-500 bg-opacity-10 text-blue-600">
                        <i class="fas fa-user text-2xl"></i>
                    </div>
                </div>
            </div>

        @elseif($type === 'visitors')
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Bulan Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['this_month'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-green-500 bg-opacity-10 text-green-600">
                        <i class="fas fa-calendar text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Bulan Lalu</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['last_month'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-blue-500 bg-opacity-10 text-blue-600">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Minggu Ini</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['this_week'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-purple-500 bg-opacity-10 text-purple-600">
                        <i class="fas fa-calendar-week text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Minggu Lalu</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $data['last_week'] }}</p>
                    </div>
                    <div class="p-4 rounded-full bg-indigo-500 bg-opacity-10 text-indigo-600">
                        <i class="fas fa-calendar-day text-2xl"></i>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Charts and Additional Data -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart Section -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-pie text-blue-500 mr-2"></i>
                Grafik {{ ucfirst($type) }}
            </h3>
            @if($type === 'news' && isset($data['chart']))
                <canvas id="newsChart" class="w-full h-64"></canvas>
            @elseif($type === 'visitors')
                <canvas id="visitorsSummaryChart" class="w-full h-64"></canvas>
            @elseif($type === 'galleries' && isset($data['chart']))
                <canvas id="galleriesChart" class="w-full h-64"></canvas>
            @elseif($type === 'admins')
                <canvas id="adminsMonthlyChart" class="w-full h-64"></canvas>
            @else
                <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-chart-pie text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">Chart akan ditampilkan di sini</p>
                        <p class="text-sm text-gray-400">Integrasi dengan Chart.js atau library chart lainnya</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Category Breakdown -->
        @if(isset($data['by_category']) && $data['by_category']->count() > 0)
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-list text-green-500 mr-2"></i>
                Berdasarkan Kategori
            </h3>
            <div class="space-y-3">
                @foreach($data['by_category'] as $category)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <span class="font-medium text-gray-700">
                            {{ $category->newsCategory->name ?? $category->kategori->nama ?? 'Tanpa Kategori' }}
                        </span>
                    </div>
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm font-semibold">
                        {{ $category->total }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Daily Visitors Chart (for visitors type) -->
        @if($type === 'visitors' && isset($data['daily']))
        <div class="bg-white rounded-xl shadow-md p-6 lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-line text-purple-500 mr-2"></i>
                Kunjungan Harian (30 Hari Terakhir)
            </h3>
            <canvas id="visitorsDailyChart" class="w-full h-64"></canvas>
        </div>
        @endif

        @if($type === 'admins')
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-users-cog text-blue-500 mr-2"></i>
                Distribusi Admin
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <canvas id="adminsRoleChart" class="w-full h-64"></canvas>
                <canvas id="adminsStatusChart" class="w-full h-64"></canvas>
            </div>
        </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="mt-8 flex justify-center space-x-4">
        @if($type === 'news')
        <a href="{{ route('admin.news.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center transition-colors">
            <i class="fas fa-newspaper mr-2"></i> Kelola Berita
        </a>
        @elseif($type === 'galleries')
        <a href="{{ route('admin.galleries.index') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 py-3 rounded-lg flex items-center transition-colors">
            <i class="fas fa-images mr-2"></i> Kelola Galeri
        </a>
        @elseif($type === 'admins')
        <a href="{{ route('admin.admins.index') }}" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg flex items-center transition-colors">
            <i class="fas a-users mr-2"></i> Kelola Admin
        </a>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    @if($type === 'news' && isset($data['chart']))
    (function(){
        const ctx = document.getElementById('newsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($data['chart']['labels']),
                datasets: [{
                    label: 'Berita Terbit per Bulan',
                    data: @json($data['chart']['series']),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.15)',
                    fill: true,
                    tension: 0.35,
                }]
            },
            options: {
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true, precision: 0 } }
            }
        });
    })();
    @endif

    @if($type === 'galleries' && isset($data['chart']))
    (function(){
        const ctx = document.getElementById('galleriesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($data['chart']['labels']),
                datasets: [{
                    label: 'Galeri Terbit per Bulan',
                    data: @json($data['chart']['series']),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.15)',
                    fill: true,
                    tension: 0.35,
                }]
            },
            options: {
                plugins: { legend: { display: true } },
                scales: { y: { beginAtZero: true, precision: 0 } }
            }
        });
    })();
    @endif

    @if($type === 'visitors')
    (function(){
        // Summary chart: This Month vs Last Month
        const sctx = document.getElementById('visitorsSummaryChart')?.getContext('2d');
        if (sctx) {
            new Chart(sctx, {
                type: 'bar',
                data: {
                    labels: ['Bulan Ini', 'Bulan Lalu'],
                    datasets: [{
                        label: 'Total Kunjungan',
                        data: [{{ (int)($data['this_month'] ?? 0) }}, {{ (int)($data['last_month'] ?? 0) }}],
                        backgroundColor: ['rgba(34,197,94,0.6)','rgba(59,130,246,0.6)'],
                        borderColor: ['#22c55e','#3b82f6'],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, precision: 0 } }
                }
            });
        }

        // Daily chart for last 30 days
        const dctx = document.getElementById('visitorsDailyChart')?.getContext('2d');
        if (dctx) {
            const daily = @json($data['daily'] ?? []);
            const labels = daily.map(item => item.date);
            const values = daily.map(item => parseInt(item.total, 10));
            new Chart(dctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Kunjungan Harian',
                        data: values,
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139,92,246,0.15)',
                        fill: true,
                        tension: 0.35,
                        pointRadius: 2
                    }]
                },
                options: {
                    plugins: { legend: { display: true } },
                    scales: { 
                        y: { beginAtZero: true, precision: 0 },
                        x: { ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 10 } }
                    }
                }
            });
        }
    })();
    @endif

    @if($type === 'admins')
    (function(){
        // Monthly new admins
        const mctx = document.getElementById('adminsMonthlyChart')?.getContext('2d');
        if (mctx) {
            new Chart(mctx, {
                type: 'line',
                data: {
                    labels: @json($data['chart']['labels'] ?? []),
                    datasets: [{
                        label: 'Admin Baru per Bulan',
                        data: @json($data['chart']['series'] ?? []),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.12)',
                        tension: 0.35,
                        fill: true,
                        pointRadius: 2
                    }]
                },
                options: { plugins: { legend: { display: true } }, scales: { y: { beginAtZero: true, precision: 0 } } }
            });
        }

        // Role distribution
        const rctx = document.getElementById('adminsRoleChart')?.getContext('2d');
        if (rctx) {
            new Chart(rctx, {
                type: 'pie',
                data: {
                    labels: @json($data['role_chart']['labels'] ?? []),
                    datasets: [{
                        data: @json($data['role_chart']['series'] ?? []),
                        backgroundColor: ['#3b82f6','#93c5fd']
                    }]
                },
                options: { plugins: { legend: { position: 'bottom' } } }
            });
        }

        // Status distribution
        const sctx = document.getElementById('adminsStatusChart')?.getContext('2d');
        if (sctx) {
            new Chart(sctx, {
                type: 'doughnut',
                data: {
                    labels: @json($data['status_chart']['labels'] ?? []),
                    datasets: [{
                        data: @json($data['status_chart']['series'] ?? []),
                        backgroundColor: ['#2563eb','#93c5fd']
                    }]
                },
                options: { plugins: { legend: { position: 'bottom' } }, cutout: '60%' }
            });
        }
    })();
    @endif
});
</script>
@endpush
