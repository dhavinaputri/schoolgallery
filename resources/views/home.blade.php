@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-24" id="parallax-container">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 text-center md:text-left mb-8 md:mb-0" data-aos="fade-right" data-aos-duration="1000">
                    <span class="inline-block bg-blue-500/30 text-white text-sm font-semibold px-4 py-1 rounded-full mb-4 backdrop-blur-sm border border-blue-400/30">
                        <i class="fas fa-graduation-cap mr-2"></i> SELAMAT DATANG
                    </span>
                    <h2 class="text-4xl md:text-5xl font-bold mb-6 text-shadow leading-tight">
                        Membangun Masa Depan di <span class="text-yellow-300 relative inline-block">
                            {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}
                            <svg class="absolute -bottom-2 left-0 w-full" height="6" viewBox="0 0 200 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 3C50 0.5 150 0.5 200 3" stroke="#FBBF24" stroke-width="5" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </h2>
                    @if($schoolProfile->description)
                        <p class="text-xl mb-8 text-blue-100 leading-relaxed">{{ $schoolProfile->description }}</p>
                    @else
                        <p class="text-xl mb-8 text-blue-100 leading-relaxed">Kami menyediakan pendidikan berkualitas dengan fasilitas modern dan lingkungan belajar yang kondusif untuk mengembangkan potensi setiap siswa.</p>
                    @endif
                    <div class="flex flex-row flex-nowrap justify-center md:justify-start gap-3">
                        <a href="{{ route('gallery') }}" class="btn-hover bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg flex items-center whitespace-nowrap">
                            <i class="fas fa-images mr-2"></i> Lihat Galeri
                        </a>
                        <a href="{{ route('news') }}" class="btn-hover bg-blue-600 border-2 border-blue-500 text-white px-6 py-3 rounded-lg font-semibold shadow-lg flex items-center whitespace-nowrap">
                            <i class="fas fa-newspaper mr-2"></i> Baca Berita
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                    <div class="relative rounded-xl overflow-hidden shadow-2xl card-shine">
                        <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                             alt="School Image" class="w-full h-64 md:h-96 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900 via-blue-900/50 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6">
                            <span class="bg-yellow-500 text-blue-900 px-4 py-2 rounded-full text-sm font-bold shadow-lg flex items-center">
                                <i class="fas fa-star mr-2"></i> Pendidikan Berkualitas
                            </span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="bg-white/80 backdrop-blur-sm text-blue-800 px-3 py-1 rounded-lg text-xs font-medium shadow-lg">
                                <i class="fas fa-award text-yellow-500 mr-1"></i> Terakreditasi A
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <!-- Latest News -->
    <section class="py-20 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-100 rounded-full opacity-30 -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-yellow-100 rounded-full opacity-30 -ml-32 -mb-32"></div>
        
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="flex flex-col items-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-4 py-1 rounded-full mb-3 shadow-sm flex items-center">
                    <i class="fas fa-fire mr-2"></i> TRENDING
                </span>
                <h3 class="text-3xl md:text-4xl font-bold text-center mb-4 relative">
                    <span class="relative z-10 text-gradient">Berita Terpopuler</span>
                    <span class="absolute bottom-1 left-0 w-full h-3 bg-yellow-200 opacity-50 z-0"></span>
                </h3>
                <div class="w-24 h-1 bg-blue-600 rounded-full mb-6"></div>
                <p class="text-gray-600 text-center max-w-2xl">Berita dan artikel paling banyak dibaca oleh pengunjung website sekolah kami</p>
            </div>
            
            <div class="grid grid-cols-1 gap-8">
                @forelse($latestNews as $index => $news)
                    @if($index < 2)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover card-shine border border-gray-100 flex flex-col md:flex-row" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                        <div class="relative md:w-1/4 image-hover">
                            <div class="card-shine-effect absolute inset-0 z-0"></div>
                            <div class="skeleton skeleton-image w-full h-56 md:h-full"></div>
                            <div class="content-loading opacity-0 transition-opacity duration-500">
                                @if($news->image)
                                    <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($news->image) }}" alt="{{ $news->title }}" class="w-full h-56 md:h-full object-cover absolute top-0 left-0">
                                @else
                                    <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="School News" class="w-full h-56 md:h-full object-cover absolute top-0 left-0">
                                @endif
                            </div>
                        </div>
                        <div class="p-6 md:w-3/4">
                            <div class="skeleton skeleton-text w-3/4 mb-3"></div>
                            <div class="skeleton skeleton-text w-full"></div>
                            <div class="skeleton skeleton-text w-full"></div>
                            <div class="skeleton skeleton-text w-2/3 mb-5"></div>
                            <div class="content-loading opacity-0 transition-opacity duration-500">
                                <div class="flex items-center mb-3">
                                    <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md flex items-center mr-3">
                                        <i class="far fa-calendar-alt mr-1"></i> {{ $news->published_at->format('d M Y') }}
                                    </span>
                                    <span class="text-sm text-gray-500 flex items-center">
                                        <i class="far fa-eye mr-1"></i> {{ rand(100, 999) }} dilihat
                                    </span>
                                </div>
                                <h4 class="text-xl font-bold mb-3 text-gray-800 line-clamp-2 hover:text-blue-600 transition-colors duration-300">{{ $news->title }}</h4>
                                <p class="text-gray-600 mb-5 line-clamp-3">{{ Str::limit(strip_tags($news->content), 150) }}</p>
                                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-2">
                                            <i class="fas fa-user-edit"></i>
                                        </div>
                                        <span class="text-sm text-gray-700">Admin</span>
                                    </div>
                                    <a href="{{ route('news.detail', $news->slug) }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center group card-shine bg-blue-50 px-4 py-2 rounded-lg hover:bg-blue-100 transition-colors duration-300">
                                        Baca Selengkapnya 
                                        <i class="fas fa-arrow-right ml-1 text-xs transition-transform duration-300 group-hover:translate-x-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @empty
                    <div class="col-span-3 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-12 text-center shadow-md border border-blue-100" data-aos="fade-up">
                        <div class="flex flex-col items-center">
                            <div class="bg-blue-100 p-5 rounded-full mb-6">
                                <i class="fas fa-newspaper text-5xl text-blue-500"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-gray-700 mb-4">Belum Ada Berita</h4>
                            <p class="text-gray-600 mb-4 max-w-md mx-auto">Berita dan informasi terpopuler akan segera ditampilkan di sini. Kunjungi kembali halaman ini untuk mendapatkan update terbaru.</p>
                            <div class="w-16 h-1 bg-blue-400 rounded-full my-4"></div>
                            <p class="text-gray-500 italic">"Pendidikan adalah senjata paling ampuh untuk mengubah dunia"</p>
                        </div>
                    </div>
                @endforelse
            </div>
            
            @if($latestNews->count() > 0)
                <div class="text-center mt-12" data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ route('news') }}" class="btn-hover inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-md">
                        <span>Lihat Semua Berita</span>
                        <i class="fas fa-arrow-right ml-2 transition-transform duration-300 group-hover:translate-x-1"></i>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Gallery Preview -->
    <section class="py-24 bg-gray-50 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-white to-transparent"></div>
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-yellow-300 rounded-full opacity-20 blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-blue-300 rounded-full opacity-20 blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full h-full bg-pattern opacity-5"></div>
        
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="flex flex-col items-center mb-16" data-aos="fade-up" data-aos-duration="800">
                <span class="bg-indigo-100 text-indigo-800 text-sm font-semibold px-4 py-1 rounded-full mb-3 shadow-sm flex items-center">
                    <i class="fas fa-camera mr-2"></i> KOLEKSI VISUAL
                </span>
                <h3 class="text-3xl md:text-4xl font-bold text-center mb-4 relative">
                    <span class="relative z-10 text-gradient">Galeri Foto</span>
                    <span class="absolute bottom-1 left-0 w-full h-3 bg-indigo-200 opacity-50 z-0"></span>
                </h3>
                <div class="w-24 h-1 bg-indigo-600 rounded-full mb-6"></div>
                <p class="text-gray-600 text-center max-w-2xl">Jelajahi koleksi foto kegiatan dan momen berharga di sekolah kami yang menggambarkan kehidupan akademik dan prestasi siswa</p>
            </div>
            
            @if($featuredGalleries->count() > 0)
                <div class="overflow-x-auto">
                    <div class="flex gap-5 md:grid md:grid-cols-4 md:gap-6">
                        @foreach($featuredGalleries as $index => $gallery)
                            <a href="{{ route('gallery.detail', $gallery->id) }}" class="group block w-72 md:w-auto flex-shrink-0 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow card-shine" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                                <div class="card-shine-effect"></div>
                                <div class="relative">
                                    <div class="w-full" style="aspect-ratio: 16 / 9;">
                                        @if($gallery->image)
                                            <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($gallery->image) }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                        @else
                                            @php
                                                $placeholderImages = [
                                                    'https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=1000&q=80',
                                                    'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1000&q=80',
                                                    'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1000&q=80',
                                                    'https://images.unsplash.com/photo-1627556704302-624286467c65?auto=format&fit=crop&w=1000&q=80'
                                                ];
                                            @endphp
                                            <img src="{{ $placeholderImages[$index % count($placeholderImages)] }}" alt="{{ $gallery->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                        @endif
                                    </div>
                                    <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/70 to-transparent">
                                        <h4 class="text-white font-semibold text-base line-clamp-1">{{ $gallery->title }}</h4>
                                        <p class="text-gray-200 text-xs">{{ \Carbon\Carbon::parse($gallery->created_at)->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="text-center mt-10" data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ route('gallery') }}" class="inline-flex items-center px-5 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700">
                        <i class="fas fa-images mr-2"></i>
                        <span>Lihat Semua Galeri</span>
                    </a>
                </div>
            @else
                <div class="bg-gradient-to-br from-white to-indigo-50 rounded-xl p-12 text-center shadow-md border border-indigo-100" data-aos="fade-up">
                    <div class="flex flex-col items-center">
                        <div class="bg-indigo-100 p-5 rounded-full mb-6">
                            <i class="fas fa-images text-5xl text-indigo-500"></i>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-700 mb-4">Galeri Foto Segera Hadir</h4>
                        <p class="text-gray-600 mb-4 max-w-md mx-auto">Koleksi foto kegiatan sekolah akan segera ditampilkan di sini. Kunjungi kembali halaman ini untuk melihat momen-momen berharga di sekolah kami.</p>
                        <div class="mt-6 grid grid-cols-3 gap-3 max-w-md mx-auto">
                            <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80" alt="School Activity" class="w-full h-24 object-cover rounded-lg opacity-50">
                            <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80" alt="School Activity" class="w-full h-24 object-cover rounded-lg opacity-50">
                            <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=300&q=80" alt="School Activity" class="w-full h-24 object-cover rounded-lg opacity-50">
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection