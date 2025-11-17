@extends('admin.layouts.app')

@section('title', 'Profil Sekolah')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Pengaturan Profil Sekolah</h2>
        <p class="text-gray-600 mt-1">Kelola informasi dan detail sekolah</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <form action="{{ route('admin.school-profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Terjadi kesalahan:</p>
                <ul class="list-disc ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Informasi Dasar -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi Dasar</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                <!-- Nama Sekolah dan Logo dikelola lewat kode, tidak melalui form -->
            </div>

            <div class="mt-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Sekolah</label>
                <textarea name="description" id="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $profile->description) }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Deskripsi singkat tentang sekolah</p>
            </div>
        </div>

        <!-- Kontak & Alamat -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Kontak & Alamat</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-600">*</span></label>
                    <textarea name="address" id="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('address', $profile->address) }}</textarea>
                </div>

                <div>
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-600">*</span></label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $profile->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-600">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $profile->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label for="operational_hours" class="block text-sm font-medium text-gray-700 mb-1">Jam Operasional</label>
                <input type="text" name="operational_hours" id="operational_hours" value="{{ old('operational_hours', $profile->operational_hours) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Contoh: Senin-Jumat, 07:00 - 15:00</p>
            </div>

            <div class="mt-4">
                <label for="map_embed" class="block text-sm font-medium text-gray-700 mb-1">Embed Google Maps</label>
                <textarea name="map_embed" id="map_embed" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('map_embed', $profile->map_embed) }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Kode embed dari Google Maps untuk menampilkan lokasi sekolah</p>
            </div>
        </div>

        <!-- Visi & Misi -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Visi & Misi</h3>
            
            <div class="mb-4">
                <label for="vision" class="block text-sm font-medium text-gray-700 mb-1">Visi</label>
                <textarea name="vision" id="vision" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('vision', $profile->vision) }}</textarea>
            </div>

            <div>
                <label for="mission" class="block text-sm font-medium text-gray-700 mb-1">Misi</label>
                <textarea name="mission" id="mission" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('mission', $profile->mission) }}</textarea>
            </div>
        </div>

        <!-- Media Sosial -->
        <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Media Sosial</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                            <i class="fab fa-facebook"></i>
                        </span>
                        <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $profile->facebook_url) }}" placeholder="https://facebook.com/sekolahku" class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                            <i class="fab fa-instagram"></i>
                        </span>
                        <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $profile->instagram_url) }}" placeholder="https://instagram.com/sekolahku" class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label for="youtube_url" class="block text-sm font-medium text-gray-700 mb-1">YouTube</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                            <i class="fab fa-youtube"></i>
                        </span>
                        <input type="url" name="youtube_url" id="youtube_url" value="{{ old('youtube_url', $profile->youtube_url) }}" placeholder="https://youtube.com/c/sekolahku" class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-1">Twitter</label>
                    <div class="flex">
                        <span class="inline-flex items-center px-3 py-2 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                            <i class="fab fa-twitter"></i>
                        </span>
                        <input type="url" name="twitter_url" id="twitter_url" value="{{ old('twitter_url', $profile->twitter_url) }}" placeholder="https://twitter.com/sekolahku" class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection