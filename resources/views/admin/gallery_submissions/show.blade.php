@extends('admin.layouts.app')

@section('title', 'Detail Pengajuan Galeri')

@section('content')
<div class="mb-6 flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-extrabold text-gray-800 flex items-center">
      <span class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-2 rounded-lg mr-2 shadow"><i class="fas fa-file-image"></i></span>
      Detail Pengajuan
    </h1>
    <p class="text-sm text-gray-500 mt-1">Periksa detail sebelum approve atau reject.</p>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2 space-y-6">
    <div class="bg-white border rounded-xl p-5">
      <h3 class="font-semibold text-gray-800 mb-4">Informasi Pengajuan</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div>
          <div class="text-gray-500">Judul</div>
          <div class="font-medium">{{ $submission->title }}</div>
        </div>
        <div>
          <div class="text-gray-500">Kategori</div>
          <div class="font-medium">{{ $submission->kategori->nama ?? '-' }}</div>
        </div>
        <div>
          <div class="text-gray-500">Pengaju</div>
          <div class="mt-1 flex items-center gap-2 font-medium">
            <span class="inline-flex h-8 w-8 rounded-full overflow-hidden items-center justify-center bg-blue-100 text-blue-700 text-sm">
              @if(optional($submission->user)->avatar_url)
                <img src="{{ $submission->user->avatar_url }}" alt="avatar" class="w-full h-full object-cover">
              @else
                {{ strtoupper(substr($submission->user->name ?? 'U',0,1)) }}
              @endif
            </span>
            <span>{{ $submission->user->name ?? '-' }} (<span class="text-gray-600">{{ $submission->user->email ?? '-' }}</span>)</span>
          </div>
        </div>
        <div>
          <div class="text-gray-500">Dikirim</div>
          <div class="font-medium">{{ $submission->created_at->format('d M Y H:i') }}</div>
        </div>
      </div>
      @if($submission->description)
      <div class="mt-4">
        <div class="text-gray-500 text-sm mb-1">Deskripsi</div>
        <p class="text-gray-800">{{ $submission->description }}</p>
      </div>
      @endif
    </div>

    <div class="bg-white border rounded-xl p-5">
      <div class="flex items-center justify-between mb-2">
        <h3 class="font-semibold text-gray-800">Gambar Pengajuan</h3>
        <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700">{{ $submission->images->count() }} file</span>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($submission->images as $img)
          @php $pub = ($publishedByImage ?? collect())->get($img->id); @endphp
          <div class="rounded-xl overflow-hidden border bg-white shadow-sm">
            <div class="relative">
              <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($img->path) }}" class="w-full h-56 object-cover" alt="image">
              <div class="absolute top-2 left-2 flex items-center gap-2">
                <span class="text-[10px] px-2 py-0.5 rounded-full {{ $pub ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                  {{ $pub ? 'Published' : 'Submitted' }}
                </span>
                @if($submission->kategori)
                  <span class="text-[10px] px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">{{ $submission->kategori->nama }}</span>
                @endif
              </div>
            </div>
            <div class="p-3 text-sm">
              <div class="text-xs text-gray-500 mb-2 flex items-center justify-between">
                <span class="line-clamp-1">{{ $img->original_name }}</span>
                <span>{{ number_format($img->size/1024, 0) }} KB</span>
              </div>

              @if($pub)
                <div class="flex items-center gap-2">
                  <form action="{{ route('admin.galleries.toggle-publish', $pub) }}" method="POST" onsubmit="return confirm('Sembunyikan (unpublish) foto ini?')">
                    @csrf
                    @method('PATCH')
                    <button class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-800 inline-flex items-center">
                      <i class="fas fa-eye-slash mr-1"></i> Unpublish
                    </button>
                  </form>
                  <form action="{{ route('admin.galleries.destroy', $pub) }}" method="POST" onsubmit="return confirm('Hapus foto ini dari galeri?')" class="ml-auto">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1.5 rounded-full bg-red-500 hover:bg-red-600 text-white inline-flex items-center">
                      <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                  </form>
                </div>
              @else
                <form action="{{ route('admin.gallery-submissions.publish', [$submission, $img]) }}" method="POST" onsubmit="return confirm('Publikasikan foto ini ke galeri?')">
                  @csrf
                  <button class="px-3 py-1.5 rounded-full bg-blue-600 hover:bg-blue-700 text-white inline-flex items-center">
                    <i class="fas fa-upload mr-1"></i> Publish
                  </button>
                </form>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <div class="space-y-6">
    <div class="bg-white border rounded-xl p-5">
      <h3 class="font-semibold text-gray-800 mb-4">Aksi</h3>
      @if($submission->status === 'pending')
        <form action="{{ route('admin.gallery-submissions.approve', $submission) }}" method="POST" class="mb-3" onsubmit="return confirm('Setujui pengajuan ini dan publikasikan ke galeri?')">
          @csrf
          <button class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow">
            <i class="fas fa-check mr-2"></i> Approve & Publish
          </button>
        </form>
        <form action="{{ route('admin.gallery-submissions.reject', $submission) }}" method="POST" onsubmit="return confirm('Tolak pengajuan ini?')">
          @csrf
          <textarea name="reason" rows="3" class="w-full border rounded-lg px-3 py-2 mb-2" placeholder="Alasan penolakan" required></textarea>
          <button class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800">
            <i class="fas fa-times mr-2"></i> Reject
          </button>
        </form>
      @else
        <div class="text-sm text-gray-600">
          Status: <span class="px-2 py-0.5 rounded-full text-xs {{ $submission->status==='approved' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">{{ strtoupper($submission->status) }}</span>
          @if($submission->reviewed_at)
            <div class="mt-2">Direview: {{ $submission->reviewed_at->format('d M Y H:i') }}</div>
          @endif
          @if($submission->reject_reason)
            <div class="mt-2">Alasan: {{ $submission->reject_reason }}</div>
          @endif
        </div>
      @endif
    </div>

    
  </div>
</div>
@endsection
