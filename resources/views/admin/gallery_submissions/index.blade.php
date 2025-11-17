@extends('admin.layouts.app')

@section('title', 'Pengajuan Galeri')

@section('content')
<div class="mb-6 flex items-center justify-between">
  <div>
    <h1 class="text-2xl font-extrabold text-gray-800 flex items-center">
      <span class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-2 rounded-lg mr-2 shadow"><i class="fas fa-inbox"></i></span>
      Pengajuan Galeri
    </h1>
    <p class="text-sm text-gray-500 mt-1">Kelola pengajuan foto dari pengguna sebelum dipublikasikan.</p>
  </div>
  <div class="flex items-center gap-2">
    <a href="{{ route('admin.galleries.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-white border shadow-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-images mr-2"></i>Galeri</a>
  </div>
</div>

@if(session('success'))
  <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 border border-green-200"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>
@endif

<div class="mb-4">
  @php $status = $status ?? request('status'); @endphp
  <div class="inline-flex rounded-lg border overflow-hidden">
    <a href="{{ route('admin.gallery-submissions.index') }}" class="px-4 py-2 text-sm {{ !$status ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">Semua</a>
    <a href="{{ route('admin.gallery-submissions.index', ['status'=>'pending']) }}" class="px-4 py-2 text-sm {{ $status==='pending' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">Pending</a>
    <a href="{{ route('admin.gallery-submissions.index', ['status'=>'approved']) }}" class="px-4 py-2 text-sm {{ $status==='approved' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">Approved</a>
    <a href="{{ route('admin.gallery-submissions.index', ['status'=>'rejected']) }}" class="px-4 py-2 text-sm {{ $status==='rejected' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }}">Rejected</a>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  @forelse($submissions as $sub)
  <div class="bg-white border rounded-xl p-4 shadow-sm hover:shadow transition">
    <div class="flex items-start gap-3">
      <div class="h-10 w-10 rounded-full overflow-hidden flex items-center justify-center bg-blue-100 text-blue-700 font-bold">
        @if(optional($sub->user)->avatar_url)
          <img src="{{ $sub->user->avatar_url }}" alt="avatar" class="w-full h-full object-cover">
        @else
          {{ strtoupper(substr($sub->user->name ?? 'U',0,1)) }}
        @endif
      </div>
      <div class="flex-1">
        <div class="flex items-center justify-between">
          <div>
            <p class="font-semibold text-gray-800 line-clamp-1">{{ $sub->title }}</p>
            <p class="text-xs text-gray-500">oleh {{ $sub->user->name ?? '-' }} • {{ $sub->created_at->diffForHumans() }}</p>
          </div>
          <span class="text-[10px] px-2 py-0.5 rounded-full {{ $sub->status==='pending' ? 'bg-amber-100 text-amber-700' : ($sub->status==='approved' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600') }}">{{ strtoupper($sub->status) }}</span>
        </div>
        <div class="mt-3 flex items-center justify-between text-sm">
          <span class="text-gray-600">Kategori: <strong>{{ $sub->kategori->nama ?? '-' }}</strong></span>
          <a href="{{ route('admin.gallery-submissions.show', $sub) }}" class="text-blue-600 hover:text-blue-800">Detail <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
        @if(($sub->images ?? collect())->count())
          <div class="mt-3 flex -space-x-2">
            @foreach($sub->images->take(4) as $img)
              <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($img->path) }}" class="h-10 w-10 object-cover rounded ring-2 ring-white border" alt="thumb">
            @endforeach
            @if($sub->images->count() > 4)
              <div class="h-10 w-10 rounded bg-gray-100 text-gray-700 text-xs flex items-center justify-center ring-2 ring-white">+{{ $sub->images->count()-4 }}</div>
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>
  @empty
  <div class="text-gray-500">Belum ada pengajuan.</div>
  @endforelse
</div>

<div class="mt-4">{{ $submissions->links() }}</div>
@endsection
