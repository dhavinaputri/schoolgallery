@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-envelope-open-text text-white text-2xl"></i>
            </div>
            <h2 class="mt-6 text-3xl font-bold text-gray-900">Verifikasi Email Anda</h2>
            <p class="mt-2 text-sm text-gray-600">
                Silakan cek inbox email Anda untuk melanjutkan
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">{{ session('info') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="text-center space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <i class="fas fa-paper-plane text-blue-600 text-4xl mb-3"></i>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Kami telah mengirimkan link verifikasi ke alamat email: 
                        <br>
                        <strong class="text-blue-600">{{ session('email') ?? (Auth::check() ? Auth::user()->email : 'email Anda') }}</strong>
                    </p>
                    <p class="text-gray-600 text-xs mt-3">
                        Klik link verifikasi di email tersebut untuk mengaktifkan akun Anda.
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-4">
                        Tidak menerima email?
                    </p>
                    
                    <form method="POST" action="{{ route('guest.verification.resend') }}" class="space-y-4">
                        @csrf
                        <div>
                            <input
                                type="email"
                                name="email"
                                value="{{ session('email') ?? (Auth::check() ? Auth::user()->email : '') }}"
                                required
                                placeholder="Masukkan email Anda"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            >
                        </div>
                        
                        <button
                            type="submit"
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            <i class="fas fa-redo mr-2"></i>
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>
                </div>

                <div class="pt-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 leading-relaxed">
                            <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                            <strong>Tips:</strong> Cek folder Spam atau Junk jika email tidak masuk ke Inbox.
                        </p>
                    </div>
                </div>

                <div class="pt-4 space-y-2">
                    <a href="{{ route('home') }}" class="block text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">
                        <i class="fas fa-home mr-1"></i>
                        Kembali ke Beranda
                    </a>
                    <a href="{{ route('guest.login') }}" class="block text-sm text-gray-600 hover:text-gray-800 transition-colors">
                        <i class="fas fa-sign-in-alt mr-1"></i>
                        Sudah verifikasi? Login di sini
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center">
            <p class="text-xs text-gray-500">
                Butuh bantuan? Hubungi 
                <a href="{{ route('contact') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    support kami
                </a>
            </p>
        </div>
    </div>
</div>

<script>
// Auto-focus email input if empty
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.querySelector('input[name="email"]');
    if (emailInput && !emailInput.value) {
        emailInput.focus();
    }
});
</script>
@endsection
