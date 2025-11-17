@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Log Aktivitas (Non-PII)</h1>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Waktu</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                    <th class="px-4 py-2 text-left">Deskripsi</th>
                    <th class="px-4 py-2 text-left">Admin/User ID</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="border-t">
                    <td class="px-4 py-2 whitespace-nowrap">{{ $log->created_at }}</td>
                    <td class="px-4 py-2">{{ $log->action }}</td>
                    <td class="px-4 py-2">{{ Str::limit($log->description, 120) }}</td>
                    <td class="px-4 py-2">{{ $log->admin_id ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada aktivitas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
</div>
@endsection


