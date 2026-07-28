@extends('layouts.admin')

@section('title', 'Kelola Penyelenggara')
@section('page_title', 'Daftar Penyelenggara / Ormawa')
@section('page_subtitle', 'Pantau seluruh akun organisasi & HIMA yang terdaftar di AmikomEventHub')

@section('content')
<div class="p-6 w-full">

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="bg-indigo-950 text-indigo-100 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-center w-16">No</th>
                        <th class="px-6 py-4">Nama Organisasi / Ormawa</th>
                        <th class="px-6 py-4">Penanggung Jawab & Email</th>
                        <th class="px-6 py-4 text-center">Total Event</th>
                        <th class="px-6 py-4 text-center">Tanggal Daftar</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($organizers as $index => $org)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-center font-bold text-slate-400">
                            {{ $organizers->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase">
                                    {{ strtoupper(substr($org->organization_name ?? $org->name, 0, 2)) }}
                                </div>
                                <span>{{ $org->organization_name ?? 'Tanpa Nama Organisasi' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">
                            {{ $org->name }}
                            <span class="block text-xs font-normal text-slate-400">{{ $org->email }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 font-bold rounded-xl text-xs">
                                {{ $org->events_count }} Event
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-slate-400 text-xs">
                            {{ $org->created_at ? $org->created_at->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.organizers.destroy', $org->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus penyelenggara ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 rounded-xl font-bold text-xs hover:bg-rose-600 hover:text-white transition">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">
                            Belum ada akun Penyelenggara/Ormawa yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($organizers->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $organizers->links() }}
        </div>
        @endif
    </div>

</div>
@endsection