@extends('layouts.admin')

@section('title', 'Kelola Event - Admin')
@section('page_title', 'Kelola Event')
@section('page_subtitle', 'Buat dan atur acara seru Anda di sini.')

@section('content')

<!-- Tombol tambah -->
<div class="flex justify-between items-center mb-6">
    <a href="{{ route('admin.events.create') }}"
        class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 4v16m8-8H4" />
        </svg>
        Tambah Event Baru
    </a>
</div>

<!-- Table -->
<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">

            <!-- Head -->
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4 w-16">No</th>
                    <th class="px-8 py-4">Poster</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Harga / Stok</th>
                    <th class="px-8 py-4">Aksi</th>
                </tr>
            </thead>

            <!-- Body -->
            <tbody class="divide-y border-t">
                @forelse($events as $index => $event)
                <tr class="hover:bg-slate-50/50 transition">

                    <!-- No -->
                    <td class="px-8 py-6 font-bold text-slate-400">
                        {{ $events->firstItem() + $index }}
                    </td>

                    <!-- Poster -->
                    <td class="px-8 py-6">
                        <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))? asset('storage/' . $event->poster_path): 'https://placehold.co/160x200' }}"
                            class="w-16 h-20 rounded-xl object-cover shadow-sm">
                    </td>

                    <!-- Event -->
                    <td class="px-8 py-6">
                        <p class="font-black text-slate-800">
                            {{ $event->title }}
                        </p>
                        <p class="text-xs text-slate-400">
                            {{ $event->category->name ?? '-' }} •
                            {{ $event->date }}
                        </p>
                    </td>

                    <!-- Harga -->
                    <td class="px-8 py-6">
                        <p class="font-bold text-indigo-600">
                            Rp {{ number_format($event->price, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-slate-400">
                            Stok: {{ $event->stock }}
                        </p>
                    </td>

                    <!-- Aksi -->
                    <td class="px-8 py-6">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.events.edit', $event->id) }}"
                                class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                                ✏️
                            </a>
                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara ini?');"> @csrf @method('DELETE') <button type="submit" class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition"> <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg> </button> </form>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center text-slate-500">
                        Belum ada acara yang ditambahkan.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    <!-- Pagination -->
    <div class="px-8 py-6 bg-slate-50/50 border-t">
        {{ $events->links() }}
    </div>
</div>

@endsection