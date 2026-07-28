<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Query Utama
        $eventsQuery = Event::query();
        $transactionsQuery = Transaction::query();

        // Filter jika role organizer (hanya ambil data milik sendiri)
        if ($user && $user->role === 'organizer') {
            $eventsQuery->where('user_id', $user->id);
            $transactionsQuery->whereHas('event', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        // 1. Deklarasi Ringkasan Statistik (Variabel yang dicari compact)
        $totalEvents          = (clone $eventsQuery)->count();
        $totalTransactions    = (clone $transactionsQuery)->whereIn('status', ['success', 'settlement'])->count();
        $totalRevenue         = (clone $transactionsQuery)->whereIn('status', ['success', 'settlement'])->sum('total_price');
        $pendingTransactions  = (clone $transactionsQuery)->where('status', 'pending')->count();

        // 2. Transaksi Terakhir (Hanya eager-load relasi event)
        $recentTransactions = (clone $transactionsQuery)
            ->with(['event'])
            ->latest()
            ->take(5)
            ->get();

        // 3. Array Inisialisasi Grafik Bulan 1 - 12
        $monthlyEventsData  = array_fill(1, 12, 0);
        $monthlyTicketsData = array_fill(1, 12, 0);

        // --- Data Grafik 1: Pertumbuhan Event ---
        $eventsPerMonth = Event::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->when($user && $user->role === 'organizer', function ($q) use ($user) {
            return $q->where('user_id', $user->id);
        })
        ->whereYear('created_at', date('Y'))
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get();

        foreach ($eventsPerMonth as $item) {
            $monthlyEventsData[$item->month] = $item->count;
        }

        // --- Data Grafik 2: Tren Tiket Terjual ---
        $ticketsPerMonth = Transaction::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->when($user && $user->role === 'organizer', function ($q) use ($user) {
            return $q->whereHas('event', function ($e) use ($user) {
                $e->where('user_id', $user->id);
            });
        })
        ->whereIn('status', ['success', 'settlement'])
        ->whereYear('created_at', date('Y'))
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get();

        foreach ($ticketsPerMonth as $item) {
            $monthlyTicketsData[$item->month] = $item->count;
        }

        $chartMonths      = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartEventsData  = array_values($monthlyEventsData);
        $chartTicketsData = array_values($monthlyTicketsData);

        return view('admin.dashboard', compact(
            'totalEvents',
            'totalTransactions',
            'totalRevenue',
            'pendingTransactions',
            'recentTransactions',
            'chartMonths',
            'chartEventsData',
            'chartTicketsData'
        ));
    }
}