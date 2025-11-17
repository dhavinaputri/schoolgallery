@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center">
                <span class="inline-block bg-blue-500/30 text-white text-sm font-semibold px-4 py-1 rounded-full mb-4 backdrop-blur-sm border border-blue-400/30">
                    <i class="fas fa-users mr-2"></i> DAFTAR GURU
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mb-6 text-shadow leading-tight">
                    Tim Pengajar
                </h1>
                <p class="text-blue-100 text-lg">Tenaga pendidik profesional yang berdedikasi</p>
            </div>
        </div>
    </section>

    <!-- Teachers Grid -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Search Bar -->
            <form method="GET" action="{{ route('teachers') }}" class="mb-8" id="teacherSearchForm">
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari guru..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-search mr-1"></i> Cari
                    </button>
                </div>
            </form>
            <script>
                (function(){
                    const form = document.getElementById('teacherSearchForm');
                    if (!form) return;
                    const input = form.querySelector('input[name="q"]');
                    let t;
                    input && input.addEventListener('input', function(){
                        clearTimeout(t);
                        t = setTimeout(()=>{ form.submit(); }, 300);
                    });
                })();
            </script>
            @if($teachers->isEmpty())
                <div class="text-center text-gray-500">Belum ada data guru yang tersedia.</div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($teachers as $index => $teacher)
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 group">
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
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
