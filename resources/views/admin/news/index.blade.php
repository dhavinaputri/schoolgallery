@extends('admin.layouts.app')

@section('title', 'Manajemen Berita')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-newspaper text-blue-500 mr-2"></i> Manajemen Berita
        </h1>
        <p class="text-sm text-gray-500 mt-1">Kelola semua berita dan artikel sekolah</p>
    </div>
    <a href="{{ route('admin.news.create') }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg flex items-center shadow-md hover:shadow-lg transition duration-200">
        <i class="fas fa-plus mr-2"></i> Tambah Berita
    </a>
</div>

@if(session('success'))
    <div id="success-alert" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm flex items-center animate__animated animate__fadeIn" role="alert">
        <i class="fas fa-check-circle text-green-500 mr-2 text-lg"></i>
        <div>
            <p class="font-medium">Berhasil!</p>
            <p>{{ session('success') }}</p>
        </div>
        <button type="button" class="ml-auto text-green-500 hover:text-green-700" onclick="document.getElementById('success-alert').remove()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <script>
        setTimeout(() => {
            const alert = document.getElementById('success-alert');
            if(alert) alert.classList.add('animate__fadeOut');
            setTimeout(() => { if(alert) alert.remove(); }, 500);
        }, 5000);
    </script>
@endif

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeOut {
        from { opacity: 1; transform: translateY(0); }
        to { opacity: 0; transform: translateY(-10px); }
    }
    .animate__animated { animation-duration: 0.5s; }
    .animate__fadeIn { animation-name: fadeIn; }
    .animate__fadeOut { animation-name: fadeOut; }
</style>

<div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-list text-white"></i>
                    <h2 class="text-lg font-semibold text-white">Daftar Berita</h2>
                    <span class="bg-white text-blue-600 text-xs px-2 py-1 rounded-full font-medium">{{ $news->total() }} item</span>
                </div>
            </div>
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                <div class="relative flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-white text-opacity-70"></i>
                    </div>
                    <input type="text" id="searchInput" class="block w-full pl-10 pr-3 py-2 border border-blue-400 rounded-lg bg-blue-100 bg-opacity-20 text-white placeholder-blue-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50" placeholder="Cari judul berita...">
                </div>
                <div class="flex space-x-2">
                    <select id="categoryFilter" class="block w-full md:w-auto px-3 py-2 border border-blue-400 rounded-lg bg-blue-100 bg-opacity-20 text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                        <option value="">Semua Kategori</option>
                        @foreach(\App\Models\NewsCategory::active()->ordered()->get() as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select id="statusFilter" class="block w-full md:w-auto px-3 py-2 border border-blue-400 rounded-lg bg-blue-100 bg-opacity-20 text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                        <option value="">Semua Status</option>
                        <option value="Dipublikasi">Dipublikasi</option>
                        <option value="Draft">Draft</option>
                    </select>
                </div>
            </div>
        </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider flex items-center">
                        <i class="fas fa-heading text-blue-400 mr-2"></i> Judul
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-tag text-blue-400 mr-2"></i> Kategori
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-globe text-blue-400 mr-2"></i> Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-calendar text-blue-400 mr-2"></i> Tanggal
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-cog text-blue-400 mr-2"></i> Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($news as $item)
                <tr class="hover:bg-blue-50 transition duration-150">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-14 w-14">
                                @if($item->image)
                                    <img class="h-14 w-14 rounded-md object-cover shadow-sm hover:shadow transition duration-200" src="{{ \App\Helpers\StorageHelper::getStorageUrl($item->image) }}" alt="{{ $item->title }}">
                                @else
                                    <div class="h-14 w-14 rounded-md bg-gray-200 flex items-center justify-center text-gray-400 shadow-sm">
                                        <i class="fas fa-newspaper text-lg"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('admin.news.show', $item) }}" class="text-sm font-medium text-gray-900 hover:text-blue-600 transition duration-150">{{ $item->title }}</a>
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit(strip_tags($item->content), 60) }}</div>
                                <div class="text-xs text-gray-400 mt-1 flex items-center gap-3">
                                    <span><i class="fas fa-user mr-1"></i> {{ $item->author }}</span>
                                    <a href="{{ route('admin.news.show', $item) }}#comments" class="inline-flex items-center px-2 py-0.5 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 transition" title="Lihat komentar">
                                        <i class="far fa-comment mr-1"></i>{{ $item->comments()->count() }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 shadow-sm">
                            <i class="fas fa-tag mr-1"></i> {{ $item->newsCategory->name ?? 'Tanpa Kategori' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->is_published)
                            <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 shadow-sm">
                                <i class="fas fa-check-circle mr-1"></i> Dipublikasi
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 shadow-sm">
                                <i class="fas fa-clock mr-1"></i> Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500 flex items-center">
                            <i class="fas fa-calendar-alt text-blue-400 mr-2"></i>
                            {{ $item->created_at->translatedFormat('d M Y') }}
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            {{ $item->created_at->diffForHumans() }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.news.edit', $item->id) }}" class="bg-blue-100 text-blue-600 hover:bg-blue-200 p-2 rounded-lg transition duration-150" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.news.toggle-publish', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-{{ $item->is_published ? 'yellow' : 'green' }}-100 text-{{ $item->is_published ? 'yellow' : 'green' }}-600 hover:bg-{{ $item->is_published ? 'yellow' : 'green' }}-200 p-2 rounded-lg transition duration-150" title="{{ $item->is_published ? 'Unpublish' : 'Publish' }}">
                                    <i class="fas fa-{{ $item->is_published ? 'eye-slash' : 'eye' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-lg transition duration-150" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <i class="fas fa-newspaper text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">Belum ada berita</p>
                            <p class="text-sm">Silakan tambahkan berita baru dengan mengklik tombol 'Tambah Berita'</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $news->firstItem() ?? 0 }} - {{ $news->lastItem() ?? 0 }} dari {{ $news->total() }} berita
                    </div>
                    <div class="pagination-container">
                        {{ $news->links() }}
                    </div>
                </div>
            </div>
            
            <style>
                /* Pagination styling */
                .pagination-container nav div:first-child { display: none; }
                .pagination-container nav span.relative, .pagination-container nav a.relative {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 2rem;
                    min-height: 2rem;
                    padding: 0.25rem 0.75rem;
                    margin: 0 0.25rem;
                    border-radius: 0.375rem;
                    font-weight: 500;
                    transition: all 0.15s ease-in-out;
                }
                .pagination-container nav span.relative.bg-white {
                    background: linear-gradient(135deg, #3b82f6, #2563eb);
                    color: white;
                    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
                }
                .pagination-container nav a.relative:hover {
                    background-color: #e5e7eb;
                }
            </style>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk filter tabel berita
        function filterTable() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const categoryValue = document.getElementById('categoryFilter').value;
            const statusValue = document.getElementById('statusFilter').value;
            
            const rows = document.querySelectorAll('tbody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                if (row.querySelector('td:nth-child(5)')) { // Pastikan ini adalah baris data (bukan baris "tidak ada data")
                    const title = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const category = row.querySelector('td:nth-child(2)').textContent.trim();
                    const status = row.querySelector('td:nth-child(3)').textContent.trim();
                    
                    const matchesSearch = title.includes(searchValue);
                    const matchesCategory = categoryValue === '' || category.includes(categoryValue);
                    const matchesStatus = statusValue === '' || status.includes(statusValue);
                    
                    const isVisible = matchesSearch && matchesCategory && matchesStatus;
                    
                    row.style.display = isVisible ? '' : 'none';
                    if (isVisible) visibleCount++;
                }
            });
            
            // Tampilkan pesan jika tidak ada hasil
            const emptyRow = document.getElementById('emptyResultRow');
            if (visibleCount === 0) {
                if (!emptyRow) {
                    const tbody = document.querySelector('tbody');
                    const newRow = document.createElement('tr');
                    newRow.id = 'emptyResultRow';
                    newRow.innerHTML = `
                        <td colspan="5" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <i class="fas fa-search text-4xl mb-3 text-gray-300"></i>
                                <p class="text-lg font-medium">Tidak ada hasil</p>
                                <p class="text-sm">Coba ubah filter pencarian Anda</p>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(newRow);
                }
            } else if (emptyRow) {
                emptyRow.remove();
            }
        }
        
        // Event listeners untuk input pencarian dan filter
        document.getElementById('searchInput').addEventListener('input', filterTable);
        document.getElementById('categoryFilter').addEventListener('change', filterTable);
        document.getElementById('statusFilter').addEventListener('change', filterTable);
        
        // Animasi untuk baris tabel
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 50 * index);
        });
        
        // Konfirmasi hapus dengan animasi
        const deleteForms = document.querySelectorAll('form[action*="destroy"]');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const row = this.closest('tr');
                
                if (confirm('Apakah Anda yakin ingin menghapus berita ini?')) {
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    
                    setTimeout(() => {
                        this.submit();
                    }, 300);
                }
            });
        });
    });
</script>
@endsection
