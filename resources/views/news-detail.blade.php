@extends('layouts.app')

@section('content')
    <!-- News Detail Hero -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                <h1 class="text-3xl md:text-4xl font-bold mb-4 text-shadow leading-tight max-w-4xl mx-auto">
                    {{ $news->title }}
                </h1>
                <div class="flex items-center justify-center gap-4 text-sm text-blue-200">
                    <span><i class="far fa-calendar-alt mr-1"></i> {{ $news->created_at->format('d M Y') }}</span>
                    <span><i class="far fa-user mr-1"></i> Admin</span>
                </div>
            </div>
        </div>
    </section>

    <!-- News Content + Sidebar -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 bg-white rounded-xl overflow-hidden shadow-lg mb-8" data-aos="fade-up">
                @if($news->image)
                    <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($news->image) }}" alt="{{ $news->title }}" class="w-full h-auto object-cover">
                @else
                    <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" alt="{{ $news->title }}" class="w-full h-auto object-cover">
                @endif
                
                <div class="p-8">
                    @php
                        $shareUrl = urlencode(request()->fullUrl());
                        $shareText = urlencode($news->title);
                    @endphp
                    @if($news->newsCategory)
                        <div class="mb-4">
                            <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-3 py-1 rounded-full font-semibold">
                                {{ $news->newsCategory->name }}
                            </span>
                        </div>
                    @endif
                    <div class="flex items-center flex-wrap gap-4 text-sm text-gray-500 mb-6">
                        <span class="inline-flex items-center"><i class="far fa-calendar-alt mr-2 text-gray-400"></i>{{ $news->created_at->format('d M Y') }}</span>
                        <span class="inline-flex items-center"><i class="far fa-user mr-2 text-gray-400"></i>Admin</span>
                    </div>
                    <div class="prose prose-lg max-w-3xl mx-auto leading-relaxed text-gray-800 news-content">
                        {!! $news->content !!}
                    </div>
                    <div class="border-t mt-8 pt-6 flex items-center gap-3">
                        <span class="text-gray-700 font-medium">Bagikan:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" class="bg-blue-600 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" rel="noopener" class="bg-blue-400 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener" class="bg-green-600 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-green-700 transition-colors">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener" class="bg-blue-800 text-white w-9 h-9 rounded-full flex items-center justify-center hover:bg-blue-900 transition-colors">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>


            <!-- Sidebar -->
<aside class="lg:col-span-4 space-y-6">
    <!-- Comments Section -->
    <div id="comments" class="bg-white rounded-xl shadow-lg p-6 sticky top-24" style="max-height: calc(100vh - 120px); overflow-y: auto;">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Komentar</h3>
        @auth
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <div class="flex items-center space-x-3 mb-4">
                    @if(auth()->user()->avatar)
                        <img src="{{ \App\Helpers\StorageHelper::getStorageUrl(auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-10 h-10 rounded-full object-cover" />
                    @else
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500">Berikan komentar Anda</p>
                    </div>
                </div>
                <form id="newsCommentForm" class="space-y-4">
                    @csrf
                    <div>
                        <textarea name="content" rows="3" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500"
                                placeholder="Tulis komentar Anda di sini..."></textarea>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                <div class="text-center">
                    <i class="fas fa-lock text-blue-500 text-2xl mb-3"></i>
                    <h4 class="text-lg font-semibold text-blue-800 mb-2">Login Diperlukan</h4>
                    <p class="text-blue-600 mb-4">Silakan login terlebih dahulu untuk dapat memberikan komentar.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login Sekarang
                    </a>
                </div>
            </div>
        @endauth

        <div id="newsCommentsList" class="space-y-4">
            <!-- Comments will be loaded here via JavaScript -->
        </div>
        <!-- Loading indicator -->
        <div id="newsCommentsLoading" class="text-center py-4">
            <i class="fas fa-spinner fa-spin text-blue-500"></i>
            <span class="ml-2 text-gray-600">Memuat komentar...</span>
        </div>
    </div>

</aside>

            </div>
        </div>
    </section>

    <!-- Related News -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-6" data-aos="fade-up">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Berita Terkait</h2>
                    <p class="text-gray-600 hidden sm:block">Baca juga berita lainnya dari {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}</p>
                </div>
                <a href="{{ route('news') }}" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-800 transition-colors">
                    Lihat semua berita <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <div class="overflow-x-auto scrollbar-thin" data-aos="fade-up">
                <div class="flex gap-4 snap-x snap-mandatory">
                    @forelse($relatedNews as $item)
                        <a href="{{ route('news.detail', $item->slug) }}" class="shrink-0 w-64 sm:w-72 md:w-80 snap-start bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 block">
                            <div class="relative">
                                @if($item->image)
                                    <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($item->image) }}" alt="{{ $item->title }}" class="w-full h-40 object-cover">
                                @else
                                    <img src="https://images.unsplash.com/photo-1546410531-bb4caa6b424d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80" alt="{{ $item->title }}" class="w-full h-40 object-cover">
                                @endif
                                <div class="absolute top-2 right-2">
                                    <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                        {{ $item->created_at->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-base md:text-lg font-bold mb-2 text-gray-800 line-clamp-2">{{ $item->title }}</h3>
                                <span class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm mt-2">
                                    Baca selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-8 w-full">
                            <p class="text-gray-600">Tidak ada berita terkait.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const newsId = {{ $news->id }};
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    
    // Only initialize for authenticated users
    if (isAuthenticated) {
        // Load comments
        loadNewsComments();
        
        // Comment form handler
        const newsCommentForm = document.getElementById('newsCommentForm');
        if (newsCommentForm) {
            newsCommentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitNewsComment();
            });
        }
    } else {
        // Load comments for display only
        loadNewsComments();
    }
    // Bookmark feature removed per request
    
    // Load news comments
    function loadNewsComments() {
        fetch(`/news/${newsId}/comments`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayNewsComments(data.comments);
                }
            })
            .catch(error => {
                console.error('Error loading news comments:', error);
                document.getElementById('newsCommentsLoading').innerHTML = 
                    '<p class="text-red-500">Gagal memuat komentar</p>';
            });
    }
    
    // Display news comments
    function displayNewsComments(comments) {
        const commentsList = document.getElementById('newsCommentsList');
        const loading = document.getElementById('newsCommentsLoading');
        
        loading.style.display = 'none';
        
        if (comments.length === 0) {
            commentsList.innerHTML = '<p class="text-gray-500 text-center py-8">Belum ada komentar. Jadilah yang pertama berkomentar!</p>';
            return;
        }
        
        commentsList.innerHTML = comments.map(comment => `
            <div class="bg-white rounded-xl shadow p-4 ${comment.depth > 0 ? 'ml-8 border-l-4 border-blue-200' : ''}">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-3">
                        ${comment.avatar_url ? `
                            <img src="${comment.avatar_url}" alt="${comment.name}" class="w-10 h-10 rounded-full object-cover" />
                        ` : `
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                ${comment.name.charAt(0).toUpperCase()}
                            </div>
                        `}
                        <div>
                            <p class="font-semibold text-gray-800">${comment.name}</p>
                            <p class="text-xs text-gray-500"><span class="timeago" data-time="${comment.created_at_iso}">${comment.created_at}</span></p>
                        </div>
                    </div>
                    ${isAuthenticated ? `
                        <button onclick="replyToNewsComment(${comment.id}, '${comment.name}')" 
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-reply mr-1"></i>Balas
                        </button>
                    ` : ''}
                </div>
                <p class="text-gray-700 ml-12">${comment.content}</p>
                
                <!-- Replies -->
                ${comment.replies && comment.replies.length > 0 ? `
                    <div class="mt-3 ml-12 space-y-3">
                        ${comment.replies.map(reply => `
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center space-x-3 mb-2">
                                    ${reply.avatar_url ? `
                                        <img src="${reply.avatar_url}" alt="${reply.name}" class="w-8 h-8 rounded-full object-cover" />
                                    ` : `
                                        <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                            ${reply.name.charAt(0).toUpperCase()}
                                        </div>
                                    `}
                                    <div>
                                        <p class="font-medium text-gray-800 text-sm">${reply.name}</p>
                                        <p class="text-xs text-gray-500"><span class="timeago" data-time="${reply.created_at_iso}">${reply.created_at}</span></p>
                                    </div>
                                </div>
                                <p class="text-gray-700 text-sm ml-11">${reply.content}</p>
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
            </div>
        `).join('');

        updateRelativeTimes();
    }
    
    // Submit news comment
    function submitNewsComment() {
        const form = document.getElementById('newsCommentForm');
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
        
        fetch(`/news/${newsId}/comment`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                form.reset();
                showNotification(data.message, 'success');
                loadNewsComments(); // Reload comments
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(error => {
            console.error('Error submitting news comment:', error);
            showNotification('Terjadi kesalahan', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Kirim Komentar';
        });
    }
    
    // Reply to news comment (global function)
    window.replyToNewsComment = function(commentId, commenterName) {
        const form = document.getElementById('newsCommentForm');
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

        const rounded = Math.round(value);
        el.textContent = rtf.format(rounded, unit);
        el.title = date.toLocaleString('id-ID');
    });
}

setInterval(updateRelativeTimes, 60000);
</script>
@push('styles')
<style>
    .news-content p {
        text-align: justify;
        text-indent: 1.5em;
        margin-bottom: 1em;
    }
</style>
@endpush
@endpush