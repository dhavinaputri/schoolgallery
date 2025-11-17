@extends('admin.layouts.app')

@section('title', $gallery->title)

@section('content')
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <!-- Header Actions intentionally removed to avoid duplicate back button (layout already has it) -->

    <!-- Main Image -->
    <div class="bg-gray-900">
        <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($gallery->image) }}" alt="{{ $gallery->title }}" class="w-full h-auto object-cover" style="max-height: 600px; object-fit: contain;">
    </div>

    <!-- Action Buttons Below Image -->
    <div class="px-6 py-4 bg-gray-50 border-b flex justify-center space-x-3">
        <a href="{{ route('admin.galleries.edit', $gallery) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors inline-flex items-center">
            <i class="fas fa-edit mr-2"></i> Edit
        </a>
        <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors inline-flex items-center">
                <i class="fas fa-trash mr-2"></i> Hapus
            </button>
        </form>
    </div>

    <!-- Photo Details -->
    <div class="p-6">
        <!-- Title & Status -->
        <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $gallery->title }}</h1>
                @if($gallery->kategori)
                    <span class="inline-block bg-blue-100 text-blue-800 text-sm px-3 py-1 rounded-full">
                        {{ $gallery->kategori->nama }}
                    </span>
                @endif
            </div>
            @if($gallery->is_published)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-1"></i> Published
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    <i class="fas fa-clock mr-1"></i> Draft
                </span>
            @endif
        </div>

        <!-- Stats Row - Clickable Buttons -->
        <div class="flex flex-wrap items-center gap-3 mb-6 pb-6 border-b">
            <button onclick="toggleSection('likes')" class="inline-flex items-center px-4 py-2 rounded-lg bg-pink-50 text-pink-700 font-medium hover:bg-pink-100 transition-colors cursor-pointer">
                <i class="fas fa-heart mr-2"></i>
                <span class="text-lg">{{ $gallery->likes()->count() }}</span>
                <span class="ml-1 text-sm">Suka</span>
            </button>
            <button onclick="toggleSection('favorites')" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-50 text-blue-700 font-medium hover:bg-blue-100 transition-colors cursor-pointer">
                <i class="far fa-bookmark mr-2"></i>
                <span class="text-lg">{{ $gallery->favorites()->count() }}</span>
                <span class="ml-1 text-sm">Disimpan</span>
            </button>
            <button onclick="toggleSection('comments')" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-50 text-gray-700 font-medium hover:bg-gray-100 transition-colors cursor-pointer">
                <i class="far fa-comment mr-2"></i>
                <span class="text-lg">{{ $gallery->comments()->count() }}</span>
                <span class="ml-1 text-sm">Komentar</span>
            </button>
            <button onclick="toggleSection('views')" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-50 text-indigo-700 font-medium hover:bg-indigo-100 transition-colors cursor-pointer">
                <i class="far fa-eye mr-2"></i>
                <span class="text-lg">{{ $gallery->view_count ?? 0 }}</span>
                <span class="ml-1 text-sm">Dilihat</span>
            </button>
        </div>

        <!-- Description & Meta -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Deskripsi</h3>
                <p class="text-gray-700 leading-relaxed">{{ $gallery->description ?? 'Tidak ada deskripsi' }}</p>
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Ditambahkan oleh</p>
                    <p class="text-gray-900">{{ $gallery->admin->name ?? 'Admin' }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Tanggal dibuat</p>
                    <p class="text-gray-900">{{ $gallery->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Terakhir diperbarui</p>
                    <p class="text-gray-900">{{ $gallery->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Favorites Section -->
    <div id="section-favorites" class="px-6 pb-6 hidden">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="far fa-bookmark text-blue-600 mr-2"></i>
            Disimpan oleh ({{ $gallery->favorites()->count() }})
        </h3>
        <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
            @forelse($gallery->favorites()->with('user')->latest()->get() as $fav)
                <div class="border-b border-gray-200 last:border-0 p-4 flex items-center justify-between hover:bg-white transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-blue-100 flex items-center justify-center text-blue-600 font-bold flex-shrink-0">
                            @if(optional($fav->user)->avatar)
                                <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($fav->user->avatar) }}" alt="{{ $fav->user->name }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($fav->user->name ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $fav->user->name ?? 'User Terhapus' }}</p>
                            <p class="text-xs text-gray-500">{{ $fav->user->email ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">
                            <i class="far fa-clock mr-1"></i>{{ $fav->created_at->diffForHumans() }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $fav->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <i class="far fa-bookmark text-4xl text-gray-300 mb-2"></i>
                    <p>Belum ada yang menyimpan foto ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Likes Section -->
    <div id="section-likes" class="px-6 pb-6 hidden">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-heart text-pink-600 mr-2"></i>
            Disukai oleh ({{ $gallery->likes()->count() }})
        </h3>
        <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
            @forelse($gallery->likes()->with('user')->latest()->get() as $like)
                <div class="border-b border-gray-200 last:border-0 p-4 flex items-center justify-between hover:bg-white transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-pink-100 flex items-center justify-center text-pink-600 font-bold flex-shrink-0">
                            @if(optional($like->user)->avatar)
                                <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($like->user->avatar) }}" alt="{{ $like->user->name }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($like->user->name ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $like->user->name ?? 'User Terhapus' }}</p>
                            <p class="text-xs text-gray-500">{{ $like->user->email ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">
                            <i class="far fa-clock mr-1"></i>{{ $like->created_at->diffForHumans() }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $like->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <i class="far fa-heart text-4xl text-gray-300 mb-2"></i>
                    <p>Belum ada yang menyukai foto ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Comments Section -->
    <div id="section-comments" class="px-6 pb-6 hidden">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-comments text-gray-600 mr-2"></i>
            Komentar ({{ $gallery->comments()->count() }})
        </h3>
        <div class="space-y-3">
            @forelse($gallery->comments as $comment)
                <div class="border rounded-lg p-4 flex items-start justify-between hover:bg-gray-50 transition">
                    <div class="flex items-start gap-3 flex-1">
                        @php($__user = $comment->email ? \App\Models\User::where('email', $comment->email)->first() : null)
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                            @if($__user && $__user->avatar)
                                <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($__user->avatar) }}" alt="{{ $comment->name }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($comment->name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <p class="font-medium text-gray-900">{{ $comment->name }}</p>
                                @if(!$comment->is_approved)
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                        <i class="fas fa-clock mr-1"></i>Menunggu
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mb-2">{{ $comment->created_at->format('d M Y H:i') }}</p>
                            <p class="text-gray-700">{{ $comment->content }}</p>
                        </div>
                    </div>
                    <form action="{{ route('admin.galleries.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            @empty
                <div class="border border-dashed border-gray-300 rounded-lg p-8 text-center text-gray-500">
                    <i class="far fa-comments text-4xl text-gray-300 mb-2"></i>
                    <p>Belum ada komentar.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Views Info Section (Static - no user list) -->
    <div id="section-views" class="px-6 pb-6 hidden">
        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-8 text-center">
            <i class="far fa-eye text-5xl text-indigo-400 mb-3"></i>
            <p class="text-2xl font-bold text-indigo-900">{{ $gallery->view_count ?? 0 }}</p>
            <p class="text-sm text-indigo-700 mt-1">Total tayangan foto ini</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleSection(section) {
    const sectionId = 'section-' + section;
    const element = document.getElementById(sectionId);
    
    if (element) {
        // Toggle visibility
        if (element.classList.contains('hidden')) {
            // Hide all sections first
            document.querySelectorAll('[id^="section-"]').forEach(el => {
                el.classList.add('hidden');
            });
            // Show clicked section
            element.classList.remove('hidden');
            // Smooth scroll to section
            element.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            // Hide if already visible
            element.classList.add('hidden');
        }
    }
}
</script>
@endpush

@endsection