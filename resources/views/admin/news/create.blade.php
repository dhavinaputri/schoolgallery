@extends('admin.layouts.app')

@section('title', 'Tambah Berita Baru')

@section('content')
<div class="bg-white rounded-xl shadow-md border-l-4 border-blue-500">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-newspaper text-blue-500 mr-3"></i> Tambah Berita Baru
                </h2>
                <p class="text-sm text-gray-500 mt-1">Buat konten berita baru untuk ditampilkan di website</p>
            </div>
            <a href="{{ route('admin.news.index') }}" class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-all duration-200 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>

        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Judul Berita -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-heading text-blue-500 mr-2"></i> Judul Berita
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('title') border-red-500 @enderror"
                            placeholder="Masukkan judul berita yang menarik" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Konten Berita -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-align-left text-blue-500 mr-2"></i> Isi Berita
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea name="content" id="content" rows="10" 
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('content') border-red-500 @enderror"
                            placeholder="Tulis isi berita lengkap disini..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Gambar Utama -->
                    <div class="bg-gradient-to-r from-blue-50 to-white p-5 rounded-lg shadow-sm border border-blue-100">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-image text-blue-500 mr-2"></i> Gambar Utama
                        </label>
                        <div id="dropZone" class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-blue-200 border-dashed rounded-lg bg-white hover:bg-blue-50 transition-all duration-300 cursor-pointer">
                            <div class="space-y-2 text-center">
                                <i class="fas fa-cloud-upload-alt text-blue-400 text-4xl"></i>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="image" class="relative cursor-pointer bg-blue-50 rounded-md font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-100 py-2 px-3 transition-colors duration-200 focus-within:outline-none">
                                        <span>Pilih File</span>
                                        <input id="image" name="image" type="file" class="sr-only" onchange="previewImage(this)" accept="image/*">
                                    </label>
                                    <p class="pl-2 flex items-center">atau drag & drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP (Maks. 5MB)</p>
                            </div>
                        </div>
                        <div class="mt-3" id="imagePreview"></div>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="news_category_id" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                            <i class="fas fa-tag text-blue-500 mr-2"></i> Kategori
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <select name="news_category_id" id="news_category_id" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 pl-10 @error('news_category_id') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('news_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-folder text-gray-400"></i>
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
                            <i class="fas fa-user-edit text-blue-500 mr-2"></i> Penulis
                            <span class="text-red-500 ml-1">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="author" id="author" value="{{ old('author', auth('admin')->user()->name) }}" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 pl-10 @error('author') border-red-500 @enderror"
                                placeholder="Nama penulis" required>
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
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
                        <div class="flex items-center justify-between">
                            <label for="is_published" class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-globe text-blue-500 mr-2"></i> Status Publikasi
                            </label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_published" id="is_published" value="1" class="sr-only peer" {{ old('is_published') ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">{{ old('is_published') ? 'Publikasikan' : 'Draft' }}</span>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i> Berita yang dipublikasikan akan langsung tampil di website
                        </p>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="pt-6">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                            <i class="fas fa-paper-plane mr-2"></i> Publikasikan Berita
                        </button>
                        <p class="text-center text-xs text-gray-500 mt-2">Pastikan semua data sudah terisi dengan benar</p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Toggle status publikasi label
    document.getElementById('is_published').addEventListener('change', function() {
        const statusLabel = this.nextElementSibling.nextElementSibling;
        statusLabel.textContent = this.checked ? 'Publikasikan' : 'Draft';
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
            previewImage(fileInput);
        }
    }

    // Preview gambar sebelum upload dengan animasi
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
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
            
            // Tampilkan loading spinner
            const loading = document.createElement('div');
            loading.className = 'flex items-center justify-center py-3';
            loading.innerHTML = '<i class="fas fa-spinner fa-spin text-blue-500 text-xl"></i><span class="ml-2 text-sm text-gray-600">Memproses gambar...</span>';
            preview.appendChild(loading);
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Hapus loading spinner
                preview.innerHTML = '';
                
                // Buat container untuk gambar
                const container = document.createElement('div');
                container.className = 'relative bg-gray-100 rounded-lg overflow-hidden border border-gray-200';
                
                // Buat gambar preview
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-full h-48 object-cover rounded-lg transition-opacity duration-300';
                img.style.opacity = '0';
                
                // Buat tombol hapus
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute top-2 right-2 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-md hover:bg-red-600 transition-colors';
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                removeBtn.onclick = function() {
                    container.remove();
                    document.getElementById('image').value = '';
                };
                
                // Buat info file
                const fileInfo = document.createElement('div');
                fileInfo.className = 'absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded';
                fileInfo.innerHTML = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
                
                // Tambahkan elemen ke container
                container.appendChild(img);
                container.appendChild(removeBtn);
                container.appendChild(fileInfo);
                preview.appendChild(container);
                
                // Animasi fade in
                setTimeout(() => {
                    img.style.opacity = '1';
                }, 100);
            }
            
            reader.readAsDataURL(file);
        }
    }

    // Inisialisasi text editor dengan konfigurasi yang lebih baik
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: {
                items: [
                    'heading', '|', 
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'blockQuote', 'insertTable', 'mediaEmbed', '|',
                    'undo', 'redo'
                ],
                shouldNotGroupWhenFull: true
            },
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraf', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h2', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h3', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h4', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            },
            placeholder: 'Tulis konten berita lengkap di sini...',
            table: {
                contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ]
            },
        })
        .then(editor => {
            console.log('Editor initialized', editor);
            // Tambahkan kelas untuk styling yang lebih baik
            const editorElement = document.querySelector('.ck-editor');
            if (editorElement) {
                editorElement.classList.add('mt-1');
            }
        })
        .catch(error => {
            console.error('Editor initialization error:', error);
        });
</script>
@endpush

@endsection
