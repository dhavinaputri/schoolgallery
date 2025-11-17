@extends('admin.layouts.app')

@section('title', 'Daftar Guru')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Daftar Guru</h2>
        <div class="flex-1 md:max-w-md md:ml-auto">
            <form method="GET" action="{{ route('admin.teachers.index') }}" id="adminTeacherSearchForm">
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input name="q" value="{{ request('q') }}" type="text" placeholder="Cari guru..." class="w-full border border-gray-300 rounded-lg pl-9 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <button type="button" id="clearAdminSearch" class="hidden absolute inset-y-0 right-2 my-auto h-8 w-8 rounded-full text-gray-400 hover:text-gray-600 flex items-center justify-center" aria-label="Bersihkan">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </form>
        </div>
        <a href="{{ route('admin.teachers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Tambah Guru
        </a>
    </div>
    <div class="flex items-center justify-between mb-4">
        <div class="text-sm text-gray-600">Menampilkan <span class="font-medium">{{ $teachers->count() }}</span> dari <span class="font-medium">{{ $teachers->total() }}</span> guru</div>
        @if(request('q'))
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1 rounded-full text-sm">
                    <i class="fas fa-filter"></i>
                    {{ request('q') }}
                </span>
                <a href="{{ route('admin.teachers.index') }}" class="text-sm text-gray-600 hover:text-gray-800 underline">Bersihkan</a>
            </div>
        @endif
    </div>
    <script>
        (function(){
            const form = document.getElementById('adminTeacherSearchForm');
            if (!form) return;
            const input = form.querySelector('input[name="q"]');
            const clearBtn = document.getElementById('clearAdminSearch');
            let t;

            function toggleClear(){
                if (!clearBtn) return;
                if (input.value && input.value.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            }

            input && input.addEventListener('input', function(){
                toggleClear();
                clearTimeout(t);
                t = setTimeout(()=>{ form.submit(); }, 300);
            });

            clearBtn && clearBtn.addEventListener('click', function(){
                input.value = '';
                toggleClear();
                form.submit();
            });

            // Initialize on load
            toggleClear();
        })();
    </script>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 text-green-700 border border-green-200 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($teachers->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-chalkboard-teacher text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500">Belum ada data guru</p>
            <a href="{{ route('admin.teachers.create') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800">
                <i class="fas fa-plus mr-1"></i> Tambah guru sekarang
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($teachers as $teacher)
            <div class="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                        <div class="relative">
                            @if($teacher->image)
                                <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($teacher->image) }}" alt="{{ $teacher->name }}" class="w-full h-48 object-cover object-top">
                            @else
                                <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-user text-5xl text-gray-300"></i>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="text-xs px-2 py-1 rounded-full {{ $teacher->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $teacher->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800">{{ $teacher->name }}</h3>
                            <p class="text-blue-600 text-sm mb-2">{{ $teacher->position }}</p>
                            @if($teacher->description)
                                <p class="text-gray-600 text-sm line-clamp-2">{{ Str::limit($teacher->description, 100) }}</p>
                            @endif
                            <div class="flex justify-between items-center text-sm text-gray-500 mt-3">
                                <span class="inline-flex items-center"><i class="fas fa-sort-numeric-down mr-1"></i> Urutan: {{ $teacher->order }}</span>
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $teachers->appends(request()->only('q'))->links() }}
        </div>
    @endif
</div>
@endsection
