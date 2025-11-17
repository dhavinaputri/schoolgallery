@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-block bg-blue-500/30 text-white text-sm font-semibold px-4 py-1 rounded-full mb-4 backdrop-blur-sm border border-blue-400/30">
                    <i class="fas fa-info-circle mr-2"></i> TENTANG KAMI
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-shadow leading-tight">
                    Tentang <span class="text-yellow-300 relative inline-block">
                        {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}
                        <svg class="absolute -bottom-2 left-0 w-full" height="6" viewBox="0 0 200 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 3C50 0.5 150 0.5 200 3" stroke="#FBBF24" stroke-width="5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>
                <p class="text-xl mb-8 text-blue-100 leading-relaxed max-w-3xl mx-auto">
                    Mengenal lebih dekat tentang sejarah, visi, misi, dan nilai-nilai yang kami anut
                </p>
            </div>
        </div>
    </section>

    

    <!-- School Profile Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="lg:w-1/2" data-aos="fade-right">
                    <div class="relative rounded-xl overflow-hidden shadow-2xl">
                        @if($schoolProfile->school_image)
                            <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($schoolProfile->school_image) }}" alt="{{ $schoolProfile->school_name }}" class="w-full h-auto">
                        @else
                            <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                                 alt="School Image" class="w-full h-auto">
                        @endif
                        <div class="absolute top-4 right-4">
                            <span class="bg-white/80 backdrop-blur-sm text-blue-800 px-3 py-1 rounded-lg text-xs font-medium shadow-lg">
                                <i class="fas fa-award text-yellow-500 mr-1"></i> Terakreditasi A
                            </span>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2" data-aos="fade-left">
                    <h2 class="text-3xl font-bold mb-6 text-gray-800">Sejarah Singkat</h2>
                    <div class="prose prose-lg max-w-none text-gray-600">
                        @if($schoolProfile->history)
                            {!! $schoolProfile->history !!}
                        @else
                            <p>{{ $schoolProfile->school_name ?? 'Sekolah Kami' }} SMK Negeri 4 Kota Bogor merupakan sekolah menengah kejuruan berbasis Teknologi Informasi dan Komunikasi. Sekolah ini didirikan dan dirintis pada tahun 2008 kemudian dibuka pada tahun 2009 yang saat ini terakreditasi A.</p>
                            <p>Selama perjalanannya, sekolah kami telah menghasilkan ribuan lulusan yang sukses di berbagai bidang dan berkontribusi positif bagi masyarakat. Kami terus berinovasi dalam metode pembelajaran dan pengembangan fasilitas untuk menciptakan lingkungan belajar yang optimal.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision Mission Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Visi & Misi</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Landasan dan arah pengembangan institusi kami</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-xl p-8 shadow-lg border-t-4 border-blue-600" data-aos="fade-right">
                    <div class="text-4xl text-blue-600 mb-4"><i class="fas fa-eye"></i></div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">Visi</h3>
                    <div class="prose prose-lg text-gray-600">
                        @if($schoolProfile->vision)
                            {!! $schoolProfile->vision !!}
                        @else
                            <p>Menjadi lembaga pendidikan terkemuka yang menghasilkan generasi unggul, berkarakter, dan berwawasan global yang mampu berkontribusi positif bagi masyarakat dan bangsa.</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-lg border-t-4 border-indigo-600" data-aos="fade-left">
                    <div class="text-4xl text-indigo-600 mb-4"><i class="fas fa-bullseye"></i></div>
                    <h3 class="text-2xl font-bold mb-4 text-gray-800">Misi</h3>
                    <div class="prose prose-lg text-gray-600">
                        @if($schoolProfile->mission)
                            {!! $schoolProfile->mission !!}
                        @else
                            <ul>
                                <li>Menyelenggarakan pendidikan berkualitas dengan kurikulum yang komprehensif dan inovatif</li>
                                <li>Mengembangkan potensi akademik dan non-akademik siswa secara optimal</li>
                                <li>Membangun karakter dan kepribadian siswa berdasarkan nilai-nilai luhur</li>
                                <li>Menciptakan lingkungan belajar yang kondusif, aman, dan nyaman</li>
                                <li>Menjalin kerjasama dengan berbagai pihak untuk pengembangan institusi</li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Majors / Departments Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Program Keahlian</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Pilihan jurusan yang siap membekali siswa dengan keterampilan sesuai kebutuhan industri</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition-all" data-aos="zoom-in" data-aos-delay="0">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-800">PPLG</h3>
                        <span class="text-blue-600"><i class="fas fa-code"></i></span>
                    </div>
                    <p class="text-gray-600 text-sm">Pengembangan Perangkat Lunak dan Gim: fokus pada pemrograman, UI/UX, basis data, dan pembuatan gim.</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition-all" data-aos="zoom-in" data-aos-delay="100">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-800">TJKT</h3>
                        <span class="text-green-600"><i class="fas fa-network-wired"></i></span>
                    </div>
                    <p class="text-gray-600 text-sm">Teknik Jaringan Komputer dan Telekomunikasi: perakitan, administrasi jaringan, server, dan keamanan jaringan.</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition-all" data-aos="zoom-in" data-aos-delay="200">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-800">TPFL</h3>
                        <span class="text-orange-600"><i class="fas fa-industry"></i></span>
                    </div>
                    <p class="text-gray-600 text-sm">Teknik Pengelasan dan Fabrikasi Logam: proses pengelasan, fabrikasi, keselamatan kerja, dan gambar teknik.</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow hover:shadow-lg transition-all" data-aos="zoom-in" data-aos-delay="300">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xl font-bold text-gray-800">TKRO</h3>
                        <span class="text-red-600"><i class="fas fa-car"></i></span>
                    </div>
                    <p class="text-gray-600 text-sm">Teknik Kendaraan Ringan Otomotif: perawatan dan perbaikan sistem mesin, chassis, kelistrikan, dan diagnosa kendaraan.</p>
                </div>
            </div>
        </div>
    </section>

    

    <!-- Values Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Nilai-Nilai Kami</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Prinsip yang menjadi pedoman dalam setiap aktivitas di sekolah kami</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-blue-50 rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300" data-aos="zoom-in" data-aos-delay="0">
                    <div class="text-4xl text-blue-600 mb-4"><i class="fas fa-brain"></i></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Keunggulan</h3>
                    <p class="text-gray-600">Kami berkomitmen untuk mencapai standar tertinggi dalam segala aspek pendidikan</p>
                </div>

                <div class="bg-indigo-50 rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300" data-aos="zoom-in" data-aos-delay="100">
                    <div class="text-4xl text-indigo-600 mb-4"><i class="fas fa-handshake"></i></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Integritas</h3>
                    <p class="text-gray-600">Kami menjunjung tinggi kejujuran, etika, dan tanggung jawab dalam setiap tindakan</p>
                </div>

                <div class="bg-purple-50 rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300" data-aos="zoom-in" data-aos-delay="200">
                    <div class="text-4xl text-purple-600 mb-4"><i class="fas fa-users"></i></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Kolaborasi</h3>
                    <p class="text-gray-600">Kami mendorong kerjasama dan sinergi antara siswa, guru, orang tua, dan masyarakat</p>
                </div>

                <div class="bg-green-50 rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300" data-aos="zoom-in" data-aos-delay="300">
                    <div class="text-4xl text-green-600 mb-4"><i class="fas fa-lightbulb"></i></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Inovasi</h3>
                    <p class="text-gray-600">Kami terus mengembangkan metode dan pendekatan baru dalam proses pembelajaran</p>
                </div>
            </div>
        </div>
    </section>

     <!-- Team Section -->
     <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Tim Pengajar</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Tenaga pendidik profesional yang berdedikasi untuk mengembangkan potensi siswa</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($teachers->take(4) as $index => $teacher)
                <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                    <div class="relative overflow-hidden aspect-square">
                        @if($teacher->image)
                            <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($teacher->image) }}" 
                                 alt="{{ $teacher->name }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-user text-6xl text-gray-400"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <div class="text-white text-left w-full">
                                <h3 class="font-bold text-lg">{{ $teacher->name }}</h3>
                                <p class="text-blue-300">{{ $teacher->position }}</p>
                                
                                @if($teacher->facebook || $teacher->twitter || $teacher->instagram || $teacher->linkedin)
                                <div class="flex space-x-2 mt-2">
                                    @if($teacher->facebook)
                                        <a href="{{ $teacher->facebook }}" target="_blank" class="text-white hover:text-blue-300">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    @endif
                                    @if($teacher->twitter)
                                        <a href="{{ $teacher->twitter }}" target="_blank" class="text-white hover:text-blue-300">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    @endif
                                    @if($teacher->instagram)
                                        <a href="{{ $teacher->instagram }}" target="_blank" class="text-white hover:text-blue-300">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    @endif
                                    @if($teacher->linkedin)
                                        <a href="{{ $teacher->linkedin }}" target="_blank" class="text-white hover:text-blue-300">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="p-3 text-center">
                        <h3 class="text-sm font-semibold text-gray-800 overflow-hidden text-ellipsis whitespace-nowrap">{{ $teacher->name }}</h3>
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center py-8">
                    <p class="text-gray-500">Belum ada data guru yang tersedia.</p>
                </div>
                @endforelse
            </div>
            
            @if($teachers->count() > 4)
            <div class="text-center mt-8">
                <a href="{{ route('teachers') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                    Lihat Semua Guru
                </a>
            </div>
            @endif
        </div>
    </section>  

    <!-- Facilities Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Fasilitas</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Kami menyediakan berbagai fasilitas modern untuk mendukung proses belajar mengajar</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($facilities as $index => $facility)
                <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="relative overflow-hidden aspect-square">
                        <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($facility->image) }}" 
                             alt="{{ $facility->title }}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    </div>
                    <div class="p-3 text-center">
                        <h3 class="text-sm font-semibold text-gray-800 overflow-hidden text-ellipsis whitespace-nowrap">{{ $facility->title }}</h3>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-8">
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg p-8">
                        <i class="fas fa-building text-gray-300 text-5xl mb-4"></i>
                        <p class="text-gray-500">Galeri fasilitas belum tersedia. Silakan tambahkan foto fasilitas di halaman admin.</p>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('gallery.category', 'fasilitas-sekolah') }}" class="btn-hover inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold shadow-lg">
                    <i class="fas fa-images mr-2"></i> Lihat Galeri Fasilitas
                </a>
            </div>
        </div>
    </section>


    <!-- Call to Action -->
    <section class="py-16 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-2/3 mb-8 md:mb-0" data-aos="fade-right">
                    <h2 class="text-3xl font-bold mb-4">Tertarik Untuk Bergabung?</h2>
                    <p class="text-blue-100">Jadilah bagian dari komunitas pendidikan kami dan kembangkan potensi Anda bersama {{ $schoolProfile->school_name ?? 'Sekolah Kami' }}</p>
                </div>
                <div class="md:w-1/3 text-center md:text-right" data-aos="fade-left">
                    <a href="{{ route('contact') }}" class="btn-hover inline-block bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i> Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection