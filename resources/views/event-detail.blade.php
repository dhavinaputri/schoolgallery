@extends('layouts.app')

@section('content')
    <!-- Hero with image/background -->
    <section class="relative bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold mb-4 leading-tight max-w-4xl mx-auto">{{ $event->title }}</h1>
                <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-blue-100">
                    <span class="inline-flex items-center bg-blue-600/30 px-3 py-1 rounded-full">
                        <i class="far fa-calendar-alt mr-2"></i>
                        {{ $event->start_at->format('d M Y') }}
                        @if($event->end_at && $event->end_at->format('Y-m-d') != $event->start_at->format('Y-m-d'))
                            - {{ $event->end_at->format('d M Y') }}
                        @endif
                    </span>
                    @if($event->location)
                        <span class="inline-flex items-center bg-blue-600/30 px-3 py-1 rounded-full">
                            <i class="fas fa-map-marker-alt mr-2"></i>{{ $event->location }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Content with sidebar -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    @if($event->image)
                        <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($event->image) }}" alt="{{ $event->title }}" class="w-full h-96 object-cover">
                    @endif
                    <div class="p-8">
                        <div class="prose prose-lg max-w-none">
                            {!! nl2br(e($event->description)) !!}
                        </div>
                        
                        <!-- Share Buttons -->
                        <div class="mt-12 pt-6 border-t border-gray-100">
                            <p class="text-sm font-medium text-gray-500 mb-3">Bagikan acara ini:</p>
                            <div class="flex items-center gap-3">
                                <a href="#" class="bg-blue-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="bg-blue-400 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-500 transition-colors">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="bg-green-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-green-700 transition-colors">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <a href="#" class="bg-blue-700 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-blue-800 transition-colors">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="bg-red-600 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-red-700 transition-colors">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <aside class="lg:col-span-4 space-y-6">
                <!-- Event Details -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4">
                        <h3 class="text-lg font-semibold">Detail Acara</h3>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Tanggal & Waktu</h4>
                                    <p class="mt-1 text-gray-900">
                                        {{ $event->start_at->format('l, d M Y') }}
                                        <br>
                                        {{ $event->start_at->format('H:i') }}
                                        @if($event->end_at)
                                            - {{ $event->end_at->format('H:i') }} WIB
                                        @endif
                                    </p>
                                </div>
                            </li>
                            
                            @if($event->location)
                            <li class="flex items-start pt-4 border-t border-gray-100">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Lokasi</h4>
                                    <p class="mt-1 text-gray-900">{{ $event->location }}</p>
                                </div>
                            </li>
                            @endif
                            
                            @if($event->contact_person)
                            <li class="flex items-start pt-4 border-t border-gray-100">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-500">Kontak</h4>
                                    <p class="mt-1 text-gray-900">{{ $event->contact_person }}</p>
                                </div>
                            </li>
                            @endif
                        </ul>
                        
                        @if($event->registration_link)
                        <div class="mt-6">
                            <a href="{{ $event->registration_link }}" target="_blank" class="w-full flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                Daftar Sekarang
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Map Placeholder -->
                @if($event->location)
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4">
                        <h3 class="text-lg font-semibold">Lokasi</h3>
                    </div>
                    <div class="p-4">
                        <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg overflow-hidden">
                            <!-- Replace with your actual map embed code -->
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-400">
                                <i class="fas fa-map-marked-alt text-4xl"></i>
                            </div>
                        </div>
                        <p class="mt-3 text-sm text-gray-600">{{ $event->location }}</p>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($event->location) }}" target="_blank" class="mt-2 inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                            Buka di Google Maps <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                        </a>
                    </div>
                </div>
                @endif
            </aside>
        </div>
    </section>
@endsection


