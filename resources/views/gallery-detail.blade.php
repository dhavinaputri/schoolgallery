@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div data-aos="fade-up" data-aos-duration="1000">
                <div class="text-center">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight leading-tight mb-3">
                        {{ $gallery->title }}
                    </h1>
                    <p class="inline-flex items-center gap-2 text-blue-100 text-sm md:text-base">
                        <i class="far fa-calendar-alt"></i> <span>{{ $gallery->created_at->format('d M Y') }}</span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Detail Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left/Main: image and details -->
                <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100 mb-8">
                <div class="w-full" style="aspect-ratio: 16 / 9;">
                    <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($gallery->image) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap items-center justify-between gap-2 sm:gap-3 mb-4">
                        <div class="hidden"></div>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                            @auth
                            @php
                                $userLiked = $gallery->likes->where('user_id', auth()->id())->isNotEmpty();
                                $userFavorited = $gallery->favorites->where('user_id', auth()->id())->isNotEmpty();
                            @endphp
                            <button id="likeBtn" 
                                    data-gallery-id="{{ $gallery->id }}" 
                                    data-liked="{{ $userLiked ? 'true' : 'false' }}"
                                    class="inline-flex items-center {{ $userLiked ? 'text-pink-600 border border-pink-200 bg-pink-50' : 'text-gray-700 border border-gray-200 bg-white' }} hover:border-pink-300 hover:bg-pink-50 transition-colors px-3 py-1.5 rounded-full text-sm sm:text-base">
                                <i class="{{ $userLiked ? 'fas' : 'far' }} fa-heart mr-1.5"></i>
                                <span id="likeText" class="font-medium">{{ $userLiked ? 'Batalkan' : 'Suka' }}</span>
                                <span id="likeCount" class="ml-1 text-[10px] sm:text-xs bg-gray-100 px-1.5 py-0.5 rounded-full">({{ $gallery->likes->count() }})</span>
                                    </button>
                                <!-- Favorite Button -->
                                <button id="favBtn"
                                        data-gallery-id="{{ $gallery->id }}"
                                        class="inline-flex items-center text-gray-700 border border-gray-200 bg-white hover:text-blue-700 hover:border-blue-300 hover:bg-blue-50 transition-colors px-3 py-1.5 rounded-full text-sm sm:text-base">
                                    <i id="favIcon" class="{{ $userFavorited ? 'fas' : 'far' }} fa-bookmark mr-1.5"></i>
                                    <span id="favText" class="font-medium">{{ $userFavorited ? 'Hapus dari Favorit' : 'Simpan' }}</span>
                                    <span id="favCount" class="ml-1 text-[10px] sm:text-xs bg-gray-100 px-1.5 py-0.5 rounded-full">({{ $gallery->favorites->count() }})</span>
                                </button>
                            @else
                            <a href="{{ route('guest.login') }}" class="inline-flex items-center text-gray-700 border border-gray-200 bg-white hover:text-pink-700 hover:border-pink-300 hover:bg-pink-50 transition-colors px-3 py-1.5 rounded-full text-sm sm:text-base">
                                <i class="far fa-heart mr-1.5"></i>
                                <span class="font-medium">Suka</span>
                                <span id="likeCount" class="ml-1 text-[10px] sm:text-xs bg-gray-100 px-1.5 py-0.5 rounded-full">({{ $gallery->likes->count() }})</span>
                            </a>
                            <a href="{{ route('guest.login') }}" class="inline-flex items-center text-gray-700 border border-gray-200 bg-white hover:text-blue-700 hover:border-blue-300 hover:bg-blue-50 transition-colors px-3 py-1.5 rounded-full text-sm sm:text-base">
                                <i class="far fa-bookmark mr-1.5"></i>
                                <span class="font-medium">Simpan</span>
                            </a>
                            @endauth
                            <a href="{{ \App\Helpers\StorageHelper::getStorageUrl($gallery->image) }}" 
                               class="inline-flex items-center text-blue-700 border border-blue-200 bg-white hover:text-blue-800 hover:border-blue-300 hover:bg-blue-50 transition-colors px-3 py-1.5 rounded-full text-sm sm:text-base" download>
                                <i class="fas fa-download mr-1.5"></i> Unduh
                            </a>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $gallery->title }}</h2>
                    @if($gallery->description)
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($gallery->description)) !!}
                        </div>
                    @endif
                </div>
            </div>

            </div> <!-- end left/main -->

            <!-- Right/Aside: Comments -->
            <aside id="comments" class="lg:col-span-1 mt-0">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-900">Komentar <span id="commentCount" class="text-gray-500 text-base font-normal"></span></h3>
                    <button id="commentsToggle" type="button" class="p-2 rounded-md hover:bg-gray-100 text-gray-600" aria-label="Toggle comments">
                        <svg id="commentsChevron" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 011.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                    </button>
                </div>

                @auth
                    <!-- Comment Form - Only for authenticated users -->
                    <div class="bg-white rounded-xl shadow p-4 sm:p-6 mb-6">
                        <div class="flex items-center space-x-3 mb-4">
                            @if(auth()->user()->avatar)
                                <img src="{{ \App\Helpers\StorageHelper::getStorageUrl(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" />
                            @else
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                    {{ auth()->user()->name[0] }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-sm text-gray-500">Berikan komentar Anda</p>
                            </div>
                        </div>
                        <form id="commentForm" class="space-y-4">
                            @csrf
                            <div>
                                <textarea name="content" rows="3" required 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 resize-none"
                                          placeholder="Tulis komentar Anda di sini..."></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    <span class="hidden sm:inline">Kirim Komentar</span>
                                    <span class="sm:hidden">Kirim</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <!-- Login required message -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                        <div class="text-center">
                            <i class="fas fa-lock text-blue-500 text-2xl mb-3"></i>
                            <h4 class="text-lg font-semibold text-blue-800 mb-2">Login Diperlukan</h4>
                            <p class="text-blue-600 mb-4">Silakan login terlebih dahulu untuk dapat memberikan komentar dan like.</p>
                            <a href="{{ route('guest.login') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login Sekarang
                            </a>
                        </div>
                    </div>
                @endauth

                <!-- Comments List (sidebar) -->
                <div id="commentsList" class="space-y-4 pr-2">
                    <!-- Comments will be loaded here via JavaScript -->
                            </div>

                <!-- Loading indicator -->
                <div id="commentsLoading" class="text-center py-4">
                    <i class="fas fa-spinner fa-spin text-blue-500"></i>
                    <span class="ml-2 text-gray-600">Memuat komentar...</span>
                </div>
                
            </aside>

            @if($relatedGalleries->isNotEmpty())
                <div class="mt-12 lg:col-span-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold text-gray-900">Galeri Lainnya</h3>
                        <a href="{{ route('gallery') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat lainnya</a>
                    </div>
                    <div class="overflow-x-auto scrollbar-thin" data-aos="fade-up">
                        <div class="flex gap-4 snap-x snap-mandatory">
                            @foreach($relatedGalleries as $related)
                                <a href="{{ route('gallery.detail', $related->id) }}" class="shrink-0 w-64 sm:w-72 md:w-80 snap-start bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 block">
                                    <div class="relative">
                                        @if($related->image)
                                            <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($related->image) }}" alt="{{ $related->title }}" class="w-full h-40 object-cover">
                                        @else
                                            <div class="w-full h-40 bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center">
                                                <i class="fas fa-images text-white text-4xl"></i>
                                            </div>
                                        @endif
                                        <div class="absolute top-2 right-2">
                                            <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                                {{ $related->created_at->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h4 class="text-base md:text-lg font-bold mb-2 text-gray-800 line-clamp-2">{{ $related->title }}</h4>
                                        <span class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm mt-2">
                                            Lihat galeri <i class="fas fa-arrow-right ml-1"></i>
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Gallery Detail JavaScript loaded successfully');
    
    const galleryId = {{ $gallery->id }};
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    
    console.log('Gallery ID:', galleryId);
    console.log('Is Authenticated:', isAuthenticated);
    
    // Load comments first; favorite status will be initialized after functions are defined below
    loadComments();
    
    // Toggle comments expand/collapse (sidebar)
    const commentsListEl = document.getElementById('commentsList');
    const commentsToggleBtn = document.getElementById('commentsToggle');
    const commentsChevron = document.getElementById('commentsChevron');
    let commentsExpanded = false;

    function setCommentsExpanded(expanded){
        commentsExpanded = expanded;
        if (!commentsListEl) return;
        // Reset classes
        commentsListEl.classList.remove('max-h-32','overflow-hidden','max-h-[60vh]','overflow-y-auto');
        if (expanded){
            commentsListEl.classList.add('max-h-[60vh]','overflow-y-auto');
            if (commentsChevron) commentsChevron.style.transform = 'rotate(180deg)';
        } else {
            commentsListEl.classList.add('max-h-32','overflow-hidden');
            if (commentsChevron) commentsChevron.style.transform = 'rotate(0deg)';
        }

    // Related slider controls
    (function(){
        const slider = document.getElementById('relatedSlider');
        const prev = document.getElementById('relPrev');
        const next = document.getElementById('relNext');
        if (!slider || !prev || !next) return;
        prev.classList.remove('hidden');
        next.classList.remove('hidden');
        const step = 300; // px per click
        prev.addEventListener('click', () => slider.scrollBy({ left: -step, behavior: 'smooth' }));
        next.addEventListener('click', () => slider.scrollBy({ left: step, behavior: 'smooth' }));
    })();
    }

    if (commentsToggleBtn){
        commentsToggleBtn.addEventListener('click', function(){
            setCommentsExpanded(!commentsExpanded);
        });
    }

    // Initialize like count/status for everyone (guest sees count only)
    checkLikeStatus();

    // Only initialize interactive features for authenticated users
    if (isAuthenticated) {
        
        // Like button handler
        const likeBtn = document.getElementById('likeBtn');
        if (likeBtn) {
            console.log('Like button found, adding event listener');
            likeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Like button clicked');
                toggleLike();
            });
        } else {
            console.log('Like button not found');
        }

    // Favorite helpers
    function loadFavoriteStatus() {
        fetch(`/gallery/${galleryId}/favorite-status`)
            .then(r => r.json())
            .then(data => {
                if (data && data.success) {
                    updateFavoriteButton(data.favorited, data.favorite_count);
                }
            })
            .catch(err => console.error('Error favorite status:', err));
    }

    function loadFavoriteCountOnly() {
        fetch(`/gallery/${galleryId}/favorite-status`)
            .then(r => r.json())
            .then(data => {
                const favCount = document.getElementById('favCount');
                if (favCount && typeof data.favorite_count !== 'undefined') {
                    favCount.textContent = `(${data.favorite_count})`;
                }
            })
            .catch(() => {});
    }

    function toggleFavorite() {
        const favBtn = document.getElementById('favBtn');
        if (!favBtn) return;
        favBtn.disabled = true;
        fetch(`/gallery/${galleryId}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data && data.success) {
                updateFavoriteButton(data.favorited, data.favorite_count);
                showNotification(data.message || 'Berhasil', 'success');
            } else {
                showNotification((data && data.message) || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(() => showNotification('Terjadi kesalahan', 'error'))
        .finally(() => { favBtn.disabled = false; });
    }

    function updateFavoriteButton(favorited, count) {
        const favIcon = document.getElementById('favIcon');
        const favText = document.getElementById('favText');
        const favCount = document.getElementById('favCount');
        const favBtn = document.getElementById('favBtn');
        if (!favIcon || !favText || !favBtn) return;
        favIcon.className = favorited ? 'fas fa-bookmark mr-2' : 'far fa-bookmark mr-2';
        favText.textContent = favorited ? 'Tersimpan' : 'Simpan';
        favBtn.className = favorited
            ? 'inline-flex items-center text-blue-700 transition-colors px-3 py-2 rounded-lg bg-blue-50 hover:bg-blue-100'
            : 'inline-flex items-center text-gray-500 hover:text-blue-700 transition-colors px-3 py-2 rounded-lg hover:bg-blue-50';
        if (typeof count !== 'undefined' && favCount) favCount.textContent = `(${count})`;
    }
        
        // Comment form handler
        const commentForm = document.getElementById('commentForm');
        if (commentForm) {
            console.log('Comment form found, adding event listener');
            commentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Comment form submitted');
                submitComment();
            });
        } else {
            console.log('Comment form not found');
        }

        // Favorite button handler
        const favBtn = document.getElementById('favBtn');
        if (favBtn) {
            favBtn.addEventListener('click', function(e){
                e.preventDefault();
                toggleFavorite();
            });
        }
    }
    
    // Check like status
    function checkLikeStatus() {
        fetch(`/gallery/${galleryId}/like-status`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateLikeButton(data.liked, data.like_count);
                }
            })
            .catch(error => console.error('Error checking like status:', error));
    }
    
    // Toggle like
    function toggleLike() {
        const likeBtn = document.getElementById('likeBtn');
        likeBtn.disabled = true;
        
        console.log('Toggling like for gallery:', galleryId);
        
        fetch(`/gallery/${galleryId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Like response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Like response data:', data);
            if (data.success) {
                updateLikeButton(data.liked, data.like_count);
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Error toggling like:', error);
            showNotification('Terjadi kesalahan saat like/unlike', 'error');
        })
        .finally(() => {
            likeBtn.disabled = false;
        });
    }
    
    // Update like button (safe for guests)
    function updateLikeButton(liked, likeCount) {
        const likeBtn = document.getElementById('likeBtn');
        const likeCountSpan = document.getElementById('likeCount');
        if (likeCountSpan) likeCountSpan.textContent = `(${likeCount})`;

        if (!likeBtn) return; // guest: no interactive like button

        const likeIcon = likeBtn.querySelector('i');
        const likeText = document.getElementById('likeText');

        if (liked) {
            if (likeIcon) likeIcon.className = 'fas fa-heart mr-1';
            likeBtn.className = 'inline-flex items-center text-pink-600 hover:text-pink-700 transition-colors';
            if (likeText) likeText.textContent = 'Batalkan';
        } else {
            if (likeIcon) likeIcon.className = 'far fa-heart mr-1';
            likeBtn.className = 'inline-flex items-center text-gray-500 hover:text-pink-700 transition-colors';
            if (likeText) likeText.textContent = 'Suka';
        }
    }
    
    // Comments state (use var to avoid TDZ issues if referenced earlier)
    var allComments = [];
    var renderedCount = 0;

    // Load comments
    function loadComments() {
        console.log('Loading comments for gallery:', galleryId);
        
        // Test if we can reach the endpoint
        fetch(`/gallery/${galleryId}/comments`)
            .then(response => {
                console.log('Comments response status:', response.status);
                console.log('Comments response headers:', response.headers);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Comments data received:', data);
                if (data.success) {
                    allComments = Array.isArray(data.comments) ? data.comments : [];
                    console.log('Comments count:', allComments.length);
                    // Update count label
                    const countEl = document.getElementById('commentCount');
                    if (countEl) countEl.textContent = `(${allComments.length})`;
                    // Render all comments into scrollable panel
                    displayComments(allComments);
                } else {
                    console.error('Failed to load comments:', data.message);
                    document.getElementById('commentsLoading').innerHTML = 
                        '<p class="text-red-500">Gagal memuat komentar: ' + (data.message || 'Unknown error') + '</p>';
                }
            })
            .catch(error => {
                console.error('Error loading comments:', error);
                document.getElementById('commentsLoading').innerHTML = 
                    '<p class="text-red-500">Gagal memuat komentar: ' + error.message + '</p>';
            });
    }
    
    // Display comments (replace list)
    function displayComments(comments) {
        const commentsList = document.getElementById('commentsList');
        const loading = document.getElementById('commentsLoading');
        
        loading.style.display = 'none';
        
        if (comments.length === 0) {
            commentsList.innerHTML = '<p class="text-gray-500 text-center py-8">Belum ada komentar. Jadilah yang pertama berkomentar!</p>';
            return;
        }
        
        commentsList.innerHTML = comments.map(comment => `
            <div class="bg-white rounded-xl shadow p-4 ${comment.depth > 0 ? 'ml-4 sm:ml-8 border-l-4 border-blue-200' : ''}">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 space-y-2 sm:space-y-0">
                    <div class="flex items-center space-x-3">
                        ${comment.avatar_url ? `
                            <img src="${comment.avatar_url}" alt="${comment.name}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" />
                        ` : `
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                ${comment.name.charAt(0).toUpperCase()}
                            </div>
                        `}
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-800 truncate">${comment.name}</p>
                            <p class="text-xs text-gray-500"><span class="timeago" data-time="${comment.created_at_iso}">${comment.created_at}</span></p>
                        </div>
                    </div>
                    ${isAuthenticated ? `
                        <button onclick="replyToComment(${comment.id}, '${comment.name}')" 
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium px-2 py-1 rounded hover:bg-blue-50 transition-colors">
                            <i class="fas fa-reply mr-1"></i>Balas
                        </button>
                    ` : ''}
                </div>
                <p class="text-gray-700 ml-12 break-words">${comment.content}</p>
                
                <!-- Replies -->
                ${comment.replies && comment.replies.length > 0 ? `
                    <div class="mt-3 ml-8 sm:ml-12 space-y-3">
                        ${comment.replies.map(reply => `
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center space-x-3 mb-2">
                                    ${reply.avatar_url ? `
                                        <img src="${reply.avatar_url}" alt="${reply.name}" class="w-8 h-8 rounded-full object-cover flex-shrink-0" />
                                    ` : `
                                        <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                            ${reply.name.charAt(0).toUpperCase()}
                                        </div>
                                    `}
                                    <div class="min-w-0 flex-1">
                                        <p class="font-medium text-gray-800 text-sm truncate">${reply.name}</p>
                                        <p class="text-xs text-gray-500"><span class="timeago" data-time="${reply.created_at_iso}">${reply.created_at}</span></p>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-sm ml-11 break-words">${reply.content}</p>
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
            </div>
        `).join('');

        updateRelativeTimes();
        // Start collapsed like the sample, expand via chevron
        setCommentsExpanded(false);
    }

    // Append comments (add to existing list)
    function appendComments(comments) {
        const commentsList = document.getElementById('commentsList');
        const html = comments.map(comment => `
            <div class="bg-white rounded-xl shadow p-4 ${comment.depth > 0 ? 'ml-4 sm:ml-8 border-l-4 border-blue-200' : ''}">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2 space-y-2 sm:space-y-0">
                    <div class="flex items-center space-x-3">
                        ${comment.avatar_url ? `
                            <img src="${comment.avatar_url}" alt="${comment.name}" class="w-10 h-10 rounded-full object-cover flex-shrink-0" />
                        ` : `
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                ${comment.name.charAt(0).toUpperCase()}
                            </div>
                        `}
                        <div class="min-w-0 flex-1">
                            <p class="font-semibold text-gray-800 truncate">${comment.name}</p>
                            <p class="text-xs text-gray-500"><span class="timeago" data-time="${comment.created_at_iso}">${comment.created_at}</span></p>
                        </div>
                    </div>
                    ${isAuthenticated ? `
                        <button onclick="replyToComment(${comment.id}, '${comment.name}')" 
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium px-2 py-1 rounded hover:bg-blue-50 transition-colors">
                            <i class="fas fa-reply mr-1"></i>Balas
                        </button>
                    ` : ''}
                </div>
                <p class="text-gray-700 ml-12 break-words">${comment.content}</p>
                ${comment.replies && comment.replies.length > 0 ? `
                    <div class="mt-3 ml-8 sm:ml-12 space-y-3">
                        ${comment.replies.map(reply => `
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center space-x-3 mb-2">
                                    ${reply.avatar_url ? `
                                        <img src="${reply.avatar_url}" alt="${reply.name}" class="w-8 h-8 rounded-full object-cover flex-shrink-0" />
                                    ` : `
                                        <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white font-semibold text-sm flex-shrink-0">
                                            ${reply.name.charAt(0).toUpperCase()}
                                        </div>
                                    `}
                                    <div class="min-w-0 flex-1">
                                        <p class="font-medium text-gray-800 text-sm truncate">${reply.name}</p>
                                        <p class="text-xs text-gray-500"><span class="timeago" data-time="${reply.created_at_iso}">${reply.created_at}</span></p>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-sm ml-11 break-words">${reply.content}</p>
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
            </div>
        `).join('');
        commentsList.insertAdjacentHTML('beforeend', html);
        updateRelativeTimes();
    }
    
    // Submit comment
    function submitComment() {
        const form = document.getElementById('commentForm');
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        console.log('Submitting comment...');
        console.log('Form data:', Object.fromEntries(formData));
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
        
        fetch(`/gallery/${galleryId}/comment`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Comment response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Comment response data:', data);
            if (data.success) {
                form.reset();
                showNotification(data.message, 'success');
                loadComments(); // Reload comments
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Error submitting comment:', error);
            showNotification('Terjadi kesalahan saat mengirim komentar', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i><span class="hidden sm:inline">Kirim Komentar</span><span class="sm:hidden">Kirim</span>';
        });
    }
    
    // Reply to comment (global function)
    window.replyToComment = function(commentId, commenterName) {
        const form = document.getElementById('commentForm');
        const contentTextarea = form.querySelector('textarea[name="content"]');
        
        // Add reply indicator
        contentTextarea.value = `@${commenterName} `;
        contentTextarea.focus();
        
        // Add hidden field for parent_id
        let parentIdField = form.querySelector('input[name="parent_id"]');
        if (!parentIdField) {
            parentIdField = document.createElement('input');
            parentIdField.type = 'hidden';
            parentIdField.name = 'parent_id';
            form.appendChild(parentIdField);
        }
        parentIdField.value = commentId;
        
        // Scroll to form
        form.scrollIntoView({ behavior: 'smooth' });
    };
    
    // Show notification
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});
</script>
<script>
// Lightweight relative time updater without external libs
function updateRelativeTimes() {
    const elements = document.querySelectorAll('.timeago[data-time]');
    const now = new Date();
    elements.forEach(el => {
        const iso = el.getAttribute('data-time');
        if (!iso) return;
        const date = new Date(iso);
        const seconds = Math.floor((now - date) / 1000);
        const rtf = new Intl.RelativeTimeFormat('id', { numeric: 'auto' });

        const divisions = [
            { amount: 60, name: 'second' },
            { amount: 60, name: 'minute' },
            { amount: 24, name: 'hour' },
            { amount: 7, name: 'day' },
            { amount: 4.34524, name: 'week' },
            { amount: 12, name: 'month' },
            { amount: Number.POSITIVE_INFINITY, name: 'year' }
        ];

        let duration = Math.abs(seconds);
        let unit = 'second';
        let value = -seconds; // past times should be negative for rtf

        for (let i = 0; i < divisions.length; i++) {
            const division = divisions[i];
            if (duration < division.amount) {
                unit = division.name;
                break;
            }
            duration /= division.amount;
            value = value / division.amount;
        }

        // Round appropriately
        const rounded = Math.round(value);
        el.textContent = rtf.format(rounded, unit);
        el.title = date.toLocaleString('id-ID');
    });
}

// Refresh every minute
setInterval(updateRelativeTimes, 60000);
</script>
@endpush
