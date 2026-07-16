@extends('layouts.admin')

@section('title', 'Manajemen Kategori')
@section('page_title', 'Manajemen Kategori')
@section('page_subtitle', 'Kelola kategori event yang tersedia')

@section('content')
<div class="p-6 w-full">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.categories.create') }}"
            class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kategori
        </a>
    </div>

    <!--Search-->
    <form method="GET"
        action="{{ route('admin.categories.index') }}"
        class="mb-6 flex gap-3">

        <input type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari kategori..."
            class="w-full max-w-sm px-5 py-3 border border-slate-200 rounded-2xl focus:outline-none focus:border-indigo-500">

        <button type="submit"
            class="px-5 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition">
            Search
        </button>

        <a href="{{ route('admin.categories.index') }}"
            class="px-5 py-3 bg-slate-200 text-slate-700 rounded-2xl font-bold hover:bg-slate-300 transition">
            Reset
        </a>

    </form>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">No</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Nama Kategori</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Slug</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Jumlah Event</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-600">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse($categories as $index => $category)
                <tr class="hover:bg-slate-50 transition">

                    <td class="px-6 py-4 text-slate-400 font-semibold">
                        {{ $index + 1 }}
                    </td>

                    <td class="px-6 py-4 font-semibold text-slate-800">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg">
                            {{ $category->name }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-slate-500 font-mono text-xs">
                        {{ $category->slug }}
                    </td>

                    <td class="px-6 py-4 text-slate-600">
                        {{ $category->events_count }} event
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex gap-2">

                            <!-- Tombol Edit -->
                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-lg font-bold text-xs hover:bg-amber-100 transition flex items-center gap-1">
                                ✏️ Edit
                            </a>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin hapus kategori ini?')">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg font-bold text-xs hover:bg-red-100 transition flex items-center gap-1">
                                    🗑️ Hapus
                                </button>

                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-6 text-slate-400">
                        Belum ada kategori.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>
@endsection