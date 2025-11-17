@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-indigo-700 to-purple-800 text-white py-12 md:py-16">
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center" data-aos="fade-up" data-aos-duration="1000">
                <span class="inline-block bg-blue-500/30 text-white text-sm font-semibold px-4 py-1 rounded-full mb-4 backdrop-blur-sm border border-blue-400/30">
                    <i class="fas fa-envelope mr-2"></i> HUBUNGI KAMI
                </span>
                <h1 class="text-3xl md:text-5xl font-bold mb-4 md:mb-6 text-shadow leading-tight">
                    Kontak <span class="text-yellow-300 relative inline-block">
                        Kami
                        <svg class="absolute -bottom-2 left-0 w-full" height="6" viewBox="0 0 200 6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 3C50 0.5 150 0.5 200 3" stroke="#FBBF24" stroke-width="5" stroke-linecap="round"/>
                        </svg>
                    </span>
                </h1>
                <p class="text-base md:text-xl mb-6 md:mb-8 text-blue-100 leading-relaxed max-w-3xl mx-auto">
                    Hubungi kami untuk informasi lebih lanjut atau kirimkan pertanyaan, saran, dan masukan Anda
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Info Section -->
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8">
                <div class="bg-blue-50 rounded-xl p-5 md:p-8 text-center hover:shadow-lg transition-all duration-300" data-aos="fade-up" data-aos-delay="0">
                    <div class="text-4xl text-blue-600 mb-4"><i class="fas fa-map-marker-alt"></i></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Alamat</h3>
                    <p class="text-gray-600">{{ $schoolProfile->address ?? 'Jl. Pendidikan No. 123, Jakarta Selatan, Indonesia' }}</p>
                </div>

                <div class="bg-indigo-50 rounded-xl p-5 md:p-8 text-center hover:shadow-lg transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-4xl text-indigo-600 mb-4"><i class="fas fa-phone"></i></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Telepon</h3>
                    <p class="text-gray-600">{{ $schoolProfile->phone ?? '(021) 1234-5678' }}</p>
                </div>

                <div class="bg-purple-50 rounded-xl p-5 md:p-8 text-center hover:shadow-lg transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-4xl text-purple-600 mb-4"><i class="fas fa-envelope"></i></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Email</h3>
                    <p class="text-gray-600">{{ $schoolProfile->email ?? 'info@sekolahkami.ac.id' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8 md:gap-12">
                <div class="lg:w-1/2" data-aos="fade-right">
                    <h2 class="text-2xl md:text-3xl font-bold mb-2 md:mb-4 text-gray-800">Kirim Pesan</h2>
                    <p class="text-gray-600">Silakan isi formulir di bawah ini untuk mengirimkan pesan, pertanyaan, atau saran kepada kami. Tim kami akan merespons secepatnya.</p>
                    <p class="text-xs text-gray-500 mt-2 md:mt-3 mb-4 md:mb-6">Tanda * wajib diisi.</p>
                    
                    @auth
                        <!-- Contact Form - Only for authenticated users -->
                        <div class="bg-white rounded-xl shadow-lg p-5 md:p-8">
                        <form id="contactForm" class="space-y-4 md:space-y-6">
                            @csrf
                            <div class="flex items-center space-x-3 mb-4 md:mb-6">
                                <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                    {{ auth()->user()->name[0] }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-lg">{{ auth()->user()->name }}</p>
                                    <p class="text-sm text-gray-500">Kirim pesan kepada kami</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" 
                                           value="{{ auth()->user()->name }}" 
                                           readonly
                                           class="w-full px-3 md:px-4 py-2.5 md:py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-600">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" id="email" name="email" 
                                           value="{{ auth()->user()->email }}" 
                                           readonly
                                           class="w-full px-3 md:px-4 py-2.5 md:py-3 rounded-lg border border-gray-300 bg-gray-50 text-gray-600">
                                </div>
                            </div>
                            
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subjek *</label>
                                <input type="text" id="subject" name="subject" required 
                                    class="w-full px-3 md:px-4 py-2.5 md:py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                    placeholder="Masukkan subjek pesan Anda">
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Pesan *</label>
                                <textarea id="message" name="message" rows="5" required 
                                    class="w-full px-3 md:px-4 py-2.5 md:py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                        placeholder="Tulis pesan Anda di sini..."></textarea>
                            </div>
                            
                            <div class="text-right">
                                <button type="submit" id="submitBtn" class="btn-hover bg-blue-600 text-white px-5 md:px-6 py-3 rounded-lg font-semibold shadow-lg inline-flex items-center w-full md:w-auto justify-center">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Pesan
                                </button>
                            </div>
                        </form>
                        </div>
                    @else
                        <!-- Login required message -->
                        <div class="bg-white rounded-xl shadow-lg p-5 md:p-8">
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 md:p-6">
                            <div class="text-center">
                                <i class="fas fa-lock text-blue-500 text-3xl mb-4"></i>
                                <h4 class="text-xl font-semibold text-blue-800 mb-3">Login Diperlukan</h4>
                                <p class="text-blue-600 mb-6">Silakan login terlebih dahulu untuk dapat mengirimkan pesan kepada kami.</p>
                                <a href="{{ route('guest.login') }}" class="inline-flex items-center px-5 md:px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    Login Sekarang
                                </a>
                            </div>
                            </div>
                        </div>
                    @endauth
                </div>
                
                <div class="lg:w-1/2" data-aos="fade-left">
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg h-full">
                        @if($schoolProfile->map_embed)
                            <div class="h-64 md:h-[400px]">
                                {!! $schoolProfile->map_embed !!}
                            </div>
                        @else
                            <div class="bg-blue-600/20 flex items-center justify-center h-64 md:h-[400px]">
                                <div class="text-center p-4">
                                    <i class="fas fa-map-marker-alt text-4xl text-blue-400 mb-2"></i>
                                    <p class="text-gray-700">Lokasi Sekolah</p>
                                    <p class="text-sm text-gray-500 mt-2">Silakan tambahkan embed Google Maps di pengaturan admin</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Social Media Section -->
    <section class="py-12 md:py-16 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-6 md:mb-8" data-aos="fade-up">
                <h2 class="text-2xl md:text-3xl font-bold mb-3 md:mb-4">Ikuti Kami</h2>
                <p class="text-blue-100 max-w-2xl mx-auto text-sm md:text-base">Dapatkan informasi terbaru tentang kegiatan sekolah melalui media sosial kami</p>
            </div>

            <div class="flex flex-wrap justify-center gap-4 md:gap-6">
                @if($schoolProfile->facebook_url)
                    <a href="{{ $schoolProfile->facebook_url }}" class="bg-white text-blue-600 p-4 rounded-full hover:shadow-lg hover:scale-110 transition-all duration-300" target="_blank" rel="noopener">
                        <i class="fab fa-facebook-f text-2xl"></i>
                    </a>
                @else
                    <a href="#" class="bg-white text-blue-600 p-4 rounded-full hover:shadow-lg hover:scale-110 transition-all duration-300">
                        <i class="fab fa-facebook-f text-2xl"></i>
                    </a>
                @endif

                @if($schoolProfile->instagram_url)
                    <a href="{{ $schoolProfile->instagram_url }}" class="bg-white text-pink-600 p-4 rounded-full hover:shadow-lg hover:scale-110 transition-all duration-300" target="_blank" rel="noopener">
                        <i class="fab fa-instagram text-2xl"></i>
                    </a>
                @else
                    <a href="#" class="bg-white text-pink-600 p-4 rounded-full hover:shadow-lg hover:scale-110 transition-all duration-300">
                        <i class="fab fa-instagram text-2xl"></i>
                    </a>
                @endif

                @if($schoolProfile->youtube_url)
                    <a href="{{ $schoolProfile->youtube_url }}" class="bg-white text-red-600 p-4 rounded-full hover:shadow-lg hover:scale-110 transition-all duration-300" target="_blank" rel="noopener">
                        <i class="fab fa-youtube text-2xl"></i>
                    </a>
                @else
                    <a href="#" class="bg-white text-red-600 p-4 rounded-full hover:shadow-lg hover:scale-110 transition-all duration-300">
                        <i class="fab fa-youtube text-2xl"></i>
                    </a>
                @endif

                @if($schoolProfile->twitter_url)
                    <a href="{{ $schoolProfile->twitter_url }}" class="bg-white text-blue-400 p-4 rounded-full hover:shadow-lg hover:scale-110 transition-all duration-300" target="_blank" rel="noopener">
                        <i class="fab fa-twitter text-2xl"></i>
                    </a>
                @else
                    <a href="#" class="bg-white text-blue-400 p-4 rounded-full hover:shadow-lg hover:scale-110 transition-all duration-300">
                        <i class="fab fa-twitter text-2xl"></i>
                    </a>
                @endif
            </div>
        </div>
    </section>
@endsection

@auth
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (contactForm && submitBtn) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitContact();
        });
        
        function submitContact() {
            const formData = new FormData(contactForm);
            
            // Disable submit button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            
            fetch('/contact', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                // Handle rate limiting (429 Too Many Requests)
                if (response.status === 429) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Anda telah mengirim terlalu banyak pesan. Silakan tunggu 30 menit sebelum mengirim pesan lagi.');
                    });
                }
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    contactForm.reset();
                } else {
                    showNotification(data.message || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error submitting contact form:', error);
                showNotification(error.message || 'Terjadi kesalahan saat mengirim pesan', 'error');
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Kirim Pesan';
            });
        }
        
        // Show notification
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    }
});
</script>
@endpush
@endauth