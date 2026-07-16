@extends('layouts.admin')

@section('title', 'Tambah Event Baru - Admin')
@section('page_title', 'Tambah Event Baru')
@section('page_subtitle', 'Masukkan detail acara baru.')

@section('content')
<div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm max-w-3xl">

    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mt-2">
       
    @csrf

        <!-- Judul -->
        <div>
            <label class="block text-sm font-bold mb-2">Judul Event</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full px-5 py-4 bg-slate-50 rounded-2xl" required>
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Kategori -->
        <div>
            <label class="block text-sm font-bold mb-2">Kategori</label>
            <select name="category_id" class="w-full px-5 py-4 bg-slate-50 rounded-2xl" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-sm font-bold mb-2">Deskripsi</label>
            <textarea name="description" class="w-full px-5 py-4 bg-slate-50 rounded-2xl">{{ old('description') }}</textarea>
        </div>

        <!-- Tanggal & Lokasi -->
        <div class="grid grid-cols-2 gap-6">
            <input type="datetime-local" name="date" value="{{ old('date') }}" class="px-5 py-4 bg-slate-50 rounded-2xl">
            <input type="text" name="location" value="{{ old('location') }}" placeholder="Lokasi" class="px-5 py-4 bg-slate-50 rounded-2xl">
        </div>

        <!-- Harga & Stok -->
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold mb-2">Harga</label>
                <input type="number" name="price" min="0" value="{{ old('price', 0) }}" 
                       class="w-full px-5 py-4 bg-slate-50 rounded-2xl {{ $errors->has('price') ? 'border border-red-500 bg-red-50' : '' }}">
                
                @if($errors->has('price'))
                    <span class="text-red-500 text-sm mt-1 block">{{ $errors->first('price') }}</span>
                @endif
            </div>
            <div>
                <label class="block text-sm font-bold mb-2">Stok</label>
                <input type="number" name="stock" min="0" value="{{ old('stock', 1) }}" 
                       class="w-full px-5 py-4 bg-slate-50 rounded-2xl {{ $errors->has('stock') ? 'border border-red-500 bg-red-50' : '' }}">
                
                @if($errors->has('stock'))
                    <span class="text-red-500 text-sm mt-1 block">{{ $errors->first('stock') }}</span>
                @endif
            </div>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                Poster Event (Opsional)
            </label>

            <input
                type="file"
                name="poster"
                accept="image/*"
                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl 
               focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 
               outline-none transition font-medium">

            {{-- Preview / Link poster lama --}}
            @if(!empty($event->poster_path))
            <p class="text-sm text-slate-500 mt-2">
                Poster saat ini:
                <a href="{{ asset('storage/' . $event->poster_path) }}"
                    target="_blank"
                    class="text-indigo-600 hover:underline">
                    Lihat
                </a>
            </p>
            @endif

            {{-- Error --}}
            @error('poster')
            <span class="text-red-500 text-sm mt-1 block">
                {{ $message }}
            </span>
            @enderror
        </div>


        <!-- Tombol -->
        <div class="flex justify-between items-center pt-4">
            <a href="{{ route('admin.events.index') }}"
                class="px-6 py-3 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 transition">
                Batal
            </a>

            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection