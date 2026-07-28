@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Ringkasan')

@section('content')
<!-- Library Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>
        </div>
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Total Pendapatan</p>
        <h3 class="text-2xl font-black">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                </path>
            </svg>
        </div>
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Tiket Terjual</p>
        <h3 class="text-2xl font-black">{{ number_format($totalTransactions ?? 0, 0, ',', '.') }}</h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Event Aktif</p>
        <h3 class="text-2xl font-black">{{ $totalEvents ?? 0 }} Event</h3>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Pesanan Pending</p>
        <h3 class="text-2xl font-black">{{ $pendingTransactions ?? 0 }} Pesanan</h3>
    </div>
</div>

<!-- ========================================== -->
<!-- DUA GRAFIK BERDAMPINGAN (CHART.JS)          -->
<!-- ========================================== -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Grafik 1: Pertumbuhan Event -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-black text-lg text-slate-800">Pertumbuhan Event</h3>
                <p class="text-xs text-slate-400 font-medium">Event baru per bulan ({{ date('Y') }})</p>
            </div>
            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 font-bold rounded-lg text-[10px] uppercase">
                Line Chart
            </span>
        </div>
        <div class="relative w-full h-64">
            <canvas id="eventsChart"></canvas>
        </div>
    </div>

    <!-- Grafik 2: Tren Tiket Terjual -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-black text-lg text-slate-800">Penjualan Tiket</h3>
                <p class="text-xs text-slate-400 font-medium">Tiket sukses per bulan ({{ date('Y') }})</p>
            </div>
            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 font-bold rounded-lg text-[10px] uppercase">
                Bar Chart
            </span>
        </div>
        <div class="relative w-full h-64">
            <canvas id="ticketsChart"></canvas>
        </div>
    </div>
</div>

<!-- Latest Sales Table -->
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b flex justify-between items-center">
        <h3 class="font-black text-xl">Transaksi Terakhir</h3>
        <a href="{{ route('admin.transactions.index') }}" class="text-indigo-600 font-bold hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4 w-1/4">Tgl Transaksi</th>
                    <th class="px-8 py-4 w-1/4">Pembeli</th>
                    <th class="px-8 py-4 w-1/4">Event</th>
                    <th class="px-8 py-4 w-[10%]">Status</th>
                    <th class="px-8 py-4 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y border-t">
                @forelse($recentTransactions as $trx)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-8 py-6 text-sm text-slate-600 max-w-xs break-all">
                        {{ $trx->created_at->format('d M y - H:i') }}<br>
                        <span class="text-xs text-slate-400">{{ $trx->order_id }}</span>
                    </td>
                    <td class="px-8 py-6">
                        <p class="font-bold uppercase tracking-wide text-sm truncate max-w-[150px]">{{ $trx->customer_name ?? $trx->user->name ?? 'User' }}</p>
                        <p class="text-xs text-slate-400 truncate max-w-[150px]">{{ $trx->customer_email ?? $trx->user->email ?? '-' }}</p>
                    </td>
                    <td class="px-8 py-6 font-medium text-slate-600 max-w-xs truncate">{{ $trx->event->title ?? '-' }}</td>
                    <td class="px-8 py-6 whitespace-nowrap">
                        @if($trx->status === 'settlement' || $trx->status === 'success')
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">Success</span>
                        @elseif($trx->status === 'pending')
                            <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold uppercase">Pending</span>
                        @else
                            <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase">{{ $trx->status }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 font-black text-indigo-600 whitespace-nowrap text-right">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-10 text-center text-slate-500 italic">Belum ada transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Script Inisialisasi Dua Grafik Chart.js -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const labels = {!! json_encode($chartMonths ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des']) !!};

        // --- GRAFIK 1: PERTUMBUHAN EVENT (LINE CHART) ---
        const ctxEvents = document.getElementById('eventsChart').getContext('2d');
        const gradEvents = ctxEvents.createLinearGradient(0, 0, 0, 250);
        gradEvents.addColorStop(0, 'rgba(99, 102, 241, 0.35)');
        gradEvents.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

        new Chart(ctxEvents, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Event Baru',
                    data: {!! json_encode($chartEventsData ?? [0,0,0,0,0,0,0,0,0,0,0,0]) !!},
                    borderColor: '#6366f1',
                    borderWidth: 3,
                    backgroundColor: gradEvents,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4f46e5',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f8fafc' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // --- GRAFIK 2: PENJUALAN TIKET (BAR CHART) ---
        const ctxTickets = document.getElementById('ticketsChart').getContext('2d');
        new Chart(ctxTickets, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Tiket Terjual',
                    data: {!! json_encode($chartTicketsData ?? [0,0,0,0,0,0,0,0,0,0,0,0]) !!},
                    backgroundColor: '#10b981',
                    borderRadius: 8,
                    hoverBackgroundColor: '#059669'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f8fafc' } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection