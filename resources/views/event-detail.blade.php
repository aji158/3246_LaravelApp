@extends('layouts.app')

@section('content')

<main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
    <!-- Left: Poster -->
    <div class="lg:col-span-1">
        <div class="sticky top-32">
            <img src="{{ ($event->poster_path && Storage::disk('public')->exists($event->poster_path))
                  ? asset('storage/' . $event->poster_path)
                  : 'https://placehold.co/200x600' }}"
                alt="{{ $event->title }}"
                class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white object-cover aspect-[3/4]">
            <div class="mt-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
    <h4 class="font-bold mb-4">Penyelenggara</h4>
    <div class="flex items-center gap-4">
        <!-- Inisial Dinamis -->
        <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold uppercase">
            {{ strtoupper(substr($event->user->organization_name ?? $event->user->name ?? 'Admin', 0, 2)) }}
        </div>
        <div>
            <!-- Nama Organisasi / Penyelenggara Dinamis -->
            <p class="font-bold text-slate-800">
                {{ $event->user->organization_name ?? $event->user->name ?? 'Admin Amikom' }}
            </p>
            <p class="text-xs text-slate-500">Verified Organizer</p>
        </div>
    </div>
</div>
        </div>
    </div>

    <!-- Right: Details -->
    <div class="lg:col-span-2 space-y-12">
        <div class="space-y-4">
            <span
                class="px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">
                {{ $event->category->name }}
            </span>
            <h1 class="text-4xl md:text-5xl font-black leading-tight">
                {{ $event->title }}
            </h1>
            <div class="flex flex-wrap gap-6 text-slate-500 font-medium">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ $event->location }}</span>
                </div>
            </div>
        </div>

        <div class="prose prose-slate max-w-none">
            <h3 class="text-2xl font-bold mb-4">Deskripsi Event</h3>
            <p class="text-lg text-slate-600 leading-relaxed">
                {{ $event->description }}
            </p>
        </div>

        <div
            class="bg-indigo-600 rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl shadow-indigo-200 relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                <div>
                    <p class="text-indigo-200 font-bold uppercase tracking-widest text-sm mb-2">Harga Tiket</p>
                    <h2 class="text-5xl font-black">
                        Rp {{ number_format($event->price, 0, ',', '.') }}
                        <span class="text-lg font-medium text-indigo-200">/ orang</span>
                    </h2>
                    <p class="mt-4 text-indigo-100 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Sisa stok: <span class="font-bold underline">
                            {{ $event->stock }} Tiket lagi!
                        </span>
                    </p>
                </div>
                <div>
                    <a href="{{ url('checkout/'.$event->id) }}"
                        class="inline-block px-10 py-5 bg-white text-indigo-600 rounded-2xl font-black text-xl hover:scale-105 transition-transform shadow-xl">
                        Pesan Sekarang
                    </a>
                </div>
            </div>
            <!-- Decoration -->
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white opacity-10 rounded-full"></div>
            <div class="absolute -left-10 -top-10 w-32 h-32 bg-indigo-400 opacity-20 rounded-full"></div>
        </div>

        <div class="space-y-4">
            <h3 class="text-xl font-bold">Kebijakan Tiket</h3>
            <ul class="space-y-3 text-slate-500">
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    Tiket dapat discan di pintu masuk (Check-in).
                </li>
                <li class="flex items-start gap-2 text-rose-500">
                    <svg class="w-5 h-5 text-rose-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Tiket yang sudah dibeli tidak dapat direfund.
                </li>
            </ul>
        </div>

        <!-- ========================================== -->
        <!-- SECTION ULASAN & RATING BINTANG (SOAL 1)   -->
        <!-- ========================================== -->
        <div class="pt-8 border-t border-slate-200 space-y-6">
            <h3 class="text-2xl font-bold text-slate-800">Ulasan & Penilaian Peserta</h3>

            @if(session('success'))
                <div class="p-4 text-sm text-emerald-800 bg-emerald-100 rounded-2xl font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form Ulasan (Bisa diisi jika User Login) -->
            @auth
            <div class="bg-slate-50 p-6 rounded-3xl border border-slate-100 shadow-sm">
                <h4 class="font-bold text-slate-800 mb-3">Beri Ulasan untuk Event Ini</h4>
                <form action="{{ route('reviews.store', $event->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">Rating Bintang</label>
                        <select name="rating" class="w-full md:w-1/3 p-3 rounded-xl border-slate-200 bg-white font-medium focus:ring-2 focus:ring-indigo-500">
                            <option value="5">⭐⭐⭐⭐⭐ (5/5 - Sangat Puas)</option>
                            <option value="4">⭐⭐⭐⭐ (4/5 - Puas)</option>
                            <option value="3">⭐⭐⭐ (3/5 - Cukup)</option>
                            <option value="2">⭐⭐ (2/5 - Kurang)</option>
                            <option value="1">⭐ (1/5 - Kecewa)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-600 mb-1">Pengalaman / Testimoni</label>
                        <textarea name="comment" rows="3" class="w-full p-4 rounded-xl border-slate-200 bg-white placeholder-slate-400 focus:ring-2 focus:ring-indigo-500" placeholder="Tuliskan ulasan jujur kamu mengenai acara ini..." required></textarea>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-100 transition">
                        Kirim Ulasan
                    </button>
                </form>
            </div>
            @else
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-slate-500 text-sm">
                Silakan <a href="{{ route('login') }}" class="text-indigo-600 font-bold underline">login</a> terlebih dahulu untuk memberikan ulasan.
            </div>
            @endauth

            <!-- Daftar Ulasan Masuk -->
            <div class="space-y-4">
                @forelse($event->reviews ?? [] as $review)
                    <div class="p-5 bg-white rounded-2xl border border-slate-100 shadow-sm space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-800">{{ $review->user->name ?? 'Peserta' }}</span>
                            <span class="text-amber-400 font-bold tracking-widest text-sm">
                                {{ str_repeat('⭐', $review->rating) }}
                            </span>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $review->comment }}</p>
                        <p class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                @empty
                    <p class="text-slate-400 italic text-sm">Belum ada ulasan untuk event ini. Jadi yang pertama memberikan testimoni!</p>
                @endforelse
            </div>
        </div>

    </div>
</main>

@endsection