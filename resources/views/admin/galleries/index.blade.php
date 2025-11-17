@extends('admin.layouts.app')

@section('title', 'Galeri Foto')

@section('content')
@php($pendingCount = \App\Models\GallerySubmission::where('status','pending')->count())
@if($pendingCount > 0)
    <div class="mb-4 px-4 py-3 rounded-lg border border-amber-200 bg-amber-50 text-amber-800 flex items-center justify-between">
        <div class="text-sm">
            <strong>{{ $pendingCount }}</strong> pengajuan galeri menunggu persetujuan.
        </div>
        <a href="{{ route('admin.gallery-submissions.index', ['status'=>'pending']) }}" class="inline-flex items-center text-sm px-3 py-1.5 rounded-md bg-amber-500 hover:bg-amber-600 text-white">
            Kelola Pengajuan <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            @if($kategoriSlug)
                Galeri {{ $kategoris->where('slug', $kategoriSlug)->first()->nama ?? '' }}
            @else
                Daftar Galeri Foto
            @endif
        </h2>
        <a href="{{ route('admin.galleries.create', $kategoriSlug ? ['kategori' => $kategoriSlug] : []) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Tambah Foto
        </a>
    </div>

    <!-- Kategori Navigasi -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.galleries.index') }}" 
               class="px-4 py-2 rounded-full text-sm font-medium {{ !$kategoriSlug ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua Kategori
            </a>
            @foreach($kategoris as $kategori)
                <a href="{{ route('admin.galleries.kategori', $kategori->slug) }}" 
                   class="px-4 py-2 rounded-full text-sm font-medium {{ $kategoriSlug == $kategori->slug ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $kategori->nama }}
                </a>
            @endforeach
        </div>
    </div>

    @if($galleries->isEmpty())
        <div class="border rounded-lg p-6 text-center text-gray-500">Belum ada galeri.</div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($galleries as $gallery)
                <div class="border rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
                    <a href="{{ route('admin.galleries.show', $gallery) }}" class="block relative group">
                        <img src="{{ $gallery->image ? \App\Helpers\StorageHelper::getStorageUrl($gallery->image) : 'https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=800&q=60' }}" alt="{{ $gallery->title }}" class="w-full h-48 object-cover group-hover:opacity-90 transition-opacity">
                        @if(!$gallery->is_published)
                            <span class="absolute top-2 right-2 bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Draft</span>
                        @endif
                    </a>
                    <div class="p-4">
                        <a href="{{ route('admin.galleries.show', $gallery) }}" class="block">
                            <h3 class="font-semibold text-gray-800 mb-2 hover:text-blue-600 transition-colors">{{ $gallery->title }}</h3>
                        </a>
                        @if($gallery->kategori)
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ $gallery->kategori->nama }}</span>
                        @endif
                        <p class="text-gray-500 text-sm mt-2 mb-3 line-clamp-2">{{ $gallery->description ?: 'Tidak ada deskripsi' }}</p>

                        <div class="flex items-center space-x-4 text-sm text-gray-600 mb-2">
                            <span class="inline-flex items-center"><i class="fas fa-user mr-1"></i>{{ $gallery->admin->name ?? 'Admin' }}</span>
                            <span class="inline-flex items-center text-pink-600"><i class="fas fa-heart mr-1"></i>{{ $gallery->likes()->count() }}</span>
                            <span class="inline-flex items-center text-gray-600"><i class="far fa-comment mr-1"></i>{{ $gallery->comments()->count() }}</span>
                            <span class="inline-flex items-center"><i class="far fa-calendar-alt mr-1"></i>{{ $gallery->created_at->format('d M Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <div class="flex items-center space-x-3 text-gray-600">
                                <a href="{{ route('admin.galleries.edit', array_merge([$gallery], $kategoriSlug ? ['kategori' => $kategoriSlug] : [])) }}" class="text-blue-600 hover:text-blue-800" title="Edit"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.galleries.toggle-publish', $gallery) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-{{ $gallery->is_published ? 'yellow' : 'green' }}-600 hover:text-{{ $gallery->is_published ? 'yellow' : 'green' }}-800" title="{{ $gallery->is_published ? 'Unpublish' : 'Publish' }}">
                                        <i class="fas fa-{{ $gallery->is_published ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                            </div>
                            <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $galleries->links() }}</div>
    @endif
</div>
@endsection