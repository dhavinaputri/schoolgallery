@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-block bg-blue-500/30 text-white text-sm font-semibold px-4 py-1 rounded-full mb-4 backdrop-blur-sm border border-blue-400/30">
                    <i class="fas fa-newspaper mr-2"></i> BERITA SEKOLAH
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-shadow leading-tight">
                    Berita & Informasi <span class="text-yellow-300 relative inline-block">
                        Terkini
                        <svg class="absolute -bottom-2 left-0 w-full" height="6" viewBox="0 0 200 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 3C50 0.5 150 0.5 200 3" stroke="#FBBF24" stroke-width="5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>
                <p class="text-xl mb-8 text-blue-100 leading-relaxed max-w-3xl mx-auto">
                    Temukan informasi terbaru tentang kegiatan, prestasi, dan pengumuman penting dari {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}
                </p>
            </div>
        </div>
    </section>
    <script>
        (function(){
            const form = document.querySelector('form[action="{{ route('news') }}"]');
            if (!form) return;
            const input = form.querySelector('input[name="search"]');
            let t;
            input && input.addEventListener('input', function(){
                clearTimeout(t);
                t = setTimeout(()=>{ form.submit(); }, 300);
            });
        })();
    </script>

    <!-- Search Section -->
    <section class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <form action="{{ route('news') }}" method="GET" class="mb-0" id="newsSearchForm">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center gap-2">
                        <span>Cari</span>
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- News + Sidebar Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            @if(request('search'))
                <div class="mb-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-800">Hasil pencarian: "{{ request('search') }}"</h3>
                    <p class="text-gray-600">{{ $news->total() }} berita ditemukan</p>
                </div>
            @endif

            @if($news->isEmpty())
                <div class="text-center py-16">
                    <div class="text-6xl text-gray-300 mb-4"><i class="fas fa-newspaper"></i></div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak ada berita ditemukan</h3>
                    <p class="text-gray-600">Silakan coba dengan kata kunci lain atau kembali lagi nanti.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-8">
                    <!-- Main Content -->
                    <div class="space-y-8">
                        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 gap-3 md:gap-6">
                            @foreach($news as $item)
                                <a href="{{ route('news.detail', $item->slug) }}" class="group block h-full">
                                    <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 h-full flex flex-col border border-gray-100">
                                        <div class="relative flex-shrink-0">
                                            @if($item->image)
                                                <img 
                                                    src="{{ \App\Helpers\StorageHelper::getStorageUrl($item->image) }}" 
                                                    alt="{{ $item->title }}" 
                                                    class="w-full h-36 md:h-48 object-cover transition-transform duration-300 group-hover:scale-105"
                                                    loading="lazy"
                                                >
                                            @else
                                                <div class="w-full h-36 md:h-48 bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center">
                                                    <i class="fas fa-newspaper text-white text-5xl"></i>
                                                </div>
                                            @endif
                                            <div class="absolute top-3 right-3">
                                                <span class="bg-blue-600/90 text-white text-xs px-3 py-1 rounded-full shadow-md backdrop-blur-sm">
                                                    <i class="far fa-calendar-alt mr-1"></i> {{ $item->created_at->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="p-4 md:p-5 flex-grow flex flex-col">
                                            @if($item->newsCategory)
                                                <div class="mb-2">
                                                    <span class="inline-block bg-indigo-100 text-indigo-800 text-[10px] md:text-xs px-2 py-0.5 md:px-2.5 md:py-1 rounded-full font-medium">
                                                        {{ $item->newsCategory->name }}
                                                    </span>
                                                </div>
                                            @endif
                                            <h3 class="text-sm md:text-lg font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                                {{ $item->title }}
                                            </h3>
                                            <p class="text-gray-600 text-xs md:text-sm mb-3 md:mb-4 line-clamp-3">
                                                {!! Str::limit(strip_tags($item->content), 120) !!}
                                            </p>
                                            <div class="mt-auto pt-2 md:pt-3">
                                                <span class="inline-flex items-center text-blue-600 font-medium text-xs md:text-sm group-hover:underline">
                                                    Baca selengkapnya
                                                    <i class="fas fa-arrow-right ml-2 text-xs mt-0.5"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-12">
                            {{ $news->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection