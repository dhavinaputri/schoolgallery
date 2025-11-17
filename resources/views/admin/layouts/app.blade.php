<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name') }}</title>
    @php
        try {
            $__logoPath = file_exists(public_path('eduspot.logo.png'))
                ? 'eduspot.logo.png'
                : (file_exists(public_path('eduspot.png')) ? 'eduspot.png' : null);
        } catch (\Exception $e) {
            $__logoPath = null;
        }
    @endphp
    <link rel="icon" type="image/png" href="{{ $__logoPath ? asset($__logoPath) : asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .sidebar-item {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            margin: 0 0.5rem;
        }
        .sidebar-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .sidebar-item.active {
            background-color: #3b82f6;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .dropdown-content {
            display: none;
            transition: all 0.3s ease;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-blue-800 to-blue-900 text-white shadow-xl">
            <div class="p-6 flex items-center space-x-3">
                <div class="bg-white p-2 rounded-lg shadow-inner">
                    <i class="fas fa-school text-blue-800 text-xl"></i>
                </div>
                <h2 class="text-xl font-bold">Admin Panel</h2>
            </div>
            <div class="px-4 py-2 mt-2">
                <div class="bg-blue-700 bg-opacity-30 rounded-lg p-2 mb-6">
                    <div class="flex items-center space-x-3 px-2">
                        <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-blue-800">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium">{{ auth('admin')->user()->name }}</p>
                            <p class="text-xs text-blue-200">{{ Auth::guard('admin')->user()->role === 'super_admin' ? 'Admin' : 'Petugas' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <nav class="mt-2 px-2">
                <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider px-4 mb-2">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-item flex items-center px-4 py-3 mb-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i> Dashboard
                </a>
                <a href="{{ route('admin.news.index') }}" class="sidebar-item flex items-center px-4 py-3 mb-2 {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper mr-3 w-5 text-center"></i> Berita
                </a>
                {{-- Events menu hidden per request --}}
                <a href="{{ route('admin.galleries.index') }}" class="sidebar-item flex items-center px-4 py-3 mb-2 {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}">
                    <i class="fas fa-images mr-3 w-5 text-center"></i> Galeri
                </a>
                <a href="{{ route('admin.teachers.index') }}" class="sidebar-item flex items-center px-4 py-3 mb-2 {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
                    <i class="fas fa-chalkboard-teacher mr-3 w-5 text-center"></i> Guru
                </a>
                <a href="{{ route('admin.reports.index') }}" class="sidebar-item flex items-center px-4 py-3 mb-2 {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line mr-3 w-5 text-center"></i> Laporan
                </a>
                
                <p class="text-xs font-semibold text-blue-200 uppercase tracking-wider px-4 mb-2 mt-6">Pengaturan</p>
                @if(Auth::guard('admin')->user()->role === 'super_admin')
                <a href="{{ route('admin.school-profile.edit') }}" class="sidebar-item flex items-center px-4 py-3 mb-2 {{ request()->routeIs('admin.school-profile.*') ? 'active' : '' }}">
                    <i class="fas fa-school mr-3 w-5 text-center"></i> Profil Sekolah
                </a>
                @endif
                <form action="{{ route('admin.logout') }}" method="POST" class="px-2 mt-6">
                    @csrf
                    <button type="submit" class="w-full sidebar-item flex items-center px-4 py-3 mb-2 text-red-200 hover:text-red-100">
                        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Logout
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center px-6 py-4">
                    <div class="flex items-center">
                        <button id="sidebar-toggle" class="mr-4 text-gray-500 hover:text-blue-600 lg:hidden">
                            <i class="fas fa-bars"></i>
                        </button>
                        @unless (request()->routeIs('admin.dashboard'))
                            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('admin.dashboard') }}" class="mr-3 inline-flex items-center px-3 py-1.5 rounded-md border text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                        @endunless
                        <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="border-l border-gray-200 h-6 mx-2"></div>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white">
                                <span>{{ substr(auth('admin')->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="text-sm font-medium text-gray-700 hidden md:inline-block">{{ auth('admin')->user()->name }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                {{-- Notifikasi Global --}}
                <!-- @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-sm mb-6 flex items-center transition duration-300 ease-in-out">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <div>
                            <p class="font-medium">Berhasil!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                        <button class="ml-auto text-green-500 hover:text-green-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif -->

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-sm mb-6 flex items-center transition duration-300 ease-in-out">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <div>
                            <p class="font-medium">Error!</p>
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                        <button class="ml-auto text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <script>
        // Sidebar toggle functionality
        document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
            const sidebar = document.querySelector('.w-64');
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('lg:translate-x-0');
        });

        // Close notification alerts with fade-out effect
        document.querySelectorAll('.bg-green-50 button, .bg-red-50 button').forEach(button => {
            button.addEventListener('click', function() {
                const alertBox = this.closest('div[class*="bg-"]');
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 300);
            });
        });
    </script>
</body>
</html>
