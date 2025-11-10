@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2><i class="fas fa-users"></i> Data Karyawan</h2>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('karyawan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Karyawan
            </a>
            <a href="{{ route('karyawan.export') }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Filter & Search -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('karyawan.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-2 mb-2">
                    <label for="status" class="mr-2">Status:</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="cuti" {{ request('status') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="resign" {{ request('status') == 'resign' ? 'selected' : '' }}>Resign</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                    </select>
                </div>
                <div class="form-group mr-2 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari NIK, Nama, Telepon..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-info mr-2 mb-2">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary mb-2">
                    <i class="fas fa-sync"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Tabel Karyawan -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Foto</th>
                            <th width="12%">NIK</th>
                            <th width="20%">Nama Lengkap</th>
                            <th width="8%">JK</th>
                            <th width="13%">No. Telepon</th>
                            <th width="12%">Tgl Masuk</th>
                            <th width="10%">Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($karyawan as $key => $k)
                        <tr>
                            <td>{{ $karyawan->firstItem() + $key }}</td>
                            <td class="text-center">
                                @if($k->foto)
                                    <img src="{{ asset('storage/karyawan/' . $k->foto) }}" 
                                         alt="{{ $k->nama_lengkap }}" 
                                         class="img-thumbnail" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px; border-radius: 4px;">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $k->nik }}</td>
                            <td>{{ $k->nama_lengkap }}</td>
                            <td>{{ $k->jenis_kelamin }}</td>
                            <td>{{ $k->no_telepon ?? '-' }}</td>
                            <td>{{ $k->tanggal_masuk ? $k->tanggal_masuk->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($k->status == 'aktif')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($k->status == 'cuti')
                                    <span class="badge badge-warning">Cuti</span>
                                @elseif($k->status == 'resign')
                                    <span class="badge badge-danger">Resign</span>
                                @else
                                    <span class="badge badge-secondary">Non-aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('karyawan.show', $k->id) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('karyawan.edit', $k->id) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('karyawan.destroy', $k->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data karyawan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <i class="fas fa-info-circle"></i> Tidak ada data karyawan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div>
                    Menampilkan {{ $karyawan->firstItem() }} - {{ $karyawan->lastItem() }} dari {{ $karyawan->total() }} data
                </div>
                <div>
                    {{ $karyawan->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome untuk icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
.table-responsive {
    overflow-x: auto;
}
@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
    }
    .btn-group .btn {
        margin-bottom: 2px;
    }
}
</style>
@endsection