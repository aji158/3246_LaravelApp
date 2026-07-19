@extends('layouts.admin')

@section('content')
<main class="flex-1 p-10 overflow-y-auto">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black">Kelola Jabatan</h1>
            <p class="text-slate-500 font-medium font-semibold text-sm">Buat dan atur level tingkatan posisi jabatan di sini.</p>
        </div>
    </header>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-2xl font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Tambah Jabatan -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-10">
        <div class="px-8 py-6 bg-slate-50/50 border-b">
            <h2 class="font-black text-slate-800 text-lg">Form Tambah Jabatan</h2>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.jabatan.store') }}" method="POST">
                @csrf
                <div class="flex gap-4">
                    <input type="text" name="name" placeholder="Nama Jabatan Baru (contoh: Ketua)" required
                        class="flex-1 px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition">
                        Simpan Jabatan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Jabatan -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4 w-16">No</th>
                        <th class="px-8 py-4">Nama Jabatan</th>
                        <th class="px-8 py-4 w-44">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y border-t">
                    @forelse($jabatans as $key => $j)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-8 py-6 font-bold text-slate-400">{{ $key + 1 }}</td>
                        <td class="px-8 py-6 font-black text-slate-800">{{ $j->name }}</td>
                        <td class="px-8 py-6">
                            <!-- Container flexbox agar tombol Edit & Hapus sejajar horizontal -->
                            <div class="flex items-center gap-2">
                                <!-- Tombol Edit (Menuju ke halaman edit.blade.php) -->
                                <a href="{{ route('admin.jabatan.edit', $j->id) }}"
                                   class="p-2.5 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition inline-flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('admin.jabatan.destroy', $j->id) }}" method="POST" class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus jabatan ini?')"
                                        class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition inline-flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-6 text-center text-slate-400 font-medium">Belum ada data jabatan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection