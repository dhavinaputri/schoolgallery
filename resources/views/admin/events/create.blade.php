@extends('admin.layouts.app')

@section('title', 'Tambah Acara')

@section('content')
<div class="p-6 max-w-3xl">
    <a href="{{ route('admin.events.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Acara</h2>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-50 text-red-700">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
            <input type="text" name="location" value="{{ old('location') }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Gambar (opsional)</label>
            <input type="file" name="image" accept="image/*" class="w-full border rounded px-3 py-2">
            <p class="text-xs text-gray-500 mt-1">Rekomendasi rasio 16:9, ukuran maksimum 1MB.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mulai</label>
                <input type="datetime-local" name="start_at" value="{{ old('start_at') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Selesai (opsional)</label>
                <input type="datetime-local" name="end_at" value="{{ old('end_at') }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        <div class="flex items-center">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" id="is_published" name="is_published" value="1" class="mr-2" {{ old('is_published', true) ? 'checked' : '' }}>
            <label for="is_published" class="text-sm text-gray-700">Publikasikan</label>
        </div>
        <div class="text-right">
            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"><i class="fas fa-save mr-2"></i>Simpan</button>
        </div>
    </form>
</div>
@endsection


