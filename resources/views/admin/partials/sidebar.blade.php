<div class="w-64 bg-white shadow-md">
    <div class="p-4 border-b border-gray-200">
        <h1 class="text-xl font-bold text-gray-800">Galeri Sekolah</h1>
        <p class="text-xs text-gray-500">
            {{ Auth::guard('admin')->user()->role === 'super_admin' ? 'Monitoring Pengguna' : 'Admin Panel' }}
        </p>
        <div class="mt-2">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ Auth::guard('admin')->user()->role === 'super_admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                <i class="fas fa-{{ Auth::guard('admin')->user()->role === 'super_admin' ? 'crown' : 'user' }} mr-1"></i>
                {{ Auth::guard('admin')->user()->role === 'super_admin' ? 'Super Admin' : 'Admin' }}
            </span>
        </div>
    </div>
    <nav class="p-4">
        <div class="mb-6">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Menu Utama</h3>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-white bg-blue-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.news.index') }}" class="flex items-center p-2 text-sm font-medium {{ request()->routeIs('admin.news.*') ? 'text-white bg-blue-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        Berita
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.galleries.index') }}" class="flex items-center p-2 text-sm font-medium {{ request()->routeIs('admin.galleries.*') ? 'text-white bg-blue-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Galeri Foto
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.gallery-submissions.index') }}" class="flex items-center p-2 text-sm font-medium {{ request()->routeIs('admin.gallery-submissions.*') ? 'text-white bg-blue-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                        Pengajuan Galeri
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="mb-6">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Pengaturan</h3>
            <ul class="space-y-2">
                @if(Auth::guard('admin')->user()->role === 'super_admin')
                <li>
                    <a href="{{ route('admin.school-profile.edit') }}" class="flex items-center p-2 text-sm font-medium {{ request()->routeIs('admin.school-profile.*') ? 'text-white bg-blue-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Profil Sekolah
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.admins.index') }}" class="flex items-center p-2 text-sm font-medium {{ request()->routeIs('admin.admins.*') ? 'text-white bg-red-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        Manajemen Admin
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'text-white bg-blue-600' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a6 6 0 00-9-5.197M9 20H4v-1a6 6 0 019-5.197M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Pengelolaan Pengguna
                    </a>
                </li>
                @endif
            </ul>
        </div>
        
        @if(Auth::guard('admin')->user()->role === 'super_admin')
        <div class="mb-6">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Super Admin</h3>
            <ul class="space-y-2">
                <li>
                    <a href="#" class="flex items-center p-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Statistik Lengkap
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center p-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Pengaturan Sistem
                    </a>
                </li>
            </ul>
        </div>
        @endif
    </nav>
</div>