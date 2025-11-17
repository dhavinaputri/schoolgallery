@extends('admin.layouts.app')

@section('title', 'Detail Guru: ' . $teacher->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Guru: {{ $teacher->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            @if($teacher->image)
                                <img src="{{ \App\Helpers\StorageHelper::getStorageUrl($teacher->image) }}" alt="{{ $teacher->name }}" class="img-fluid rounded-circle mb-3" style="max-width: 200px;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 200px; height: 200px;">
                                    <i class="fas fa-user text-white" style="font-size: 100px;"></i>
                                </div>
                            @endif
                            
                            <h4>{{ $teacher->name }}</h4>
                            <h5 class="text-muted">{{ $teacher->position }}</h5>
                            
                            <div class="social-links mt-3">
                                @if($teacher->facebook)
                                    <a href="{{ $teacher->facebook }}" target="_blank" class="btn btn-primary btn-sm mb-1">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                @endif
                                @if($teacher->twitter)
                                    <a href="{{ $teacher->twitter }}" target="_blank" class="btn btn-info btn-sm mb-1">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                @endif
                                @if($teacher->instagram)
                                    <a href="{{ $teacher->instagram }}" target="_blank" class="btn btn-danger btn-sm mb-1" style="background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); border: none;">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                @endif
                                @if($teacher->linkedin)
                                    <a href="{{ $teacher->linkedin }}" target="_blank" class="btn btn-primary btn-sm mb-1" style="background-color: #0077b5;">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                @endif
                            </div>
                            
                            <div class="mt-3">
                                <span class="badge {{ $teacher->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $teacher->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                <span class="badge bg-info">
                                    Urutan: {{ $teacher->order }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h5>Deskripsi</h5>
                            @if($teacher->description)
                                <p class="text-justify">{{ $teacher->description }}</p>
                            @else
                                <p class="text-muted">Tidak ada deskripsi tersedia.</p>
                            @endif
                            
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Informasi Tambahan</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Dibuat Pada</th>
                                            <td>{{ $teacher->created_at->format('d F Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Diperbarui Pada</th>
                                            <td>{{ $teacher->updated_at->format('d F Y H:i') }}</td>
                                        </tr>
                                        @if($teacher->deleted_at)
                                        <tr class="table-danger">
                                            <th>Dihapus Pada</th>
                                            <td>{{ $teacher->deleted_at->format('d F Y H:i') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@endsection
