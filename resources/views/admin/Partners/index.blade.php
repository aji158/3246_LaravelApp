@extends('layouts.admin')

@section('title', 'Partner')
@section('page_title', 'Manajemen Partner')
@section('page_subtitle', 'Kelola data partner AmikomEventHub')

@section('content')

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">

    <!-- Header -->
    <div class="p-6 flex justify-between items-center border-b border-slate-100">

        <div>
            <h2 class="text-xl font-black text-slate-800">
                Data Partner
            </h2>
            <p class="text-sm text-slate-500">
                Daftar partner yang bekerja sama
            </p>
        </div>

        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('admin.partners.create') }}"
                class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>
                Tambah Partner
            </a>
        </div>
        
    </div>

    <!-- Search -->
    <div class="p-6 border-b border-slate-100">

        <form method="GET"
            action="{{ route('admin.partners.index') }}"
            class="flex gap-3">

            <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari partner..."
                class="w-full max-w-sm px-5 py-3 border border-slate-200 rounded-2xl focus:outline-none focus:border-indigo-500">

            <button type="submit"
                class="px-5 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition">
                Search
            </button>

            <a href="{{ route('admin.partners.index') }}"
                class="px-5 py-3 bg-slate-200 text-slate-700 rounded-2xl font-bold hover:bg-slate-300 transition">
                Reset
            </a>

        </form>

    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">

            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-left font-bold text-slate-500">No</th>
                    <th class="px-6 py-4 text-left font-bold text-slate-500">Logo</th>
                    <th class="px-6 py-4 text-left font-bold text-slate-500">Nama Partner</th>
                    <th class="px-6 py-4 text-left font-bold text-slate-500">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse($partners as $index => $partner)

                <tr class="hover:bg-slate-50 transition">

                    <td class="px-6 py-4">
                        {{ $index + 1 }}
                    </td>

                    <td class="px-6 py-4">
                    @if(!empty($partner->logo_url))
                        {{-- Jika berupa URL Cloudinary (dimulai http/https) --}}
                        @if(\Illuminate\Support\Str::startsWith($partner->logo_url, ['http://', 'https://']))
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="w-12 h-12 object-contain rounded-lg">
                        {{-- Jika berupa File Lokal --}}
                        @else
                            <img src="{{ asset('storage/' . $partner->logo_url) }}" alt="{{ $partner->name }}" class="w-12 h-12 object-contain rounded-lg">
                        @endif
                    @else
                        <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-lg flex items-center justify-center text-[10px] font-bold">
                            No Logo
                        </div>
                    @endif
                </td>

                    <td class="px-6 py-4 font-semibold text-slate-700">
                        {{ $partner->name }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex gap-2">

                            <!-- Edit -->
                            <a href="{{ route('admin.partners.edit', $partner->id) }}"
                                class="px-4 py-2 bg-amber-100 text-amber-700 rounded-xl text-xs font-bold hover:bg-amber-200 transition">
                                Edit
                            </a>

                            <!-- Delete -->
                            <form action="{{ route('admin.partners.destroy', $partner->id) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    onclick="return confirm('Yakin hapus partner ini?')"
                                    class="px-4 py-2 bg-red-100 text-red-700 rounded-xl text-xs font-bold hover:bg-red-200 transition">
                                    Hapus
                                </button>

                            </form>

                        </div>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="4" class="text-center py-10 text-slate-400">
                        Belum ada data partner
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>

@endsection