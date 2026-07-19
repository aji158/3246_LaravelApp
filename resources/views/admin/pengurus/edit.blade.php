@extends('layouts.admin')

@section('content')
<main class="flex-1 p-10 overflow-y-auto">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black">Ubah Data Pengurus</h1>
            <p class="text-slate-500 font-semibold text-sm">Perbarui informasi penugasan dan gaji operasional anggota tim.</p>
        </div>
        <div>
            <a href="{{ route('admin.pengurus.index') }}" 
               class="px-5 py-3 bg-slate-200 text-slate-700 rounded-2xl font-bold text-sm hover:bg-slate-300 transition">
                ← Kembali
            </a>
        </div>
    </header>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden max-w-2xl">
        <div class="px-8 py-6 bg-slate-50/50 border-b">
            <h2 class="font-black text-slate-800 text-lg">Form Edit Anggota Pengurus</h2>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.pengurus.update', $pengurus->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-5 mb-8">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $pengurus->name) }}" required
                            class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2">Jabatan</label>
                        <select name="jabatan_id" required class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white outline-none focus:ring-2 focus:ring-indigo-500 transition">
                            @foreach($jabatans as $j)
                                <option value="{{ $j->id }}" {{ $pengurus->jabatan_id == $j->id ? 'selected' : '' }}>
                                    {{ $j->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2">Deskripsi Tugas</label>
                        <input type="text" name="description" value="{{ old('description', $pengurus->description) }}"
                            class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2">Gaji (Salary)</label>
                        <input type="number" name="salary" value="{{ old('salary', $pengurus->salary) }}" required
                            class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.pengurus.index') }}" class="px-5 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection