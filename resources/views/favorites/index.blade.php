@extends('layouts.app')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Favorit Saya</h1>
                <p class="text-gray-600 text-sm md:text-base">Kumpulan foto yang kamu simpan.</p>
            </div>
            <a href="{{ route('gallery') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm md:text-base">
                <i class="fas fa-images mr-2"></i> Lihat Semua Galeri
            </a>
        </div>

        @if($favorites->count() === 0)
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <div class="text-4xl text-gray-300 mb-3"><i class="far fa-bookmark"></i></div>
                <p class="text-gray-700 font-medium">Belum ada foto yang disimpan.</p>
                <p class="text-gray-500 text-sm mt-1">Klik tombol "Simpan" pada foto untuk menambahkannya ke favorit.</p>
                <a href="{{ route('gallery') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Jelajahi Galeri
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6">
                @foreach($favorites as $fav)
                    @php($g = $fav->gallery)
                    @if($g)
                        <a href="{{ route('gallery.detail', $g->id) }}" class="group block bg-white rounded-xl overflow-hidden border border-gray-200 hover:shadow-md transition-all">
                            <div class="relative w-full" style="aspect-ratio: 16/10;">
                                <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($g->image) }}" alt="{{ $g->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute top-2 right-2">
                                    <span class="bg-blue-600 text-white text-[10px] md:text-xs px-2 py-0.5 md:px-2 md:py-1 rounded-full">Favorit</span>
                                </div>
                            </div>
                            <div class="p-2 md:p-3">
                                <h3 class="text-gray-800 font-semibold line-clamp-2 text-sm md:text-base">{{ $g->title }}</h3>
                                <p class="text-[11px] md:text-xs text-gray-500 mt-1">Disimpan {{ $fav->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>

            <div class="mt-6">
                {{ $favorites->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
