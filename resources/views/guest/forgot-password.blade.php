@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-12 w-12 bg-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-unlock-alt text-white text-xl"></i>
            </div>
            <h2 class="mt-6 text-3xl font-bold text-gray-900">Lupa Password</h2>
            <p class="mt-2 text-sm text-gray-600">
                Masukkan email Anda. Kami akan mengirimkan link untuk reset password.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            @if (session('status'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    <p class="text-sm">{{ session('status') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('guest.password.email') }}" method="POST">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-blue-500"></i>Email
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        autocomplete="email" 
                        required 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email Anda"
                    >
                </div>

                <div>
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        Kirim Link Reset
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('guest.login') }}" class="text-sm text-blue-600 hover:text-blue-500">Kembali ke login</a>
            </div>
        </div>
    </div>
</div>
@endsection
