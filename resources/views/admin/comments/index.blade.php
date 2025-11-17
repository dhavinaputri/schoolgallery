@extends('admin.layouts.app')

@section('title', 'Kelola Komentar')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="bg-blue-600 text-white rounded-lg p-2 mr-3"><i class="fas fa-comments"></i></span>
                Kelola Komentar
            </h1>
            <p class="text-gray-500 mt-1">Moderasi komentar dari berita dan galeri</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-lg border border-blue-600 text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 flex items-center bg-green-50 text-green-700 border border-green-200 rounded-lg p-3">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-600" onclick="this.parentElement.style.display='none'"><i class="fas fa-times"></i></button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 flex items-center bg-red-50 text-red-700 border border-red-200 rounded-lg p-3">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="ml-auto text-red-600" onclick="this.parentElement.style.display='none'"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border border-gray-200 text-center">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-600">Total Komentar</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200 text-center">
            <div class="text-2xl font-bold text-orange-600">{{ $stats['pending'] }}</div>
            <div class="text-sm text-gray-600">Menunggu</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['approved'] }}</div>
            <div class="text-sm text-gray-600">Disetujui</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200 text-center">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['news'] }}</div>
            <div class="text-sm text-gray-600">Berita</div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-gray-200 text-center">
            <div class="text-2xl font-bold text-gray-800">{{ $stats['gallery'] }}</div>
            <div class="text-sm text-gray-600">Galeri</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-gray-100 rounded-xl p-4 mb-4 flex flex-col md:flex-row gap-3 items-center justify-between">
        <form method="GET" action="{{ route('admin.comments.index') }}" class="flex gap-3 w-full">
            <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Semua Tipe</option>
                <option value="news" {{ $type === 'news' ? 'selected' : '' }}>Berita</option>
                <option value="gallery" {{ $type === 'gallery' ? 'selected' : '' }}>Galeri</option>
            </select>
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Disetujui</option>
            </select>
        </form>
    </div>

    <!-- Comments List -->
    <div class="space-y-4">
        @forelse($comments as $comment)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-start gap-3 flex-1">
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                        @php
                            $email = $comment['email'] ?? null;
                            $gravatar = $email ? 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($email))).'?s=80&d=mp' : null;
                            $avatarUrl = $comment['user_avatar_url'] ?? null; // allow controller to pass avatar if available
                        @endphp
                        @if(!empty($avatarUrl))
                            <img src="{{ $avatarUrl }}" alt="avatar" class="w-full h-full object-cover">
                        @elseif($gravatar)
                            <img src="{{ $gravatar }}" alt="avatar" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($comment['name'], 0, 1)) }}
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-800">{{ $comment['name'] }}</span>
                            @if($comment['email'])
                                <span class="text-xs text-gray-500">{{ $comment['email'] }}</span>
                            @endif
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $comment['type'] === 'news' ? 'bg-purple-100 text-purple-700' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ $comment['type'] === 'news' ? 'Berita' : 'Galeri' }}
                            </span>
                            @if($comment['parent_id'])
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    <i class="fas fa-reply mr-1"></i>Balasan
                                </span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-600 mb-2">
                            <i class="fas fa-clock mr-1"></i>{{ $comment['created_at']->diffForHumans() }}
                            <span class="mx-2">•</span>
                            <i class="fas {{ $comment['type'] === 'news' ? 'fa-newspaper' : 'fa-images' }} mr-1"></i>
                            @if($comment['item_slug'])
                                <a href="{{ $comment['type'] === 'news' ? route('news.show', $comment['item_slug']) : route('gallery.show', $comment['item_slug']) }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ $comment['item_title'] }}
                                </a>
                            @else
                                <span class="text-gray-500">{{ $comment['item_title'] }}</span>
                            @endif
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-gray-700">
                            {{ $comment['content'] }}
                        </div>
                    </div>
                </div>
                <div class="ml-4">
                    @if($comment['is_approved'])
                        <span class="px-3 py-1.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200">
                            <i class="fas fa-check mr-1"></i>Disetujui
                        </span>
                    @else
                        <span class="px-3 py-1.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700 border border-orange-200">
                            <i class="fas fa-clock mr-1"></i>Menunggu
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100">
                @if(!$comment['is_approved'])
                <form action="{{ route('admin.comments.approve', [$comment['type'], $comment['id']]) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        <i class="fas fa-check mr-1"></i>Setujui
                    </button>
                </form>
                @endif
                <form action="{{ route('admin.comments.destroy', [$comment['type'], $comment['id']]) }}" method="POST" class="inline" onsubmit="return confirm('Hapus komentar ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1.5 bg-white text-red-600 border border-red-500 rounded-lg hover:bg-red-50 transition text-sm font-medium">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-dashed border-gray-300">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 mb-4">
                <i class="fas fa-comments text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Komentar</h3>
            <p class="text-gray-600">Komentar dari berita dan galeri akan muncul di sini</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $comments->links() }}
    </div>
</div>
@endsection
