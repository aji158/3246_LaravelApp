<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\Category;

class CheckoutController extends Controller
{
    // 1. Menampilkan Halaman Form Checkout (Modul 10)
    public function create(Event $event)
    {
        $categories = Category::all();
        return view('checkout.create', compact('event', 'categories'));
    }

    // 2. Memproses Simpan Data & Generate Snap Token Midtrans (Modul 11)
    public function store(Request $request, Event $event)
    {
        // Validasi Input
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // Cek Stok Tiket
        if ($event->stock <= 0) {
            return back()->with('error', 'Maaf, stok tiket untuk event ini sudah habis.');
        }

        // Kalkulasi Harga (+ Biaya Layanan Rp 5.000)
        $totalPrice = $event->price + 5000;
        $orderId = 'TRX-' . time() . '-' . rand(100, 999);

        // Merekam Transaksi ke Database
        $transaction = Transaction::create([
            'order_id' => $orderId,
            'event_id' => $event->id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Mengurangi stok event
        $event->decrement('stock');

        // --- INTEGRASI SNAP MIDTRANS ---
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production'); 
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
        ];

        try {
            // Tembak API Midtrans untuk mendapatkan Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Simpan snap token ke database
            $transaction->update(['snap_token' => $snapToken]);
            
            return redirect()->route('checkout.payment', $transaction->order_id);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran jaringan: ' . $e->getMessage());
        }
    }

    // 3. Menampilkan Halaman Arah Ulang Jendela Pembayaran (Modul 11)
    public function payment($order_id)
    {
        $categories = Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        return view('checkout.payment', compact('transaction', 'categories'));
    }

    // 4. Menampilkan Halaman Sukses & Validasi Status Midtrans (Modul 11)
    public function success($order_id)
{
    // Mengambil daftar kategori untuk keperluan menu footer
   $categories = Category::all();
   
    $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
    
    // Konfigurasi Midtrans untuk mengecek status transaksi langsung ke API
    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    \Midtrans\Config::$isProduction = false;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    try {
        // Mengecek status pesanan secara mandiri (Bypass)
        $status = \Midtrans\Transaction::status($order_id);
        
        if ($status) {
            // Mengambil nilai status transaksi
            $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');
            
            // Jika API Midtrans mengonfirmasi bahwa transaksi telah berhasil (settlement / capture)
            if (in_array($trx_status, ['settlement', 'capture'])) {
                // Hanya lakukan update jika status di database lokal masih 'pending' (indikasi Webhook tidak masuk)
                if (strtolower($transaction->status) === 'pending') {
                    $transaction->update(['status' => 'success']);
                    
                    if ($transaction->event && $transaction->event->stock > 0) {
                        $transaction->event->stock = $transaction->event->stock - 1;
                        $transaction->event->save();
                        
                        try {
                            \Illuminate\Support\Facades\Mail::to($transaction->customer_email)
                                ->send(new \App\Mail\EventTicketMail($transaction));
                        } catch (\Exception $e) {
                            \Log::error('Gagal mengirim email E-Ticket secara manual (Bypass): ' . $e->getMessage());
                        }
                    }
                }
            }
        }
    } catch (\Exception $e) {
        // Jika terjadi error dari API Midtrans (transaksi tidak valid), kembalikan ke beranda
        return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem pembayaran.');
    }

    return view('checkout.success', compact('transaction', 'categories'));
}
}