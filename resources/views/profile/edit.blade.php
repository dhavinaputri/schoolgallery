@extends('layouts.app')

@section('content')
@php($tab = $tab ?? request('tab', 'akun'))
<div class="min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 py-8">
        @if(session('status'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div>
            <!-- Content -->
            <div>
                @if($tab === 'akun')
                    <div class="bg-white rounded-lg shadow-sm p-4 md:p-8">
                        <div class="flex flex-col md:flex-row items-center md:items-start gap-4 md:gap-6 text-center md:text-left">
                            <!-- Profile Picture -->
                            <div class="relative self-center md:self-auto">
                                <div class="w-20 h-20 md:w-24 md:h-24 bg-gray-200 rounded-full overflow-hidden flex items-center justify-center">
                                    <img id="avatarPreview" src="{{ $user->avatar ? \App\Helpers\StorageHelper::getStorageUrl($user->avatar) : '' }}" alt="Avatar" class="w-full h-full object-cover {{ $user->avatar ? '' : 'hidden' }}" />
                                    @unless($user->avatar)
                                        <i id="avatarIcon" class="fas fa-user text-3xl text-gray-400"></i>
                                    @endunless
                                </div>
                                <label for="avatar" class="absolute bottom-0 right-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors cursor-pointer" title="Ubah foto profil">
                                    <i class="fas fa-camera text-sm"></i>
                                </label>
                            </div>

                            <!-- User Info -->
                            <div class="flex-1 w-full">
                                <form id="profile-form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4 md:space-y-6 max-w-md mx-auto md:max-w-none md:mx-0">
                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                        <div>
                                            <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Nama</label>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                                   class="block w-full px-3 md:px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base" required />
                                            @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Email</label>
                                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                                   class="block w-full px-3 md:px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base" required />
                                            @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                        </div>
                                    </div>

                                    <input id="avatar" name="avatar" type="file" class="hidden" accept="image/*" />
                                    @error('avatar')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror

                                    <div class="border-t pt-6">
                                        <h3 class="text-base md:text-lg font-semibold text-gray-800 mb-4">Ubah Password (opsional)</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                                            <div>
                                                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Password Saat Ini</label>
                                                <input type="password" name="current_password" 
                                                       class="block w-full px-3 md:px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base" />
                                                @error('current_password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Password Baru</label>
                                                <input type="password" name="password" 
                                                       class="block w-full px-3 md:px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base" />
                                                @error('password')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Konfirmasi Password Baru</label>
                                                <input type="password" name="password_confirmation" 
                                                       class="block w-full px-3 md:px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm md:text-base" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-center md:justify-between pt-6 border-t">
                                        <div class="flex space-x-4">
                                            <button type="submit" name="redirect" value="home" class="px-4 md:px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors flex items-center text-sm md:text-base">
                                                <i class="fas fa-edit mr-2"></i> Simpan Perubahan
                                            </button>
                                            <span id="unsavedBadge" class="hidden text-sm text-amber-700 bg-amber-100 border border-amber-200 px-3 py-1 rounded">Perubahan belum disimpan</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif($tab === 'aktivitas')
                    <div class="bg-white rounded-lg shadow-sm p-4 md:p-8">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-4">Aktivitas Saya</h3>
                        @if($activities->isEmpty())
                            <div class="border rounded-md p-4 text-sm text-gray-600">Belum ada aktivitas untuk ditampilkan.</div>
                        @else
                            <div class="space-y-3">
                                @foreach($activities as $act)
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border rounded-md p-3">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 md:w-12 md:h-12 rounded overflow-hidden bg-gray-100 flex-shrink-0">
                                                @if(!empty($act['gallery']) && $act['gallery']->image)
                                                    <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($act['gallery']->image) }}" alt="{{ $act['gallery']->title }}" class="w-full h-full object-cover"/>
                                                @elseif(!empty($act['news']) && $act['news']->image)
                                                    <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($act['news']->image) }}" alt="{{ $act['news']->title }}" class="w-full h-full object-cover"/>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm md:text-base text-gray-700">
                                                    @if($act['type']==='like')
                                                        <span class="text-pink-600 font-medium">Menyukai</span>
                                                    @elseif($act['type']==='gallery_comment')
                                                        <span class="text-blue-600 font-medium">Mengomentari Galeri</span>
                                                    @elseif($act['type']==='news_comment')
                                                        <span class="text-blue-600 font-medium">Mengomentari Berita</span>
                                                    @endif
                                                    <span class="font-semibold">{{ $act['gallery']->title ?? $act['news']->title ?? 'Konten' }}</span>
                                                </div>
                                                @if(!empty($act['excerpt']))
                                                    <div class="text-xs text-gray-500">“{{ $act['excerpt'] }}”</div>
                                                @endif
                                                <div class="text-[11px] md:text-xs text-gray-400">{{ optional($act['at'])->format('d M Y H:i') }}</div>
                                            </div>
                                        </div>
                                        @if(!empty($act['gallery']))
                                            <a href="{{ route('gallery.detail', $act['gallery']->id) }}" class="text-xs md:text-sm text-blue-600 hover:underline">Buka</a>
                                        @elseif(!empty($act['news']))
                                            <a href="{{ route('news.detail', $act['news']->slug) }}" class="text-xs md:text-sm text-blue-600 hover:underline">Buka</a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @elseif($tab === 'favorit')
                    <div class="bg-white rounded-lg shadow-sm p-4 md:p-8">
                        <h3 class="text-base md:text-lg font-semibold text-gray-900 mb-4">Favorit Saya</h3>
                        @if($favorites->isEmpty())
                            <div class="border rounded-md p-4 text-sm text-gray-600">Belum ada konten favorit.</div>
                        @else
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
                                @foreach($favorites as $fav)
                                    @if($fav->gallery)
                                        <a href="{{ route('gallery.detail', $fav->gallery->id) }}" class="block group">
                                            <div class="rounded-lg overflow-hidden shadow-sm border">
                                                <div class="w-full" style="aspect-ratio: 16 / 9;">
                                                    <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($fav->gallery->image) }}" alt="{{ $fav->gallery->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                                                </div>
                                                <div class="p-2">
                                                    <div class="text-sm md:text-base font-medium line-clamp-2">{{ $fav->gallery->title }}</div>
                                                    <div class="text-[11px] md:text-xs text-gray-500 mt-1">{{ optional($fav->gallery->created_at)->format('d M Y') }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


@push('styles')
<style>
    .hidden { display: none; }
    .fade-in { animation: fadeIn 200ms ease-in; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    .spin { animation: spin 1s linear infinite; }
    @keyframes spin { from { transform: rotate(0); } to { transform: rotate(360deg); } }
    .btn-disabled { opacity: 0.6; pointer-events: none; }
    .toast { position: fixed; top: 16px; right: 16px; z-index: 9999; }
    .toast > div { box-shadow: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1); }
}</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('avatar');
    const previewImg = document.getElementById('avatarPreview');
    const previewIcon = document.getElementById('avatarIcon');
    const form = document.getElementById('profile-form');
    const unsaved = document.getElementById('unsavedBadge');

    function showUnsaved() {
        if (unsaved) unsaved.classList.remove('hidden');
    }

    // Live preview and auto-submit when user selects a new avatar
    if (fileInput) {
        fileInput.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) return;

            // Preview
            const reader = new FileReader();
            reader.onload = function (e) {
                if (previewImg) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                }
                if (previewIcon) previewIcon.classList.add('hidden');
            };
            reader.readAsDataURL(file);

            // Auto-submit to persist immediately
            if (form) {
                form.submit();
            } else {
                showUnsaved();
            }
        });
    }

    // Mark unsaved when text inputs change
    document.querySelectorAll('#profile-form input[type="text"], #profile-form input[type="email"], #profile-form input[type="password"]').forEach(el => {
        el.addEventListener('input', showUnsaved);
    });
});
</script>
@endpush
