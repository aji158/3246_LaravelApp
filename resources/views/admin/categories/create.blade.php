@extends('layouts.admin')

@section('title', 'Tambah Categories - Admin')
@section('page_title', 'Tambah Categories')
@section('page_subtitle', 'Tambah Categories.')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">Tambah Kategori</h1>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block mb-2 font-semibold">
                Nama Kategori
            </label>

            <input type="text"
                name="name"
                value="{{ old('name') }}"
                class="w-full border rounded-xl px-4 py-3"
                required>

            @error('name')
            <p class="text-red-500 text-sm mt-2">
                {{ $message }}
            </p>
            @enderror
        </div>


        <button type="submit"
            class="bg-indigo-600 text-white px-6 py-3 rounded-xl">
            Simpan
        </button>

    </form>
</div>
@endsection