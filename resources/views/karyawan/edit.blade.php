@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus"></i> 
                        {{ isset($karyawan) ? 'Edit Karyawan' : 'Tambah Karyawan Baru' }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ isset($karyawan) ? route('karyawan.update', $karyawan->id) : route('karyawan.store') }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @if(isset($karyawan))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <!-- Foto -->
                            <div class="col-md-3 mb-3">
                                <div class="text-center">
                                    <label class="font-weight-bold">Foto Karyawan</label>
                                    <div class="mb-2">
                                        @if(isset($karyawan) && $karyawan->foto)
                                            <img src="{{ asset('storage/karyawan/' . $karyawan->foto) }}" 
                                                 id="preview-foto" 
                                                 class="img-thumbnail" 
                                                 style="width: 200px; height: 250px; object-fit: cover;">
                                        @else
                                            <div id="preview-foto" 
                                                 class="bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" 
                                                 style="width: 200px; height: 250px;">
                                                <i class="fas fa-camera fa-3x"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file" 
                                           class="form-control-file @error('foto') is-invalid @enderror" 
                                           id="foto" 
                                           name="foto" 
                                           accept="image/*"
                                           onchange="previewImage(event)">
                                    @error('foto')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                    <small class="text-muted">Max 2MB (JPG, PNG)</small>
                                </div>
                            </div>

                            <!-- Form Data -->
                            <div class="col-md-9">
                                <div class="row">
                                    <!-- NIK -->
                                    <div class="col-md-6 mb-3">
                                        <label for="nik" class="font-weight-bold">NIK <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nik') is-invalid @enderror" 
                                               id="nik" 
                                               name="nik" 
                                               value="{{ old('nik', $karyawan->nik ?? '') }}" 
                                               required>
                                        @error('nik')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Nama Lengkap -->
                                    <div class="col-md-6 mb-3">
                                        <label for="nama_lengkap" class="font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control @error('nama_lengkap') is-invalid @enderror" 
                                               id="nama_lengkap" 
                                               name="nama_lengkap" 
                                               value="{{ old('nama_lengkap', $karyawan->nama_lengkap ?? '') }}" 
                                               required>
                                        @error('nama_lengkap')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Tempat Lahir -->
                                    <div class="col-md-6 mb-3">
                                        <label for="tempat_lahir" class="font-weight-bold">Tempat Lahir</label>
                                        <input type="text" 
                                               class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                               id="tempat_lahir" 
                                               name="tempat_lahir" 
                                               value="{{ old('tempat_lahir', $karyawan->tempat_lahir ?? '') }}">
                                        @error('tempat_lahir')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Lahir -->
                                    <div class="col-md-6 mb-3">
                                        <label for="tanggal_lahir" class="font-weight-bold">Tanggal Lahir</label>
                                        <input type="date" 
                                               class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                               id="tanggal_lahir" 
                                               name="tanggal_lahir" 
                                               value="{{ old('tanggal_lahir', isset($karyawan) && $karyawan->tanggal_lahir ? $karyawan->tanggal_lahir->format('Y-m-d') : '') }}">
                                        @error('tanggal_lahir')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Jenis Kelamin -->
                                    <div class="col-md-6 mb-3">
                                        <label for="jenis_kelamin" class="font-weight-bold">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-control @error('jenis_kelamin') is-invalid @enderror" 
                                                id="jenis_kelamin" 
                                                name="jenis_kelamin" 
                                                required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L" {{ old('jenis_kelamin', $karyawan->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('jenis_kelamin', $karyawan->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- No Telepon -->
                                    <div class="col-md-6 mb-3">
                                        <label for="no_telepon" class="font-weight-bold">No. Telepon</label>
                                        <input type="text" 
                                               class="form-control @error('no_telepon') is-invalid @enderror" 
                                               id="no_telepon" 
                                               name="no_telepon" 
                                               value="{{ old('no_telepon', $karyawan->no_telepon ?? '') }}"
                                               placeholder="08xxxxxxxxxx">
                                        @error('no_telepon')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-12 mb-3">
                                        <label for="email" class="font-weight-bold">Email</label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $karyawan->email ?? '') }}">
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Alamat -->
                                    <div class="col-md-12 mb-3">
                                        <label for="alamat" class="font-weight-bold">Alamat Lengkap</label>
                                        <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                                  id="alamat" 
                                                  name="alamat" 
                                                  rows="3">{{ old('alamat', $karyawan->alamat ?? '') }}</textarea>
                                        @error('alamat')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Posisi -->
                                    <div class="col-md-4 mb-3">
                                        <label for="posisi" class="font-weight-bold">Posisi</label>
                                        <input type="text" 
                                               class="form-control @error('posisi') is-invalid @enderror" 
                                               id="posisi" 
                                               name="posisi" 
                                               value="{{ old('posisi', $karyawan->posisi ?? 'Cleaning Service') }}">
                                        @error('posisi')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Masuk -->
                                    <div class="col-md-4 mb-3">
                                        <label for="tanggal_masuk" class="font-weight-bold">Tanggal Masuk <span class="text-danger">*</span></label>
                                        <input type="date" 
                                               class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                                               id="tanggal_masuk" 
                                               name="tanggal_masuk" 
                                               value="{{ old('tanggal_masuk', isset($karyawan) && $karyawan->tanggal_masuk ? $karyawan->tanggal_masuk->format('Y-m-d') : '') }}" 
                                               required>
                                        @error('tanggal_masuk')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-4 mb-3">
                                        <label for="status" class="font-weight-bold">Status <span class="text-danger">*</span></label>
                                        <select class="form-control @error('status') is-invalid @enderror" 
                                                id="status" 
                                                name="status" 
                                                required>
                                            <option value="aktif" {{ old('status', $karyawan->status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="cuti" {{ old('status', $karyawan->status ?? '') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                            <option value="resign" {{ old('status', $karyawan->status ?? '') == 'resign' ? 'selected' : '' }}>Resign</option>
                                            <option value="nonaktif" {{ old('status', $karyawan->status ?? '') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Keluar (jika resign) -->
                                    <div class="col-md-12 mb-3" id="tanggal-keluar-wrapper" style="display: none;">
                                        <label for="tanggal_keluar" class="font-weight-bold">Tanggal Keluar</label>
                                        <input type="date" 
                                               class="form-control @error('tanggal_keluar') is-invalid @enderror" 
                                               id="tanggal_keluar" 
                                               name="tanggal_keluar" 
                                               value="{{ old('tanggal_keluar', isset($karyawan) && $karyawan->tanggal_keluar ? $karyawan->tanggal_keluar->format('Y-m-d') : '') }}">
                                        @error('tanggal_keluar')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Tombol -->
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Data
                                </button>
                                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Preview foto saat dipilih
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('preview-foto');
        preview.innerHTML = '<img src="' + reader.result + '" class="img-thumbnail" style="width: 200px; height: 250px; object-fit: cover;">';
    }
    reader.readAsDataURL(event.target.files[0]);
}

// Tampilkan field tanggal keluar jika status resign
document.getElementById('status').addEventListener('change', function() {
    const tanggalKeluarWrapper = document.getElementById('tanggal-keluar-wrapper');
    if (this.value === 'resign') {
        tanggalKeluarWrapper.style.display = 'block';
    } else {
        tanggalKeluarWrapper.style.display = 'none';
        document.getElementById('tanggal_keluar').value = '';
    }
});

// Trigger saat halaman load
document.addEventListener('DOMContentLoaded', function() {
    const status = document.getElementById('status').value;
    if (status === 'resign') {
        document.getElementById('tanggal-keluar-wrapper').style.display = 'block';
    }
});
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection