@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-block bg-blue-500/30 text-white text-sm font-semibold px-4 py-1 rounded-full mb-4 backdrop-blur-sm border border-blue-400/30">
                    <i class="fas fa-calendar-alt mr-2"></i> AGENDA SEKOLAH
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-shadow leading-tight">
                    Acara & Kegiatan <span class="text-yellow-300 relative inline-block">
                        Mendatang
                        <svg class="absolute -bottom-2 left-0 w-full" height="6" viewBox="0 0 200 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 3C50 0.5 150 0.5 200 3" stroke="#FBBF24" stroke-width="5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>
                <p class="text-xl mb-8 text-blue-100 leading-relaxed max-w-3xl mx-auto">
                    Temukan informasi lengkap tentang acara dan kegiatan yang akan datang di sekolah kami
                </p>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            @if($events->isEmpty())
                <div class="text-center py-16">
                    <div class="text-6xl text-gray-300 mb-4"><i class="fas fa-calendar-times"></i></div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Tidak ada acara mendatang</h3>
                    <p class="text-gray-600">Silakan kunjungi kembali halaman ini nanti untuk informasi acara terbaru.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 h-full flex flex-col group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="relative flex-shrink-0">
                                @if($event->image)
                                    <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-r from-blue-400 to-indigo-500 flex items-center justify-center">
                                        <i class="fas fa-calendar-day text-white text-5xl"></i>
                                    </div>
                                @endif
                                <div class="absolute top-3 right-3">
                                    <span class="bg-blue-600/90 text-white text-xs px-3 py-1 rounded-full shadow-md backdrop-blur-sm">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ $event->start_at->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-6 flex-grow flex flex-col">
                                <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-blue-600 transition-colors">
                                    {{ $event->title }}
                                </h3>
                                
                                <div class="space-y-3 mb-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-1">
                                            <i class="far fa-clock text-blue-500 w-5 text-center"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Waktu</p>
                                            <p class="text-sm text-gray-600">
                                                {{ $event->start_at->format('l, d F Y') }}
                                                @if($event->end_at && !$event->start_at->isSameDay($event->end_at))
                                                    <br>s/d {{ $event->end_at->format('l, d F Y') }}
                                                @endif
                                                @if($event->start_time)
                                                    <br>{{ $event->start_time }}
                                                    @if($event->end_time)
                                                         - {{ $event->end_time }} WIB
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    @if($event->location)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-1">
                                            <i class="fas fa-map-marker-alt text-red-400 w-5 text-center"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Lokasi</p>
                                            <p class="text-sm text-gray-600">{{ $event->location }}</p>
                                        </div>
                                    </div>
                                    @endif

                                    @if($event->description)
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mt-1">
                                            <i class="fas fa-info-circle text-green-500 w-5 text-center"></i>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">Deskripsi</p>
                                            <p class="text-sm text-gray-600 line-clamp-3">{{ strip_tags($event->description) }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                @if($event->registration_link)
                                <div class="mt-auto pt-4 border-t border-gray-100">
                                    <a href="{{ $event->registration_link }}" target="_blank" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection