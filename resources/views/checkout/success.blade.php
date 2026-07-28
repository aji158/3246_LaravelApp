@extends('layouts.app')

@section('title', 'Pemesanan Berhasil')

@section('content')
@php
    $isFree = ($transaction->total_price == 0);
@endphp

<main class="max-w-3xl mx-auto px-6 py-12 md:py-20 text-center">
    <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 md:p-12 shadow-xl inline-block w-full">
        
        <!-- Icon Centang / Sukses -->
        <div class="w-20 h-20 md:w-24 md:h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-100">
            <svg class="w-10 h-10 md:w-12 md:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <!-- Judul Dinamis -->
        <h2 class="text-3xl md:text-4xl font-black text-slate-800 mb-3">
            {{ $isFree ? 'Tiket Gratis Berhasil Didapatkan!' : 'Terima Kasih!' }}
        </h2>
        
        <!-- Pesan Konfirmasi -->
        <p class="text-slate-500 mb-8 leading-relaxed max-w-lg mx-auto text-sm md:text-base">
            @if($isFree)
                Selamat! Tiket Anda untuk pesanan <strong class="text-slate-800 font-bold">{{ $transaction->order_id }}</strong> telah berhasil diterbitkan dan dikirim ke email <strong class="text-indigo-600 font-bold">{{ $transaction->customer_email }}</strong>.
            @else
                Pembayaran untuk pesanan <strong class="text-slate-800 font-bold">{{ $transaction->order_id }}</strong> telah kami terima. 
                E-Ticket resmi telah dikirimkan ke email <strong class="text-indigo-600 font-bold">{{ $transaction->customer_email }}</strong>.
            @endif
        </p>

        <!-- E-Ticket Card Summary -->
        @if($transaction->event)
        <div class="bg-slate-50 rounded-2xl border border-slate-200/80 p-6 text-left mb-8 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Kode Booking</span>
                    <p class="font-extrabold text-indigo-600 text-lg">{{ $transaction->order_id }}</p>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 font-bold text-xs rounded-full uppercase">
                    {{ $isFree ? 'FREE TICKET' : 'LUNAS' }}
                </span>
            </div>

            <div>
                <h4 class="font-extrabold text-slate-800 text-lg">{{ $transaction->event->title }}</h4>
                <div class="flex flex-wrap gap-4 text-xs font-medium text-slate-500 mt-2">
                    <span class="flex items-center gap-1">
                        📅 {{ \Carbon\Carbon::parse($transaction->event->date)->format('d M Y, H:i') }} WIB
                    </span>
                    <span class="flex items-center gap-1">
                        📍 {{ $transaction->event->location }}
                    </span>
                </div>
            </div>

            <div class="border-t border-slate-200 pt-3 flex justify-between text-xs text-slate-500">
                <span>Atas Nama: <strong class="text-slate-700">{{ $transaction->customer_name }}</strong></span>
                <span>Total: <strong class="text-slate-700">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong></span>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('home') }}" 
                class="w-full sm:w-auto px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 active:scale-95">
                Kembali ke Beranda
            </a>
            <button onclick="window.print()" 
                class="w-full sm:w-auto px-8 py-4 bg-slate-100 text-slate-700 rounded-2xl font-bold hover:bg-slate-200 transition active:scale-95">
                🖨️ Cetak E-Ticket
            </button>
        </div>

    </div>
</main>
@endsection