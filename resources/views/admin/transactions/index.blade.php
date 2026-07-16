@php /** @var \Illuminate\Pagination\LengthAwarePaginator|\App\Models\Transaction[] $transactions */ @endphp
@extends('layouts.admin')

@section('title', 'Laporan Transaksi')
@section('page_title', 'Laporan Transaksi')
@section('page_subtitle', 'Rekap seluruh transaksi pembelian tiket')

@section('content')
<div class="p-6 w-full">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Laporan Transaksi</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar transaksi pembelian tiket event</p>
        </div>

        <button class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
            Export Laporan
        </button>
    </div>

    <!-- KALKULASI DATA DARI DATABASE UNTUK CHART -->
    @php
        $success = $transactions->filter(function($trx) {
            return in_array(strtolower($trx->status), ['success', 'settlement']);
        })->count();

        $pending = $transactions->filter(function($trx) {
            return strtolower($trx->status) === 'pending';
        })->count();

        $free = $transactions->filter(function($trx) {
            return strtolower($trx->status) === 'free';
        })->count();
    @endphp

    <!-- TABLE -->
    <!-- TABLE -->
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-600 min-w-[800px]">
                
                <thead class="bg-slate-900 text-slate-200 text-xs uppercase tracking-wider border-b border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-center w-16">No</th>
                        <th class="px-6 py-4">Informasi Pelanggan</th>
                        <th class="px-6 py-4">Detail Event</th>
                        <th class="px-6 py-4 text-center">Status Pembayaran</th>
                        <th class="px-6 py-4 text-right">Total Bayar (+Biaya Layanan)</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($transactions as $i => $trx)
                    <tr class="hover:bg-slate-50/80 transition duration-150 ease-in-out">
                        
                        <!-- NO -->
                        <td class="px-6 py-4 text-center font-bold text-slate-400 bg-slate-50/50">
                            {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $i + 1 }}
                        </td>

                        <!-- PELANGGAN -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-extrabold text-slate-800 tracking-wide text-base mb-0.5 uppercase">
                                    {{ $trx->customer_name }}
                                </span>
                                <span class="text-xs text-indigo-600 font-medium tracking-tight mb-0.5">
                                    ✉ {{ $trx->customer_email }}
                                </span>
                                <span class="text-xs text-slate-400 font-normal">
                                    📞 {{ $trx->customer_phone }}
                                </span>
                            </div>
                        </td>

                        <!-- EVENT -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-semibold text-slate-700 text-sm">
                                    {{ $trx->event->title ?? 'Event Tidak Ditemukan' }}
                                </span>
                                <span class="text-[10px] font-mono text-slate-400 mt-1 bg-slate-100 px-2 py-0.5 rounded w-max">
                                    ID: {{ $trx->event_id }}
                                </span>
                            </div>
                        </td>

                        <!-- STATUS -->
                        <td class="px-6 py-4 text-center">
                            @if(in_array(strtolower($trx->status), ['success', 'settlement']))
                            <span class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full text-xs font-bold shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                Success
                            </span>
                            @elseif(strtolower($trx->status) == 'pending')
                            <span class="inline-flex items-center px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full text-xs font-bold shadow-sm">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                Pending
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-full text-xs font-bold shadow-sm">
                                {{ strtoupper($trx->status) }}
                            </span>
                            @endif
                        </td>

                        <!-- TOTAL HARGA -->
                        <td class="px-6 py-4 text-right font-black text-indigo-600 text-base">
                            Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic bg-slate-50/30">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <span class="text-2xl">📭</span>
                                <span class="font-medium">Belum ada data transaksi masuk di database.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

    <!-- LINK PAGINASI -->
    @if($transactions->hasPages())
    <div class="mt-4 p-4 bg-white border border-slate-100 rounded-2xl shadow-sm">
        {{ $transactions->links() }}
    </div>
    @endif

    <!-- CHART SECTION -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

        <!-- BAR CHART -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border">
            <h2 class="text-lg font-bold mb-4 text-slate-700">Transaksi per Status</h2>
            <div class="relative w-full h-64">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <!-- PIE CHART -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border">
            <h2 class="text-lg font-bold mb-4 text-slate-700">Distribusi Status</h2>
            <div class="relative w-full h-64">
                <canvas id="pieChart"></canvas>
            </div>
        </div>

    </div>

</div>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const success = @json($success);
    const pending = @json($pending);
    const free = @json($free);

    // BAR CHART
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: ['Success', 'Pending', 'Free'],
            datasets: [{
                label: 'Jumlah Transaksi',
                data: [success, pending, free],
                backgroundColor: ['#22c55e', '#f59e0b', '#64748b']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // PIE CHART
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: ['Success', 'Pending', 'Free'],
            datasets: [{
                data: [success, pending, free],
                backgroundColor: ['#22c55e', '#f59e0b', '#64748b']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endsection