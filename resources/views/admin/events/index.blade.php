@extends('admin.layouts.app')

@section('title', 'Kelola Acara')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="bg-blue-600 text-white rounded-lg p-2 mr-3"><i class="fas fa-calendar-alt"></i></span>
                Kelola Acara
            </h1>
            <p class="text-gray-500 mt-1">Atur acara sekolah dengan tampilan modern yang konsisten</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.events.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-plus mr-2"></i>Tambah Acara
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="mb-4 flex items-center bg-green-50 text-green-700 border border-green-200 rounded-lg p-3">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-auto text-green-600" onclick="this.parentElement.style.display='none'"><i class="fas fa-times"></i></button>
        </div>
    @endif

    <!-- Toolbar -->
    <div class="bg-white border border-gray-100 rounded-xl p-4 mb-4 flex flex-col md:flex-row gap-3 items-center justify-between">
        <div class="relative w-full md:w-72">
            <input id="searchEvent" type="text" placeholder="Cari acara..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <select id="filterStatus" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Status</option>
                <option value="published">Dipublikasikan</option>
                <option value="draft">Draft</option>
            </select>
            <select id="filterMonth" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Bulan</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ sprintf('%02d',$m) }}">{{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}</option>
                @endfor
            </select>
        </div>
    </div>

    <!-- Event Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="eventCards">
        @forelse($events as $event)
        <div class="event-card bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300" 
             data-status="{{ $event->is_published ? 'published' : 'draft' }}" 
             data-month="{{ $event->start_at->format('m') }}">
            
            <!-- Image / Placeholder -->
            <div class="relative h-48 bg-gradient-to-br from-blue-50 to-blue-100 overflow-hidden">
                @if($event->image)
                    <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-6xl text-blue-300"></i>
                    </div>
                @endif
                
                <!-- Date Badge -->
                <div class="absolute top-3 left-3 bg-white rounded-lg shadow-md px-3 py-2 text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $event->start_at->format('d') }}</div>
                    <div class="text-xs text-gray-600 uppercase">{{ $event->start_at->translatedFormat('M') }}</div>
                </div>
                
                <!-- Status Badge -->
                <div class="absolute top-3 right-3">
                    <form action="{{ route('admin.events.toggle-publish', $event) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="px-3 py-1.5 rounded-full text-xs font-medium border transition {{ $event->is_published ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300' }}">
                            <i class="fas {{ $event->is_published ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                            {{ $event->is_published ? 'Published' : 'Draft' }}
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-5">
                <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">{{ $event->title }}</h3>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-clock text-blue-500 w-5"></i>
                        <span>{{ $event->start_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($event->end_at)
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-hourglass-end text-blue-500 w-5"></i>
                        <span>{{ $event->end_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt text-blue-500 w-5"></i>
                        <span>{{ $event->location ?? 'Lokasi belum ditentukan' }}</span>
                    </div>
                </div>
                
                @if($event->description)
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $event->description }}</p>
                @endif
                
                <!-- Actions -->
                <div class="flex gap-2">
                    <a href="{{ route('admin.events.edit', $event) }}" class="flex-1 text-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="flex-1" onsubmit="return confirm('Hapus acara ini? Tindakan tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button class="w-full px-3 py-2 bg-white text-red-600 border border-red-500 rounded-lg hover:bg-red-50 transition text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-dashed border-gray-300">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 mb-4">
                    <i class="fas fa-calendar-alt text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Acara</h3>
                <p class="text-gray-600 mb-6">Mulai dengan menambahkan acara pertama untuk sekolah Anda</p>
                <a href="{{ route('admin.events.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Acara Pertama
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $events->links() }}</div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const q = document.getElementById('searchEvent');
    const status = document.getElementById('filterStatus');
    const month = document.getElementById('filterMonth');
    const cards = document.querySelectorAll('#eventCards .event-card');

    function applyFilters(){
        const term = (q.value || '').toLowerCase();
        const st = status.value;
        const mo = month.value;
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            const cardStatus = card.getAttribute('data-status');
            const cardMonth = card.getAttribute('data-month');
            const okText = term === '' || text.includes(term);
            const okStatus = st === '' || st === cardStatus;
            const okMonth = mo === '' || mo === cardMonth;
            card.style.display = (okText && okStatus && okMonth) ? '' : 'none';
        });
    }
    q.addEventListener('input', applyFilters);
    status.addEventListener('change', applyFilters);
    month.addEventListener('change', applyFilters);
});
</script>
@endpush
@endsection


