@extends('admin.layouts.app')

@section('title', 'Edit Data Guru')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Edit Data Guru: {{ $teacher->name }}</h2>
        <a href="{{ route('admin.teachers.index') }}" class="text-gray-600 hover:text-gray-800">Kembali ke daftar</a>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <p class="font-semibold mb-2">Terjadi kesalahan:</p>
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $teacher->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" id="position" name="position" value="{{ old('position', $teacher->position) }}" required class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('position') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('description', $teacher->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="facebook" class="block text-sm font-medium text-gray-700">Facebook</label>
                        <input type="url" id="facebook" name="facebook" value="{{ old('facebook', $teacher->facebook) }}" placeholder="https://facebook.com/username" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('facebook') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                    </div>
                    <div>
                        <label for="twitter" class="block text-sm font-medium text-gray-700">Twitter</label>
                        <input type="url" id="twitter" name="twitter" value="{{ old('twitter', $teacher->twitter) }}" placeholder="https://twitter.com/username" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('twitter') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="instagram" class="block text-sm font-medium text-gray-700">Instagram</label>
                        <input type="url" id="instagram" name="instagram" value="{{ old('instagram', $teacher->instagram) }}" placeholder="https://instagram.com/username" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('instagram') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                    </div>
                    <div>
                        <label for="linkedin" class="block text-sm font-medium text-gray-700">LinkedIn</label>
                        <input type="url" id="linkedin" name="linkedin" value="{{ old('linkedin', $teacher->linkedin) }}" placeholder="https://linkedin.com/in/username" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('linkedin') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700">Urutan Tampil</label>
                        <input type="number" id="order" name="order" value="{{ old('order', $teacher->order) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('order') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Angka lebih kecil akan muncul lebih dulu</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $teacher->is_active) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="is_active" class="ml-2 text-sm text-gray-700">Aktif</label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Nonaktifkan untuk menyembunyikan dari tampilan pengunjung</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Foto Profil</label>
                <div class="mt-1 flex flex-col items-start">
                    <div class="w-40 h-40 rounded-lg bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if($teacher->image)
                            <img id="preview-image" src="{{ \App\Helpers\StorageHelper::getStorageUrl($teacher->image) }}" alt="{{ $teacher->name }}" class="w-full h-full object-cover">
                        @else
                            <img id="preview-image" src="" alt="" class="hidden w-full h-full object-cover">
                            <i id="preview-icon" class="fas fa-user text-4xl text-gray-300"></i>
                        @endif
                    </div>
                    <input type="file" id="image" name="image" accept="image/*" class="mt-3 text-sm text-gray-700 @error('image') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Ukuran maksimal 2MB. Format: JPG, JPEG, PNG, GIF</p>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t flex justify-end space-x-3">
            <a href="{{ route('admin.teachers.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-5 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Perbarui Data</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const input = document.getElementById('image');
    const previewImg = document.getElementById('preview-image');
    const previewIcon = document.getElementById('preview-icon');
    input?.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = (ev) => {
            if (previewIcon) previewIcon.classList.add('hidden');
            previewImg.src = ev.target.result;
            previewImg.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });
    </script>
@endpush
