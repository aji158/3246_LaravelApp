@extends('layouts.admin') {{-- Memanggil layout admin bawaan proyekmu --}}

@section('content')
<div class="container-fluid px-4 mt-4">
    <h1 class="mt-4">Manajemen Jabatan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Halaman Kelola Data Jabatan</li>
    </ol>

    <!-- Form Tambah Jabatan -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i> Tambah Jabatan Baru
        </div>
        <div class="card-body">
            <form action="{{ route('jabatan.store') }}" method="POST">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-9 mb-2">
                        <input type="text" name="nama_jabatan" class="form-control" placeholder="Masukkan Nama Jabatan (Contoh: Ketua, Sekretaris)" required>
                    </div>
                    <div class="col-md-3 mb-2 d-grid">
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Jabatan -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Daftar Jabatan
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="10%">No</th>
                        <th>Nama Jabatan</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jabatans as $key => $j)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $j->nama_jabatan }}</td>
                        <td class="text-center">
                            <form action="{{ route('jabatan.destroy', $j->id) }}" method="POST">
                                @csrf 
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus jabatan ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">Belum ada data jabatan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection