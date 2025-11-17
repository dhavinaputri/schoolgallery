@extends('layouts.app')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="text-center">
        <div class="text-7xl font-extrabold text-blue-600 mb-2">404</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Halaman Tidak Ditemukan</h1>
        <p class="text-gray-600 mb-6">Maaf, halaman yang Anda cari tidak tersedia atau sudah dipindahkan.</p>
        <a href="{{ route('home') }}" class="inline-block px-5 py-2.5 bg-blue-600 text-white rounded-md hover:bg-blue-700">Kembali ke Beranda</a>
    </div>
    </div>
@endsection


