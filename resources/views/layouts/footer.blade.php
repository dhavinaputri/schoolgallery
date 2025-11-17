<footer class="bg-gradient-to-b from-gray-800 to-gray-900 text-white pt-16 pb-8 relative overflow-hidden">
    
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="flex flex-col md:flex-row justify-between mb-12">
            <div class="mb-10 md:mb-0 md:w-1/3" data-aos="fade-right" data-aos-duration="800">
                <div class="space-y-4">
                    @if($schoolProfile->address)
                        <div class="flex items-start group">
                            <div class="bg-indigo-600/20 p-2 rounded-lg mr-3 group-hover:bg-indigo-600/40 transition-all duration-300">
                                <i class="fas fa-map-marker-alt text-indigo-300"></i>
                            </div>
                            <div>
                                <h6 class="text-sm font-semibold text-indigo-200">Alamat</h6>
                                <p class="text-gray-300">{{ $schoolProfile->address }}</p>
                            </div>
                        </div>
                    @endif
                    @if($schoolProfile->phone)
                        <div class="flex items-start group">
                            <div class="bg-indigo-600/20 p-2 rounded-lg mr-3 group-hover:bg-indigo-600/40 transition-all duration-300">
                                <i class="fas fa-phone text-indigo-300"></i>
                            </div>
                            <div>
                                <h6 class="text-sm font-semibold text-indigo-200">Telepon</h6>
                                <p class="text-gray-300">{{ $schoolProfile->phone }}</p>
                            </div>
                        </div>
                    @endif
                    @if($schoolProfile->email)
                        <div class="flex items-start group">
                            <div class="bg-indigo-600/20 p-2 rounded-lg mr-3 group-hover:bg-indigo-600/40 transition-all duration-300">
                                <i class="fas fa-envelope text-indigo-300"></i>
                            </div>
                            <div>
                                <h6 class="text-sm font-semibold text-indigo-200">Email</h6>
                                <p class="text-gray-300">{{ $schoolProfile->email }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="md:w-1/3" data-aos="fade-left" data-aos-duration="800" data-aos-delay="200">
                <h4 class="text-lg font-bold mb-6 text-white relative inline-block">
                    <span class="relative z-10">Ikuti Kami</span>
                    <span class="absolute bottom-0 left-0 w-full h-1 bg-blue-600"></span>
                </h4>
                <p class="text-gray-300 mb-6">Ikuti kami di media sosial untuk mendapatkan informasi terbaru tentang kegiatan sekolah</p>
                <div class="flex flex-wrap gap-3">
                    @if($schoolProfile->facebook_url)
                        <a href="{{ $schoolProfile->facebook_url }}" class="bg-gradient-to-br from-blue-600 to-blue-800 hover:from-blue-500 hover:to-blue-700 text-white p-3 rounded-lg transition-all duration-300 shadow-lg hover:scale-110" target="_blank" rel="noopener">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                    @endif
                    @if(data_get($schoolProfile, 'tiktok_url'))
                        <a href="{{ $schoolProfile->tiktok_url }}" class="bg-black hover:bg-gray-800 text-white p-3 rounded-lg transition-all duration-300 shadow-lg hover:scale-110" target="_blank" rel="noopener">
                            <i class="fab fa-tiktok text-lg"></i>
                        </a>
                    @endif
                    @if($schoolProfile->instagram_url)
                        <a href="{{ $schoolProfile->instagram_url }}" class="bg-gradient-to-br from-purple-600 to-pink-500 hover:from-purple-500 hover:to-pink-400 text-white p-3 rounded-lg transition-all duration-300 shadow-lg hover:scale-110" target="_blank" rel="noopener">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                    @endif
                    @if($schoolProfile->youtube_url)
                        <a href="{{ $schoolProfile->youtube_url }}" class="bg-gradient-to-br from-red-600 to-red-800 hover:from-red-500 hover:to-red-700 text-white p-3 rounded-lg transition-all duration-300 shadow-lg hover:scale-110" target="_blank" rel="noopener">
                            <i class="fab fa-youtube text-lg"></i>
                        </a>
                    @endif
                    @if($schoolProfile->twitter_url)
                        <a href="{{ $schoolProfile->twitter_url }}" class="bg-gradient-to-br from-blue-400 to-blue-600 hover:from-blue-300 hover:to-blue-500 text-white p-3 rounded-lg transition-all duration-300 shadow-lg hover:scale-110" target="_blank" rel="noopener">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                    @endif
                </div>
                
                @if($schoolProfile->operational_hours)
                    <div class="mt-6 bg-white/5 backdrop-blur-sm p-5 rounded-xl shadow-lg border border-white/10">
                        <h5 class="font-semibold text-white mb-2 flex items-center">
                            <i class="far fa-clock mr-2 text-yellow-400"></i> Jam Operasional
                        </h5>
                        <p class="text-gray-300">{{ $schoolProfile->operational_hours }}</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="border-t border-gray-700 pt-8 mt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 mb-4 md:mb-0">&copy; {{ date('Y') }} {{ $schoolProfile->school_name ?? 'Galeri Sekolah' }}. All rights reserved.</p>
                <div class="text-gray-400 text-sm">
                    <a href="#" class="hover:text-white mr-4">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white mr-4">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white">Peta Situs</a>
                </div>
            </div>

        </div>
    </div>
</footer>