@extends('layouts.admin')

@section('content')
<main class="flex-1 p-10 overflow-y-auto">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black">Kelola Pengurus</h1>
            <p class="text-slate-500 font-medium font-semibold text-sm">Manajemen tim internal dan besaran gaji operasional.</p>
        </div>
    </header>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-2xl font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Tambah Pengurus -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden mb-10">
        <div class="px-8 py-6 bg-slate-50/50 border-b">
            <h2 class="font-black text-slate-800 text-lg">Form Tambah Anggota Pengurus</h2>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.pengurus.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="name" placeholder="Nama Pengurus" required
                            class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2">Pilih Jabatan</label>
                        <select name="jabatan_id" required class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatans as $j)
                                <option value="{{ $j->id }}">{{ $j->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2">Deskripsi Tugas</label>
                        <input type="text" name="description" placeholder="Deskripsi Tugas/Jobdesc"
                            class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2">Gaji (Salary)</label>
                        <input type="number" name="salary" placeholder="Besaran Gaji (Angka)" required
                            class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition">
                        + Tambah Pengurus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Pengurus -->
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4 w-16">No</th>
                        <th class="px-8 py-4">Nama Lengkap</th>
                        <th class="px-8 py-4">Jabatan</th>
                        <th class="px-8 py-4">Deskripsi Tugas</th>
                        <th class="px-8 py-4">Gaji / Salary</th>
                        <th class="px-8 py-4 w-44">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y border-t">
                    @forelse($penguruses as $key => $p)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-8 py-6 font-bold text-slate-400">{{ $key + 1 }}</td>
                        <td class="px-8 py-6 font-black text-slate-800">{{ $p->name }}</td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold">
                                {{ $p->jabatan->name ?? 'Tidak Ada' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-sm text-slate-500 font-medium">{{ $p->description ?? '-' }}</td>
                        <td class="px-8 py-6 font-bold text-indigo-600">Rp{{ number_format($p->salary, 0, ',', '.') }}</td>
                        <td class="px-8 py-6">
                            <!-- Container flexbox agar tombol Edit & Hapus sejajar horizontal -->
                            <div class="flex items-center gap-2">
                                <!-- Tombol Edit (Mengarahkan ke halaman edit pengurus) -->
                                <a href="{{ route('admin.pengurus.edit', $p->id) }}"
                                   class="p-2.5 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition inline-flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('admin.pengurus.destroy', $p->id) }}" method="POST" class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')"
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
                        <td colspan="6" class="px-8 py-6 text-center text-slate-400 font-medium">Belum ada data pengurus.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection