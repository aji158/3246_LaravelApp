@extends('layouts.admin')

@section('title', 'Tambah Partner')
@section('page_title', 'Tambah Partner')
@section('page_subtitle', 'Tambahkan partner baru')

@section('content')

<div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm max-w-2xl">

    <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <!-- Nama -->
        <div class="mb-6">
            <label class="block mb-2 font-bold text-slate-700">
                Nama Partner
            </label>

            <input type="text"
                name="name"
                value="{{ old('name') }}"
                class="w-full px-5 py-4 border-2 border-slate-100 rounded-2xl bg-slate-50 focus:outline-none focus:border-indigo-500"
                required>

            @error('name')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Logo -->
        <div class="mb-6">
            <label class="block mb-2 font-bold text-slate-700">
                Logo Partner
            </label>

            <input type="file"
                name="logo_url"
                class="w-full px-5 py-4 border-2 border-slate-100 rounded-2xl bg-slate-50 focus:outline-none focus:border-indigo-500"
                required>

            @error('logo_url')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Button -->
        <div class="flex justify-end gap-3">

            <a href="{{ route('admin.partners.index') }}"
                class="px-6 py-3 rounded-2xl text-slate-500 font-bold hover:text-slate-700 transition">
                Batal
            </a>

            <button type="submit"
                class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition">
                Simpan
            </button>

        </div>

    </form>

</div>

@endsection