<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        try {
            $__logoPath = file_exists(public_path('eduspot.logo.png'))
                ? 'eduspot.logo.png'
                : (file_exists(public_path('eduspot.png')) ? 'eduspot.png' : null);
        } catch (\Exception $e) {
            $__logoPath = null;
        }
    @endphp
    <title>@yield('meta_title', config('app.name', $schoolProfile->school_name ?? 'Galeri Sekolah'))</title>
    <link rel="icon" type="image/png" href="{{ $__logoPath ? asset($__logoPath) : asset('favicon.png') }}">
    @include('layouts.seo')
    @php
        try {
            $useVite = file_exists(public_path('build/manifest.json'));
        } catch (\Exception $e) {
            $useVite = false;
        }
    @endphp
    
    @if ($useVite)
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    @stack('head')
</head>
<body class="bg-gray-50">
    @php
        $authRoutes = [
            'guest.login',
            'guest.register',
            'guest.password.request',
            'guest.password.reset',
            // alias used by Laravel notifications
            'password.reset',
        ];
        $hideNavbar = in_array(optional(Route::current())->getName(), $authRoutes, true);
    @endphp
    <!-- Navigation -->
    @unless($hideNavbar)
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Left: School brand -->
                <div class="flex items-center flex-1">
                    <a href="{{ route('home') }}" class="flex items-center">
                        @if($__logoPath)
                            <picture class="mr-3">
                                <img src="{{ asset($__logoPath) }}" alt="Logo {{ config('app.name', 'Galeri Sekolah') }}" class="h-12 md:h-14 w-auto">
                            </picture>
                        @else
                            <div class="bg-blue-600 text-white p-2 rounded-lg mr-3">
                                <i class="fas fa-school text-xl"></i>
                            </div>
                        @endif
                        <div>
                            <h1 class="text-lg font-bold text-gray-800">{{ config('app.name', 'Galeri Sekolah') }}</h1>
                            <p class="text-xs text-gray-500">Pendidikan Berkualitas</p>
                        </div>
                    </a>
                </div>
                
                <!-- Center: Main nav -->
                <div class="hidden md:flex items-center space-x-4 justify-center flex-1">
                    <a href="{{ route('home') }}" class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2"><i class="fas fa-home text-blue-500"></i><span>Beranda</span></a>
                    <a href="{{ route('about') }}" class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2"><i class="fas fa-info-circle text-blue-500"></i><span>Tentang</span></a>

                    @php($newsCategories = \App\Models\NewsCategory::active()->ordered()->get())
                    <div class="relative group">
                        <button id="newsDropdownBtn" class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2">
                            <i class="fas fa-newspaper text-blue-500"></i><span>Berita</span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        @if($newsCategories->isNotEmpty())
                        <div id="newsDropdownMenu" class="invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-opacity duration-200 absolute left-0 mt-2 w-56 bg-white border border-gray-100 rounded-lg shadow-lg py-2 z-50">
                            <a href="{{ route('news') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">Berita Terkini</a>
                            <div class="my-2 border-t border-gray-100"></div>
                            @foreach($newsCategories as $cat)
                                <a href="{{ route('news', ['category' => $cat->slug]) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50">{{ $cat->name }}</a>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('gallery') }}" class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2"><i class="fas fa-images text-blue-500"></i><span>Galeri</span></a>
                    
                    <a href="{{ route('contact') }}" class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2"><i class="fas fa-envelope text-blue-500"></i><span>Kontak</span></a>
                    
                </div>

                <!-- Right: Profile/Login -->
                <div class="hidden md:flex items-center justify-end flex-1">
                    @auth
                        <div class="relative group">
                            <button class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2">
                                <i class="fas fa-user text-blue-500"></i>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                <a href="{{ route('profile.edit', ['tab' => 'akun']) }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-user mr-2"></i> Pengaturan Akun
                                </a>
                                <a href="{{ route('profile.edit', ['tab' => 'aktivitas']) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-history mr-2"></i> Aktivitas
                                </a>
                                <a href="{{ route('profile.edit', ['tab' => 'favorit']) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="far fa-bookmark mr-2"></i> Favorit Saya
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form action="{{ route('guest.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('guest.login') }}" class="nav-link py-2 px-3 text-gray-700 hover:text-blue-600 font-medium transition-all inline-flex items-center gap-2">
                            <i class="fas fa-sign-in-alt text-blue-500"></i><span>Login</span>
                        </a>
                    @endauth
                </div>
                
                <div class="md:hidden flex items-center relative z-50">
                    <button id="mobile-menu-button" type="button" aria-controls="mobile-menu" aria-expanded="false" class="text-gray-500 hover:text-blue-600 focus:outline-none"
                        onclick="(function(){var m=document.getElementById('mobile-menu'); if(!m) return; var hidden=m.classList.contains('hidden'); if(hidden){m.classList.remove('hidden'); this.setAttribute('aria-expanded','true');} else {m.classList.add('hidden'); this.setAttribute('aria-expanded','false');}}).call(this)">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-100 shadow-inner">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all"><i class="fas fa-home mr-2 text-blue-500"></i> Beranda</a>
                <a href="{{ route('about') }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all"><i class="fas fa-info-circle mr-2 text-blue-500"></i> Tentang</a>
                <div>
                    @php($mobileNewsOpen = request()->routeIs('news'))
                    <button id="mobile-news-toggle" type="button" aria-controls="mobile-news-submenu" aria-expanded="{{ $mobileNewsOpen ? 'true' : 'false' }}" class="w-full flex items-center justify-between py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all"
                        onclick="(function(){var sub=document.getElementById('mobile-news-submenu'); if(!sub) return; var isHidden=sub.classList.contains('hidden'); var icon=this.querySelector('i.fas.fa-chevron-down'); if(isHidden){ sub.classList.remove('hidden'); this.setAttribute('aria-expanded','true'); if(icon){ icon.classList.add('transform'); icon.classList.add('rotate-180'); }} else { sub.classList.add('hidden'); this.setAttribute('aria-expanded','false'); if(icon){ icon.classList.remove('rotate-180'); icon.classList.remove('transform'); } } }).call(this)">
                        <span class="inline-flex items-center"><i class="fas fa-newspaper mr-2 text-blue-500"></i> Berita</span>
                        <i class="fas fa-chevron-down text-xs {{ $mobileNewsOpen ? 'transform rotate-180' : '' }}"></i>
                    </button>
                    @php($newsCategoriesMobile = \App\Models\NewsCategory::active()->ordered()->get())
                    <div id="mobile-news-submenu" class="{{ $mobileNewsOpen ? '' : 'hidden' }} mt-1 space-y-1 border border-gray-100 bg-gray-50 rounded-md ">
                        @php($currentCat = request('category'))
                        <a href="{{ route('news') }}"
                           @if(!$currentCat) aria-current="page" @endif
                           class="block py-2 pl-10 pr-3 rounded-md transition-all
                           {{ !$currentCat ? 'bg-blue-100 text-blue-700 font-semibold border border-blue-200 shadow-sm' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-100' }}
                           active:shadow-md">
                           Semua Berita
                        </a>
                        @foreach($newsCategoriesMobile as $cat)
                            @php($isActive = ($currentCat === $cat->slug))
                            <a href="{{ route('news', ['category' => $cat->slug]) }}"
                               @if($isActive) aria-current="page" @endif
                               class="block py-2 pl-10 pr-3 rounded-md transition-all
                               {{ $isActive ? 'bg-blue-100 text-blue-700 font-semibold border border-blue-200 shadow-sm' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-100' }}
                               active:shadow-md">
                               {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('gallery') }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all"><i class="fas fa-images mr-2 text-blue-500"></i> Galeri</a>
                <a href="{{ route('contact') }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all"><i class="fas fa-envelope mr-2 text-blue-500"></i> Kontak</a>
                
                <!-- Guest Authentication Mobile -->
                @auth
                    <div class="border-t border-gray-200 pt-2 mt-2 space-y-1">
                        <button id="mobile-account-toggle" type="button" aria-controls="mobile-account-submenu" aria-expanded="false" class="w-full flex items-center justify-between py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all">
                            <span><i class="fas fa-user mr-2 text-blue-500"></i> {{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div id="mobile-account-submenu" class="hidden ml-8 mt-1 space-y-1">
                            @php($currentTab = request('tab'))
                            @php($isAkun = request()->routeIs('profile.edit') && (!$currentTab || $currentTab === 'akun'))
                            @php($isAktivitas = request()->routeIs('profile.edit') && $currentTab === 'aktivitas')
                            @php($isFavorit = request()->routeIs('profile.edit') && $currentTab === 'favorit')
                            <a href="{{ route('profile.edit', ['tab' => 'akun']) }}"
                               @if($isAkun) aria-current="page" @endif
                               class="block py-2 px-3 rounded-md transition-all active:shadow-md
                               {{ $isAkun ? 'bg-blue-100 text-blue-700 font-semibold border border-blue-200 shadow-sm' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                <i class="fas fa-user-cog mr-2"></i> Pengaturan Akun
                            </a>
                            <a href="{{ route('profile.edit', ['tab' => 'aktivitas']) }}"
                               @if($isAktivitas) aria-current="page" @endif
                               class="block py-2 px-3 rounded-md transition-all active:shadow-md
                               {{ $isAktivitas ? 'bg-blue-100 text-blue-700 font-semibold border border-blue-200 shadow-sm' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                <i class="fas fa-history mr-2"></i> Aktivitas
                            </a>
                            <a href="{{ route('profile.edit', ['tab' => 'favorit']) }}"
                               @if($isFavorit) aria-current="page" @endif
                               class="block py-2 px-3 rounded-md transition-all active:shadow-md
                               {{ $isFavorit ? 'bg-blue-100 text-blue-700 font-semibold border border-blue-200 shadow-sm' : 'text-gray-700 hover:text-blue-600 hover:bg-blue-50' }}">
                                <i class="far fa-bookmark mr-2"></i> Favorit Saya
                            </a>
                            <div class="border-t border-gray-200"></div>
                            <form action="{{ route('guest.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left py-2 px-3 text-red-600 hover:bg-red-50 rounded-md transition-all active:shadow-md">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="border-t border-gray-200 pt-2 mt-2">
                        <a href="{{ route('guest.login') }}" class="block py-2 px-3 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-md font-medium transition-all">
                            <i class="fas fa-sign-in-alt mr-2 text-blue-500"></i> Login
                        </a>
                    </div>
                @endauth
                </div>
            </div>
        </div>
    </nav>
    @endunless
    <script>
    document.addEventListener('DOMContentLoaded', function(){
        var mobileMenuBtn = document.getElementById('mobile-menu-button');
        var mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function(e){
                e.preventDefault();
                var isHidden = mobileMenu.classList.contains('hidden');
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    mobileMenuBtn.setAttribute('aria-expanded', 'true');
                } else {
                    mobileMenu.classList.add('hidden');
                    mobileMenuBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }

        var newsToggle = document.getElementById('mobile-news-toggle');
        var newsSub = document.getElementById('mobile-news-submenu');
        if (newsToggle && newsSub) {
            newsToggle.addEventListener('click', function(e){
                e.preventDefault();
                var isHidden = newsSub.classList.contains('hidden');
                if (isHidden) {
                    newsSub.classList.remove('hidden');
                    newsToggle.setAttribute('aria-expanded', 'true');
                    var icon = newsToggle.querySelector('i.fas.fa-chevron-down');
                    if (icon) { icon.classList.add('transform'); icon.classList.add('rotate-180'); }
                    // Close account submenu when opening news
                    if (accSub && !accSub.classList.contains('hidden')) {
                        accSub.classList.add('hidden');
                        if (accToggle) accToggle.setAttribute('aria-expanded','false');
                        var aicon = accToggle && accToggle.querySelector('i.fas.fa-chevron-down');
                        if (aicon) { aicon.classList.remove('rotate-180'); aicon.classList.remove('transform'); }
                    }
                } else {
                    newsSub.classList.add('hidden');
                    newsToggle.setAttribute('aria-expanded', 'false');
                    var icon2 = newsToggle.querySelector('i.fas.fa-chevron-down');
                    if (icon2) { icon2.classList.remove('rotate-180'); icon2.classList.remove('transform'); }
                }
            });
        }

        var accToggle = document.getElementById('mobile-account-toggle');
        var accSub = document.getElementById('mobile-account-submenu');
        if (accToggle && accSub) {
            accToggle.addEventListener('click', function(e){
                e.preventDefault();
                // Close news when opening account
                if (newsSub && !newsSub.classList.contains('hidden')) {
                    newsSub.classList.add('hidden');
                    if (newsToggle) newsToggle.setAttribute('aria-expanded','false');
                    var nicon = newsToggle && newsToggle.querySelector('i.fas.fa-chevron-down');
                    if (nicon) { nicon.classList.remove('rotate-180'); nicon.classList.remove('transform'); }
                }
                var accHidden = accSub.classList.contains('hidden');
                if (accHidden) {
                    accSub.classList.remove('hidden');
                    accToggle.setAttribute('aria-expanded','true');
                    var aicon2 = accToggle.querySelector('i.fas.fa-chevron-down');
                    if (aicon2) { aicon2.classList.add('transform'); aicon2.classList.add('rotate-180'); }
                } else {
                    accSub.classList.add('hidden');
                    accToggle.setAttribute('aria-expanded','false');
                    var aicon3 = accToggle.querySelector('i.fas.fa-chevron-down');
                    if (aicon3) { aicon3.classList.remove('rotate-180'); aicon3.classList.remove('transform'); }
                }
            });
        }

        // Outside click to close submenus
        document.addEventListener('click', function(e){
            var withinMobileMenu = e.target.closest('#mobile-menu');
            if (!withinMobileMenu) {
                if (newsSub && !newsSub.classList.contains('hidden')) {
                    newsSub.classList.add('hidden');
                    if (newsToggle) newsToggle.setAttribute('aria-expanded','false');
                    var icon3 = newsToggle && newsToggle.querySelector('i.fas.fa-chevron-down');
                    if (icon3) { icon3.classList.remove('rotate-180'); icon3.classList.remove('transform'); }
                }
                if (accSub && !accSub.classList.contains('hidden')) {
                    accSub.classList.add('hidden');
                    if (accToggle) accToggle.setAttribute('aria-expanded','false');
                    var aicon4 = accToggle && accToggle.querySelector('i.fas.fa-chevron-down');
                    if (aicon4) { aicon4.classList.remove('rotate-180'); aicon4.classList.remove('transform'); }
                }
            }
        });
    });
    </script>
</body>
</html>