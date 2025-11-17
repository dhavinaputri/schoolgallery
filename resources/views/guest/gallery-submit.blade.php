@extends('layouts.app')

@section('title', 'Kirim Foto Kegiatan')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <span class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-2 rounded-lg mr-2 shadow"><i class="fas fa-paper-plane"></i></span>
            Kirim Foto Kegiatan
        </h1>
        <p class="text-sm text-gray-500 mt-1">Bagikan momenmu. Maksimal 2 foto, ukuran ≤ 3MB per foto. Pengajuan akan direview admin.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg border border-red-200">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border rounded-xl shadow-sm p-6">
        <form action="{{ route('gallery.submit.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" class="w-full border rounded-lg px-3 py-2 bg-gray-100" value="{{ $user->name }}" disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="text" class="w-full border rounded-lg px-3 py-2 bg-gray-100" value="{{ $user->email }}" disabled>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="kategori_id" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="" disabled selected>Pilih kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('kategori_id')==$cat->id?'selected':'' }}>{{ $cat->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input type="text" name="title" class="w-full border rounded-lg px-3 py-2" value="{{ old('title') }}" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="4" class="w-full border rounded-lg px-3 py-2" placeholder="Ceritakan singkat tentang fotomu (opsional)">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unggah Foto (maks 2, ≤ 3MB per foto)</label>
                <input id="images" name="images[]" type="file" accept="image/jpeg,image/png,image/webp" multiple class="w-full border rounded-lg px-3 py-2">
                <p class="text-xs text-gray-500 mt-1">Hanya jpeg, png, webp. Maksimal 2 file.</p>
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('gallery') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800"><i class="fas fa-arrow-left mr-2"></i>Kembali ke Galeri</a>
                <button type="submit" class="inline-flex items-center px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow"><i class="fas fa-paper-plane mr-2"></i>Kirim</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
  const input = document.getElementById('images');
  input?.addEventListener('change', function(){
    if(this.files.length > 2){
      alert('Maksimal 2 file.');
      this.value = '';
    }
    for (const f of this.files){
      if (f.size > 3 * 1024 * 1024){
        alert('Ukuran tiap file maksimal 3MB.');
        this.value = '';
        break;
      }
    }
  })
</script>
@endpush
@endsection
