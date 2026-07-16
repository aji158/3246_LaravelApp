@extends('layouts.admin')

@section('content')
<main class="flex-1 p-10 overflow-y-auto">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black">Ubah Data Jabatan</h1>
            <p class="text-slate-500 font-semibold text-sm">Perbarui nama posisi tingkatan jabatan.</p>
        </div>
        <div>
            <a href="{{ route('admin.jabatan.index') }}" 
               class="px-5 py-3 bg-slate-200 text-slate-700 rounded-2xl font-bold text-sm hover:bg-slate-300 transition">
                ← Kembali
            </a>
        </div>
    </header>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden max-w-xl">
        <div class="px-8 py-6 bg-slate-50/50 border-b">
            <h2 class="font-black text-slate-800 text-lg">Form Edit Jabatan</h2>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.jabatan.update', $jabatan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2">Nama Jabatan</label>
                    <input type="text" name="name" value="{{ old('name', $jabatan->name) }}" required
                        class="w-full px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    @error('name')
                        <p class="text-rose-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.jabatan.index') }}" class="px-5 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition">
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