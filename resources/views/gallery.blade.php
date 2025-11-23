@extends('layouts.app')

@push('styles')
<style>
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        padding: 15px 25px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateX(120%);
        transition: transform 0.3s ease-in-out;
    }
    .toast.show {
        transform: translateX(0);
    }
    .toast.success {
        background-color: #10B981;
    }
    .toast.error {
        background-color: #EF4444;
    }
    
    /* Styling untuk card galeri */
    .gallery-item {
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .gallery-item .image-container {
        /* Samakan rasio semua thumbnail tanpa mengubah file asli */
        aspect-ratio: 16 / 9; /* 16:9 agar mirip tampilan admin */
        height: auto;
        overflow: hidden;
        position: relative;
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-item:hover img {
        transform: scale(1.05);
    }
    .gallery-item .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .gallery-item .card-footer {
        background: transparent;
        border-top: 0;
        padding-top: 0;
        margin-top: auto;
    }
</style>
@endpush

@section('content')
    @if(session('error'))
        <div class="toast error show">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="toast success show">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-block bg-blue-500/30 text-white text-sm font-semibold px-4 py-1 rounded-full mb-4 backdrop-blur-sm border border-blue-400/30">
                    <i class="fas fa-images mr-2"></i> GALERI SEKOLAH
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-shadow leading-tight">
                    Galeri <span class="text-yellow-300 relative inline-block">
                        {{ $title ?? 'Sekolah' }}
                        <svg class="absolute -bottom-2 left-0 w-full" height="6" viewBox="0 0 200 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 3C50 0.5 150 0.5 200 3" stroke="#FBBF24" stroke-width="5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>
                <p class="text-xl mb-8 text-blue-100 leading-relaxed max-w-3xl mx-auto">
                    @if(isset($title))
                        Koleksi galeri {{ strtolower($title) }} {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}
                    @else
                        Dokumentasi visual dari berbagai kegiatan dan momen berharga di {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}
                    @endif
                </p>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            @php
                $kategoris = \App\Models\Kategori::where('is_active', true)->get();
                
                // Tentukan warna dan ikon untuk setiap kategori
                $categoryStyles = [
                    'kegiatan-sekolah' => [
                        'from' => 'from-blue-500',
                        'to' => 'to-blue-700',
                        'text' => 'text-blue-100',
                        'icon' => 'fa-school',
                        'delay' => '0'
                    ],
                    'fasilitas-sekolah' => [
                        'from' => 'from-green-500',
                        'to' => 'to-green-700',
                        'text' => 'text-green-100',
                        'icon' => 'fa-archway',
                        'delay' => '100'
                    ],
                    'prestasi' => [
                        'from' => 'from-purple-500',
                        'to' => 'to-purple-700',
                        'text' => 'text-purple-100',
                        'icon' => 'fa-trophy',
                        'delay' => '200'
                    ],
                    'default' => [
                        'from' => 'from-indigo-500',
                        'to' => 'to-indigo-700',
                        'text' => 'text-indigo-100',
                        'icon' => 'fa-image',
                        'delay' => '0'
                    ]
                ];
            @endphp
            
            <div class="mb-6 sm:mb-8">
                <form action="{{ route('gallery') }}" method="GET" class="mb-0" id="gallerySearchForm">
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari galeri..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center gap-2">
                            <i class="fas fa-search"></i>
                            <span class="ml-1">Cari</span>
                        </button>
                    </div>
                </form>
            </div>
            <script>
                (function(){
                    const form = document.getElementById('gallerySearchForm');
                    if (!form) return;
                    const input = form.querySelector('input[name="search"]');
                    let t;
                    input && input.addEventListener('input', function(){
                        clearTimeout(t);
                        t = setTimeout(()=>{ form.submit(); }, 300);
                    });
                })();
            </script>
            
            @if(!$kategoris->isEmpty())
                <div class="mb-6 sm:mb-8">
                    <div class="overflow-x-auto -mx-4 px-4 sm:mx-0 sm:px-0">
                        <div class="inline-flex gap-2 sm:gap-3 w-max sm:w-auto sm:flex sm:flex-wrap sm:justify-center">
                            <a href="{{ route('gallery') }}" 
                               class="inline-block px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-sm sm:text-base {{ !isset($activeCategory) ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} font-medium transition-all duration-200 shadow-sm">Semua Galeri</a>
                            @foreach($kategoris as $kategori)
                                <a href="{{ route('gallery.category', ['category' => $kategori->slug]) }}" 
                                   class="inline-block px-3 py-1.5 sm:px-4 sm:py-2 rounded-full text-sm sm:text-base {{ isset($activeCategory) && $activeCategory === $kategori->slug ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} font-medium transition-all duration-200 shadow-sm">
                                    {{ $kategori->nama }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Gallery Grid Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            @if($galleries->isEmpty())
                <div class="text-center py-16">
                    <div class="text-6xl text-gray-300 mb-4"><i class="fas fa-images"></i></div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak ada galeri ditemukan</h3>
                    <p class="text-gray-600">Silakan coba dengan kata kunci lain atau kembali lagi nanti.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6">
                    @foreach($galleries as $gallery)
                        <div class="gallery-item bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-100"
                             data-aos="fade-up" data-aos-delay="{{ $loop->index % 4 * 100 }}">
                            <a href="{{ route('gallery.detail', $gallery->id) }}" class="block image-container focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-none">
                                @if($gallery->image)
                                    <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($gallery->image) }}" loading="lazy" alt="{{ $gallery->title }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" loading="lazy" alt="{{ $gallery->title }}">
                                @endif
                            </a>
                            <div class="p-3 md:p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 text-sm md:text-base">{{ $gallery->title }}</h3>
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                    <span><i class="far fa-calendar-alt mr-1"></i> {{ $gallery->created_at->format('d M Y') }}</span>
                                    @if($gallery->kategori)
                                        <span class="px-2 py-1 rounded-full text-xs" 
                                              style="background-color: rgba(99, 102, 241, 0.1); color: #4f46e5;">
                                            {{ $gallery->kategori->nama }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('gallery.detail', $gallery->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        <i class="far fa-eye mr-1"></i> Lihat
                                    </a>
                                    @if($gallery->image)
                                        <a href="{{ \App\Helpers\StorageHelper::getStorageUrl($gallery->image) }}" 
                                           class="text-gray-600 hover:text-gray-800 text-sm font-medium" 
                                           title="Download Gambar"
                                           download>
                                            <i class="fas fa-download mr-1"></i> Unduh
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-sm font-medium" title="Gambar tidak tersedia">
                                            <i class="fas fa-download mr-1"></i> Unduh
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-3 hidden sm:flex items-center justify-between text-sm text-gray-600">
                                    <div class="flex items-center gap-4">
                                        @auth
                                            @php
                                                $userHasLiked = $gallery->user_has_liked ?? false;
                                                $userHasFav = $gallery->user_has_favorited ?? false;
                                            @endphp
                                            <button onclick="toggleLike({{ $gallery->id }})" 
                                                    class="like-btn inline-flex items-center transition-colors duration-200 {{ $userHasLiked ? 'text-pink-600' : 'text-gray-500 hover:text-pink-600' }}"
                                                    data-gallery-id="{{ $gallery->id }}"
                                                    data-liked="{{ $userHasLiked ? 'true' : 'false' }}">
                                                <i class="{{ $userHasLiked ? 'fas' : 'far' }} fa-heart mr-1"></i>
                                                <span class="like-count">{{ $gallery->likes_count ?? 0 }}</span>
                                            </button>
                                            <button onclick="toggleFavoriteList({{ $gallery->id }})"
                                                    class="fav-btn inline-flex items-center px-2 py-1 rounded transition-colors duration-200 {{ $userHasFav ? 'text-blue-700 bg-blue-50 ring-1 ring-blue-200' : 'text-gray-500 hover:text-blue-700 hover:bg-blue-50' }}"
                                                    data-gallery-id="{{ $gallery->id }}"
                                                    data-favorited="{{ $userHasFav ? 'true' : 'false' }}"
                                                    aria-pressed="{{ $userHasFav ? 'true' : 'false' }}"
                                                    title="{{ $userHasFav ? 'Tersimpan' : 'Simpan ke favorit' }}">
                                                <i class="{{ $userHasFav ? 'fas' : 'far' }} fa-bookmark mr-1"></i>
                                                <span class="fav-count">{{ $gallery->favorites_count ?? 0 }}</span>
                                            </button>
                                        @else
                                            <div class="inline-flex items-center text-gray-400">
                                                <i class="far fa-heart mr-1"></i>
                                                <span class="like-count" data-gallery-id="{{ $gallery->id }}">{{ $gallery->likes_count ?? 0 }}</span>
                                            </div>
                                            <div class="inline-flex items-center text-gray-400">
                                                <i class="far fa-bookmark mr-1"></i>
                                                <span class="fav-count" data-gallery-id="{{ $gallery->id }}">{{ $gallery->favorites_count ?? 0 }}</span>
                                            </div>
                                        @endauth
                                    </div>
                                    <a href="{{ route('gallery.detail', $gallery->id) }}#comments" class="inline-flex items-center text-gray-500 hover:text-blue-600">
                                        <i class="far fa-comment mr-1"></i>
                                        <span>{{ $gallery->comments_count ?? 0 }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($galleries->hasPages())
                    <div class="mt-6 flex items-center justify-between gap-2 sm:gap-4">
                        @php
                            // Preserve active query parameters (search, category, etc.)
                            $p = $galleries->appends(request()->query());
                            $prevUrl = $p->previousPageUrl();
                            $nextUrl = $p->nextPageUrl();
                            $current = $galleries->currentPage();
                            $totalPages = $galleries->lastPage();
                        @endphp

                        <div class="flex-1">
                            <a href="{{ $prevUrl ?: '#' }}"
                               class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-md border text-xs sm:text-sm font-medium transition-colors
                                      {{ $prevUrl ? 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700' : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' }}"
                               {{ $prevUrl ? '' : 'aria-disabled=true' }}>
                                <span class="mr-1 sm:mr-2">&larr;</span> <span class="hidden xs:inline">Previous page</span>
                            </a>
                        </div>

                        <div class="hidden sm:block text-sm text-gray-600 whitespace-nowrap">
                            Page <span class="font-semibold">{{ $current }}</span> of <span class="font-semibold">{{ $totalPages }}</span>
                        </div>

                        <div class="flex-1 flex justify-end">
                            <a href="{{ $nextUrl ?: '#' }}"
                               class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 rounded-md border text-xs sm:text-sm font-medium transition-colors
                                      {{ $nextUrl ? 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700' : 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' }}"
                               {{ $nextUrl ? '' : 'aria-disabled=true' }}>
                                <span class="hidden xs:inline">Next page</span> <span class="ml-1 sm:ml-2">&rarr;</span>
                            </a>
                        </div>
                    </div>
                    <!-- Keyboard shortcuts for pagination: Left/Right arrows -->
                    <script>
                        (function(){
                            const prevUrl = @json($prevUrl);
                            const nextUrl = @json($nextUrl);
                            document.addEventListener('keydown', function(e){
                                const tag = (e.target && e.target.tagName) ? e.target.tagName.toLowerCase() : '';
                                // avoid when typing in inputs/textareas
                                if (tag === 'input' || tag === 'textarea') return;
                                if (e.key === 'ArrowLeft' && prevUrl) {
                                    window.location.href = prevUrl;
                                } else if (e.key === 'ArrowRight' && nextUrl) {
                                    window.location.href = nextUrl;
                                }
                            });
                        })();
                    </script>
                @endif
            @endif
        </div>
    </section>


    <!-- Call to Action -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-2/3 mb-8 md:mb-0" data-aos="fade-right">
                    <h2 class="text-3xl font-bold mb-4">Punya Foto Kegiatan Sekolah?</h2>
                    <p class="text-blue-100">Bagikan momen berharga di sekolah dengan mengirimkan foto kegiatan untuk ditampilkan di galeri kami.</p>
                </div>
                <div class="md:w-1/3 text-center md:text-right" data-aos="fade-left">
                    @auth
                        <a href="{{ route('gallery.submit') }}" class="btn-hover inline-block bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Foto
                        </a>
                    @else
                        <a href="{{ route('guest.login') }}" class="btn-hover inline-block bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Kirim
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
@push('scripts')
<script>
    // Auto-hide toast messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.querySelector('.toast');
        if (toast) {
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    });

    // Like functionality
    async function toggleLike(galleryId) {
        try {
            const likeBtn = document.querySelector(.like-btn[data-gallery-id="${galleryId}"]);
            if (!likeBtn) return;
            likeBtn.disabled = true;

            const response = await fetch(/gallery/${galleryId}/like, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();
            
            if (data.success) {
                // Update like button
                const likeIcon = likeBtn.querySelector('i');
                const likeCount = likeBtn.querySelector('.like-count');
                
                if (likeIcon) {
                    if (data.liked) {
                        likeIcon.className = 'fas fa-heart mr-1';
                        likeBtn.className = 'like-btn inline-flex items-center text-pink-600 hover:text-pink-700';
                        likeBtn.dataset.liked = 'true';
                    } else {
                        likeIcon.className = 'far fa-heart mr-1';
                        likeBtn.className = 'like-btn inline-flex items-center text-gray-500 hover:text-pink-700';
                        likeBtn.dataset.liked = 'false';
                    }
                }
                
                if (likeCount && typeof data.like_count !== 'undefined') {
                    likeCount.textContent = data.like_count;
                }
            }
        } catch (error) {
            console.error('Error toggling like:', error);
            showNotification('Terjadi kesalahan saat like/unlike', 'error');
        } finally {
            const likeBtn = document.querySelector(.like-btn[data-gallery-id="${galleryId}"]);
            if (likeBtn) likeBtn.disabled = false;
        }
    }

    // Favorite functionality for list
    async function toggleFavoriteList(galleryId) {
        try {
            const favBtn = document.querySelector(.fav-btn[data-gallery-id="${galleryId}"]);
            if (!favBtn) return;
            favBtn.disabled = true;

            const response = await fetch(/gallery/${galleryId}/favorite, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();
            if (data && data.success) {
                const icon = favBtn.querySelector('i');
                const countEl = favBtn.querySelector('.fav-count');
                if (icon) {
                    if (data.favorited) {
                        favBtn.className = 'fav-btn inline-flex items-center text-blue-700';
                        icon.className = 'fas fa-bookmark mr-1';
                        favBtn.dataset.favorited = 'true';
                    } else {
                        favBtn.className = 'fav-btn inline-flex items-center text-gray-500 hover:text-blue-700';
                        icon.className = 'far fa-bookmark mr-1';
                        favBtn.dataset.favorited = 'false';
                    }
                }
                if (countEl && typeof data.favorite_count !== 'undefined') {
                    countEl.textContent = data.favorite_count;
                }
            }
        } catch (e) {
            console.error('Error toggling favorite:', e);
        } finally {
            const favBtn = document.querySelector(.fav-btn[data-gallery-id="${galleryId}"]);
            if (favBtn) favBtn.disabled = false;
        }
    }

    // On load, fetch latest like and favorite status/counts per card
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.fav-btn, .fav-count').forEach(async (node) => {
            const card = node.closest('[data-aos]');
            const idBtn = document.querySelector('.fav-btn', card);
        });
        // Fetch counts for all cards (works for auth or guest)
        const ids = new Set();
        document.querySelectorAll('.like-btn[data-gallery-id], .like-count[data-gallery-id], .fav-btn[data-gallery-id], .fav-count[data-gallery-id]').forEach(el => {
            const id = el.getAttribute('data-gallery-id');
            if (id) ids.add(id);
        });

        ids.forEach(async (id) => {
            // Favorite status/count
            try {
                const resFav = await fetch(/gallery/${id}/favorite-status);
                const dataFav = await resFav.json();
                const favBtn = document.querySelector(.fav-btn[data-gallery-id="${id}"]);
                const favCountEl = favBtn ? favBtn.querySelector('.fav-count') : document.querySelector(.fav-count[data-gallery-id="${id}"]);
                if (favCountEl && typeof dataFav.favorite_count !== 'undefined') {
                    favCountEl.textContent = dataFav.favorite_count;
                }
                if (favBtn && dataFav.success && typeof dataFav.favorited !== 'undefined') {
                    const icon = favBtn.querySelector('i');
                    if (icon) {
                        if (dataFav.favorited) {
                            favBtn.className = 'fav-btn inline-flex items-center text-blue-700';
                            icon.className = 'fas fa-bookmark mr-1';
                            favBtn.dataset.favorited = 'true';
                        } else {
                            favBtn.className = 'fav-btn inline-flex items-center text-gray-500 hover:text-blue-700';
                            icon.className = 'far fa-bookmark mr-1';
                            favBtn.dataset.favorited = 'false';
                        }
                    }
                }
            } catch (e) {}

            // Like status/count
            try {
                const resLike = await fetch(/gallery/${id}/like-status);
                const dataLike = await resLike.json();
                const likeBtn = document.querySelector(.like-btn[data-gallery-id="${id}"]);
                const likeCountEl = likeBtn ? likeBtn.querySelector('.like-count') : document.querySelector(.like-count[data-gallery-id="${id}"]);
                if (likeCountEl && typeof dataLike.like_count !== 'undefined') {
                    likeCountEl.textContent = dataLike.like_count;
                }
                if (likeBtn && dataLike.success && typeof dataLike.liked !== 'undefined') {
                    const icon = likeBtn.querySelector('i');
                    if (icon) {
                        if (dataLike.liked) {
                            likeBtn.className = 'like-btn inline-flex items-center text-pink-600 hover:text-pink-700';
                            icon.className = 'fas fa-heart mr-1';
                            likeBtn.dataset.liked = 'true';
                        } else {
                            likeBtn.className = 'like-btn inline-flex items-center text-gray-500 hover:text-pink-700';
                            icon.className = 'far fa-heart mr-1';
                            likeBtn.dataset.liked = 'false';
                        }
                    }
                }
            } catch (e) {}
        });
    });
</script>
@endpush

@endsection