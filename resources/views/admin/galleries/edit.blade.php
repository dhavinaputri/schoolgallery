@extends('admin.layouts.app')

@section('title', 'Edit Foto Galeri')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Edit Foto</h2>
        <p class="text-gray-600 mt-1">Perbarui informasi dan gambar foto</p>
    </div>

    <form action="{{ route('admin.galleries.update', $gallery) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        @if(request()->has('kategori'))
            <input type="hidden" name="kategori" value="{{ request('kategori') }}">
        @endif

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

        <div class="mb-6">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Foto <span class="text-red-600">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $gallery->title) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" id="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $gallery->description) }}</textarea>
            <p class="text-sm text-gray-500 mt-1">Deskripsi singkat tentang foto (opsional)</p>
        </div>

        <div class="mb-6">
            <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-600">*</span></label>
            <select name="kategori_id" id="kategori_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $gallery->kategori_id) == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Saat Ini</label>
            <div class="mt-1 relative group">
                <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($gallery->image) }}" alt="{{ $gallery->title }}" class="max-h-48 rounded-lg shadow-sm group-hover:opacity-90 transition duration-200">
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                    <span class="bg-black bg-opacity-50 text-white px-3 py-1 rounded-lg text-sm">Foto saat ini</span>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Ganti Foto</label>
            <div id="dropZone" class="mt-1 flex items-center justify-center px-4 py-8 bg-white text-blue-600 rounded-lg shadow-lg tracking-wide border-2 border-dashed border-blue-500 cursor-pointer hover:bg-blue-50 hover:border-blue-600 transition-all duration-300">
                <div class="text-center">
                    <svg class="w-12 h-12 mx-auto mb-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                    </svg>
                    <p class="text-lg font-medium mb-2">Drag & Drop foto baru di sini</p>
                    <p class="text-sm text-gray-500 mb-4">atau</p>
                    <label class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Pilih File Baru
                        <input type="file" name="image" id="image" class="hidden" accept="image/*">
                    </label>
                </div>
            </div>
            <div id="image-preview" class="mt-4 hidden">
                <div class="relative bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                    <img id="preview-image" src="#" alt="Preview" class="w-full h-64 object-cover">
                    <button type="button" id="remove-preview" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-md hover:bg-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div id="file-info" class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded"></div>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-2">Format yang didukung: JPG, PNG, GIF, WEBP. Ukuran maksimal: 5MB. Biarkan kosong jika tidak ingin mengganti foto.</p>
        </div>

        <div class="mb-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $gallery->is_published) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <span class="ml-2 text-sm text-gray-700">Publikasikan</span>
            </label>
            <p class="text-sm text-gray-500 mt-1">Jika tidak dicentang, foto akan disimpan sebagai draft</p>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Drag and Drop functionality
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('image');
    const previewContainer = document.getElementById('image-preview');
    const previewImage = document.getElementById('preview-image');
    const fileInfo = document.getElementById('file-info');
    const removePreview = document.getElementById('remove-preview');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);

    // Handle file input change
    fileInput.addEventListener('change', function(e) {
        handleFiles(e.target.files);
    });

    // Handle remove preview
    removePreview.addEventListener('click', function() {
        previewContainer.classList.add('hidden');
        fileInput.value = '';
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        dropZone.classList.add('bg-blue-100', 'border-blue-600');
        dropZone.classList.remove('border-blue-500');
    }

    function unhighlight(e) {
        dropZone.classList.remove('bg-blue-100', 'border-blue-600');
        dropZone.classList.add('border-blue-500');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];
            
            // Validasi ukuran file (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                return;
            }
            
            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Tipe file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.');
                return;
            }
            
            // Set file to input
            fileInput.files = files;
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                fileInfo.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                previewContainer.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
@endsection