<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data user/admin yang sedang login
        $user = auth()->user();

        // 0. Ambil daftar ID event HANYA milik admin yang sedang login
        $myEventIds = Event::where('user_id', $user->id)->pluck('id');

        // 1. Menjumlahkan nominal total_price HANYA dari event milik admin ini
        $totalRevenue = Transaction::whereIn('event_id', $myEventIds)
            ->whereIn('status', ['settlement', 'success'])
            ->sum('total_price');

        // 2. Menghitung tiket Lunas HANYA dari event milik admin ini
        $ticketsSold = Transaction::whereIn('event_id', $myEventIds)
            ->whereIn('status', ['settlement', 'success'])
            ->count();

        // 3. Menghitung Acara Mendatang HANYA milik admin ini
        $activeEvents = Event::where('user_id', $user->id)
            ->where('date', '>=', now())
            ->count();

        // 4. Menghitung Transaksi Pending HANYA dari event milik admin ini
        $pendingOrders = Transaction::whereIn('event_id', $myEventIds)
            ->where('status', 'pending')
            ->count();

        // 5. Riwayat 5 pesanan mutakhir HANYA untuk event milik admin ini
        $recentTransactions = Transaction::whereIn('event_id', $myEventIds)
            ->with('event')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'ticketsSold', 
            'activeEvents', 
            'pendingOrders', 
            'recentTransactions'
        ));
    }
}