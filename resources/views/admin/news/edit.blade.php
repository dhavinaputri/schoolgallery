@extends('admin.layouts.app')

@section('title', 'Edit Berita')

@section('content')
<div class="bg-white rounded-xl shadow-sm border-t-4 border-blue-500 border-r border-l border-b">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-newspaper text-blue-500 mr-2"></i> Edit Berita
                    <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Form</span>
                </h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui informasi berita yang sudah ada</p>
            </div>
            <a href="{{ route('admin.news.index') }}" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-3 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Judul Berita -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-heading text-blue-500 mr-1"></i> Judul Berita
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="title" id="title" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('title') border-red-500 @enderror pl-10"
                                placeholder="Masukkan judul berita" value="{{ old('title', $news->title) }}" required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-pen text-gray-400"></i>
                            </div>
                        </div>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Konten Berita -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-align-left text-blue-500 mr-1"></i> Isi Berita
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea name="content" id="content" rows="10" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('content') border-red-500 @enderror"
                            placeholder="Tulis isi berita disini..." required>{{ old('content', $news->content) }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Gambar Utama -->
                    <div class="bg-gradient-to-br from-blue-50 to-white p-4 rounded-lg shadow-sm border border-blue-100">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-image text-blue-500 mr-1"></i> Gambar Utama
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        
                        @if($news->image)
                            <div class="mb-4">
                                <div class="relative group">
                                    <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($news->image) }}" alt="{{ $news->title }}" class="w-full h-48 object-cover rounded-lg mb-2 shadow-sm group-hover:opacity-90 transition duration-200">
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                                        <span class="bg-black bg-opacity-50 text-white px-3 py-1 rounded-lg text-sm">Gambar saat ini</span>
                                    </div>
                                </div>
                                <div class="flex items-center bg-red-50 p-2 rounded-lg">
                                    <input type="checkbox" name="remove_image" id="remove_image" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <label for="remove_image" class="ml-2 block text-sm text-red-700 flex items-center">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus gambar ini
                                    </label>
                                </div>
                            </div>
                        @endif
                        
                        <div id="dropZone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-blue-200 border-dashed rounded-md hover:bg-blue-50 transition duration-200 cursor-pointer">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-blue-400 text-4xl mb-3"></i>
                                <div class="flex flex-col text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-blue-500 text-white py-2 px-4 rounded-md font-medium hover:bg-blue-600 transition duration-200 mx-auto mb-2">
                                        <i class="fas fa-file-image mr-1"></i> Pilih File
                                        <input id="image" name="image" type="file" class="sr-only" onchange="previewImage(this, 'imagePreview')" accept="image/*">
                                    </label>
                                    <p class="text-gray-500">atau drag and drop file disini</p>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Format: PNG, JPG, GIF, WEBP (Maksimal 5MB)</p>
                            </div>
                        </div>
                        <div class="mt-2" id="imagePreview"></div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="news_category_id" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-tag text-blue-500 mr-1"></i> Kategori
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="news_category_id" id="news_category_id" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('news_category_id') border-red-500 @enderror pl-10 appearance-none" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('news_category_id', $news->news_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-folder text-gray-400"></i>
                            </div>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </div>
                        </div>
                        @error('news_category_id')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Penulis -->
                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-user-edit text-blue-500 mr-1"></i> Penulis
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="author" id="author" value="{{ old('author', $news->author) }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('author') border-red-500 @enderror pl-10"
                                placeholder="Nama penulis" required>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                        @error('author')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status Publikasi -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-globe text-blue-500 mr-1"></i> Status Publikasi
                        </label>
                        <div class="flex items-center">
                            <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="is_published" id="is_published" value="1" 
                                    class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                    {{ old('is_published', $news->is_published) ? 'checked' : '' }}>
                                <label for="is_published" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>
                            <label for="is_published" class="text-sm text-gray-700" id="publish-status-label">
                                {{ old('is_published', $news->is_published) ? 'Dipublikasikan' : 'Draft' }}
                            </label>
                            <span class="ml-2 text-xs {{ old('is_published', $news->is_published) ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} px-2 py-1 rounded-full" id="status-badge">
                                {{ old('is_published', $news->is_published) ? 'Live' : 'Tersimpan' }}
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="pt-4 flex space-x-3">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center shadow-md hover:shadow-lg transition duration-200">
                            <i class="fas fa-sync-alt mr-2"></i> Perbarui Berita
                        </button>
                        <a href="{{ route('admin.news.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-4 rounded-lg flex items-center justify-center transition duration-200">
                            <i class="fas fa-times mr-1"></i> Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
    /* Toggle Switch Styling */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #3B82F6;
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #3B82F6;
    }
    .toggle-label {
        transition: background-color 0.2s ease-in;
    }
    
    /* Editor Styling */
    .ck-editor__editable {
        min-height: 300px;
        max-height: 500px;
        border-radius: 0.5rem !important;
    }
    .ck-toolbar {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    .ck-content {
        font-size: 1rem;
        line-height: 1.5;
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle status publikasi
    document.addEventListener('DOMContentLoaded', function() {
        const toggleCheckbox = document.getElementById('is_published');
        const statusLabel = document.getElementById('publish-status-label');
        const statusBadge = document.getElementById('status-badge');
        
        toggleCheckbox.addEventListener('change', function() {
            if (this.checked) {
                statusLabel.textContent = 'Dipublikasikan';
                statusBadge.textContent = 'Live';
                statusBadge.className = 'ml-2 text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full';
            } else {
                statusLabel.textContent = 'Draft';
                statusBadge.textContent = 'Tersimpan';
                statusBadge.className = 'ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full';
            }
        });
    });

    // Drag and Drop functionality
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('image');

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

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
        dropZone.classList.remove('border-blue-200');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
        dropZone.classList.add('border-blue-200');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            previewImage(fileInput, 'imagePreview');
        }
    }

    // Preview gambar sebelum upload dengan animasi
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validasi ukuran file (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                input.value = '';
                return;
            }
            
            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Tipe file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.');
                input.value = '';
                return;
            }
            
            // Tambahkan loading spinner
            const spinner = document.createElement('div');
            spinner.className = 'flex justify-center items-center my-4';
            spinner.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-500 text-2xl"></i>';
            preview.appendChild(spinner);
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Hapus spinner
                preview.innerHTML = '';
                
                // Buat container untuk gambar dan tombol hapus
                const container = document.createElement('div');
                container.className = 'relative mt-3 bg-white p-1 rounded-lg shadow-sm border border-blue-100';
                
                // Buat gambar preview
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-48 object-cover rounded-lg transition duration-300 ease-in-out transform hover:scale-[1.02]';
                container.appendChild(img);
                
                // Tambahkan tombol hapus
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-sm hover:bg-red-600 transition duration-200';
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                removeBtn.onclick = function() {
                    container.remove();
                    input.value = '';
                };
                container.appendChild(removeBtn);
                
                // Tambahkan info file
                const fileInfo = document.createElement('div');
                fileInfo.className = 'text-xs text-gray-500 mt-2 flex items-center justify-between';
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                fileInfo.innerHTML = `
                    <span class="flex items-center"><i class="fas fa-file-image text-blue-400 mr-1"></i> ${fileName}</span>
                    <span>${fileSize} MB</span>
                `;
                container.appendChild(fileInfo);
                
                preview.appendChild(container);
            }
            
            reader.readAsDataURL(file);
        }
    }

    // Inisialisasi text editor dengan konfigurasi yang lebih lengkap
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: {
                items: [
                    'heading', '|', 
                    'bold', 'italic', 'strikethrough', 'underline', 'code', '|', 
                    'link', 'bulletedList', 'numberedList', '|', 
                    'outdent', 'indent', '|',
                    'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', '|', 
                    'undo', 'redo', '|',
                    'alignment', 'fontColor', 'fontBackgroundColor'
                ],
                shouldNotGroupWhenFull: true
            },
            placeholder: 'Tulis konten berita lengkap di sini...',
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h2', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h3', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h4', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn', 'tableRow', 'mergeTableCells',
                    'tableProperties', 'tableCellProperties'
                ]
            },
            image: {
                toolbar: [
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side',
                    '|',
                    'toggleImageCaption',
                    'imageTextAlternative'
                ]
            },
            fontColor: {
                colors: [
                    { color: '#000000', label: 'Black' },
                    { color: '#4D4D4D', label: 'Dim grey' },
                    { color: '#999999', label: 'Grey' },
                    { color: '#E6E6E6', label: 'Light grey' },
                    { color: '#FFFFFF', label: 'White' },
                    { color: '#E64C4C', label: 'Red' },
                    { color: '#E6994C', label: 'Orange' },
                    { color: '#E6E64C', label: 'Yellow' },
                    { color: '#4CE64C', label: 'Green' },
                    { color: '#4C4CE6', label: 'Blue' }
                ]
            },
            fontBackgroundColor: {
                colors: [
                    { color: '#000000', label: 'Black' },
                    { color: '#4D4D4D', label: 'Dim grey' },
                    { color: '#999999', label: 'Grey' },
                    { color: '#E6E6E6', label: 'Light grey' },
                    { color: '#FFFFFF', label: 'White' },
                    { color: '#E64C4C', label: 'Red' },
                    { color: '#E6994C', label: 'Orange' },
                    { color: '#E6E64C', label: 'Yellow' },
                    { color: '#4CE64C', label: 'Green' },
                    { color: '#4C4CE6', label: 'Blue' }
                ]
            }
        })
        .catch(error => {
            console.error('Error initializing editor:', error);
        });
</script>
@endpush

@endsection
