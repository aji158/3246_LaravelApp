@extends('layouts.admin')

@section('title', 'Kelola Ulasan & Rating')
@section('page_title', 'Ulasan & Penilaian Peserta')
@section('page_subtitle', 'Pantau dan moderasi ulasan bintang dari para pembeli tiket')

@section('content')
<div class="p-6 w-full">

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600">
                <thead class="bg-slate-900 text-slate-200 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-center w-16">No</th>
                        <th class="px-6 py-4">Peserta / User</th>
                        <th class="px-6 py-4">Event</th>
                        <th class="px-6 py-4 text-center">Rating</th>
                        <th class="px-6 py-4">Ulasan / Testimoni</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($reviews as $index => $review)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-center font-bold text-slate-400">
                            {{ $reviews->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ $review->user->name ?? 'Anonim' }}
                            <span class="block text-xs font-normal text-slate-400">{{ $review->user->email ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">
                            {{ $review->event->title ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-amber-400 text-base font-bold">
                                {{ str_repeat('⭐', $review->rating) }}
                            </span>
                            <span class="block text-xs text-slate-400 font-bold">({{ $review->rating }}/5)</span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 max-w-xs break-words">
                            "{{ $review->comment }}"
                            <span class="block text-[10px] text-slate-400 mt-1">{{ $review->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
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
                            Belum ada ulasan yang masuk dari peserta.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>

</div>
@endsection