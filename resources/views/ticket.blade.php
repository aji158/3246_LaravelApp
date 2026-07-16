@extends('layouts.app')

@section('content')
<div class="max-w-md w-full mx-auto py-8">
    <!-- Success Banner -->
    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-black text-white">Pembayaran Berhasil!</h1>
        <p class="text-indigo-100 mt-2">Tiket Anda telah terbit dan siap digunakan.</p>
    </div>

    <!-- Ticket Card -->
    <div class="bg-white text-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl relative">
        <!-- Ticket Header -->
        <div class="p-8 bg-indigo-50 border-b-4 border-dashed border-indigo-100 text-center relative">
            <p class="text-indigo-600 font-bold uppercase tracking-widest text-xs mb-2">E-Ticket Resmi</p>
            <!-- MENAMPILKAN JUDUL EVENT SECARA DINAMIS -->
            <h2 class="text-2xl font-black leading-tight">{{ $transaction->event->title }}</h2>

            <!-- Ticket Side Cuts -->
            <div class="absolute -left-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
            <div class="absolute -right-4 -bottom-4 w-8 h-8 bg-indigo-600 rounded-full"></div>
        </div>

        <!-- Ticket Body -->
        <div class="p-8 space-y-8">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase mb-1">Nama Pembeli</p>
                    <!-- NAMA PEMBELI DINAMIS -->
                    <p class="font-bold text-lg">{{ $transaction->customer_name }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase mb-1">Tanggal & Waktu</p>
                    <!-- TANGGAL DAN WAKTU EVENT DINAMIS -->
                    <p class="font-bold text-lg">{{ \Carbon\Carbon::parse($transaction->event->date)->format('d M, H:i') }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase mb-1">Order ID</p>
                    <!-- ORDER ID DINAMIS -->
                    <p class="font-bold text-lg">{{ $transaction->order_id }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase mb-1">Lokasi</p>
                    <!-- LOKASI EVENT DINAMIS -->
                    <p class="font-bold text-lg">{{ $transaction->event->location }}</p>
                </div>
            </div>

            <!-- Bagian QR Code Dinamis -->
            <div class="bg-slate-100 p-6 rounded-3xl flex flex-col items-center">
                <p class="text-slate-400 text-xs font-bold uppercase mb-4">Scan QR untuk Check-in</p>
                
                <!-- GENERATE REAL QR CODE DARI API (Menggunakan Order ID sebagai data scan) -->
                <div class="w-48 h-48 bg-white p-4 rounded-xl shadow-inner flex items-center justify-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($transaction->order_id) }}" 
                         alt="QR Code Ticket" 
                         class="w-full h-full">
                </div>
                
                <!-- KODE TIKET/ORDER ID DINAMIS DI BAWAH QR -->
                <p class="mt-4 font-mono font-bold text-slate-800">{{ $transaction->order_id }}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-8 pb-8">
            <button onclick="window.print()"
                class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition">
                Cetak / Simpan PDF
            </button>
            <!-- Tombol Kembali ke Beranda -->
            <a href="{{ route('home') }}"
                class="block text-center mt-4 text-slate-500 font-bold hover:text-indigo-600">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection