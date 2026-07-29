<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\Category;
use App\Mail\EventTicketMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class CheckoutController extends Controller
{
    /**
     * 1. Menampilkan Halaman Form Checkout
     */
    public function create(Event $event)
    {
        $categories = Category::all();
        return view('checkout.create', compact('event', 'categories'));
    }

    /**
     * 2. Memproses Simpan Data & Bypass untuk Event Gratis / Generate Snap Token Midtrans
     */
    public function store(Request $request, Event $event)
    {
        // Validasi Input
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // Cek Stok Tiket
        if ($event->stock <= 0) {
            return back()->with('error', 'Maaf, stok tiket untuk event ini sudah habis.');
        }

        // Cek Apakah Event Gratis
        $isFree = ($event->price == 0);

        // Kalkulasi Harga (Biaya Layanan Rp 0 jika event gratis)
        $adminFee   = $isFree ? 0 : 5000;
        $totalPrice = $event->price + $adminFee;
        $orderId    = 'TRX-' . time() . '-' . rand(100, 999);

        // ========================================================
        // 1. BYPASS TRANSAKSI UNTUK EVENT GRATIS (FREE EVENT)
        // ========================================================
        if ($isFree) {
            // Merekam Transaksi dengan status LANGSUNG 'success'
            $transaction = Transaction::create([
                'order_id'       => $orderId,
                'event_id'       => $event->id,
                'user_id'        => auth()->check() ? auth()->id() : null,
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'total_price'    => 0,
                'status'         => 'success',
            ]);

            // Potong Stok Tiket Saat Itu Juga
            $event->decrement('stock');

            // Kirim E-Ticket ke Email Pembeli (Gunakan Queue agar Async & tidak Timeout)
            try {
                Mail::to($transaction->customer_email)->queue(new EventTicketMail($transaction));
            } catch (Exception $e) {
                Log::error('Gagal mengirim email E-Ticket gratis: ' . $e->getMessage());
            }

            // Langsung alihkan ke Halaman Sukses / Cetak Tiket (Bypass Midtrans)
            return redirect()->route('checkout.success', $transaction->order_id)
                ->with('success', 'Tiket gratis berhasil didapatkan!');
        }

        // ========================================================
        // 2. ALUR REGULER MIDTRANS UNTUK EVENT BERBAYAR
        // ========================================================
        $transaction = Transaction::create([
            'order_id'       => $orderId,
            'event_id'       => $event->id,
            'user_id'        => auth()->check() ? auth()->id() : null,
            'customer_name'  => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price'    => $totalPrice,
            'status'         => 'pending',
        ]);

        // Integrasi Snap Midtrans
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production'); 
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email'      => $request->customer_email,
                'phone'      => $request->customer_phone,
            ],
        ];

        try {
            // Dapatkan Snap Token dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            // Simpan snap token ke database
            $transaction->update(['snap_token' => $snapToken]);
            
            return redirect()->route('checkout.payment', $transaction->order_id);
            
        } catch (Exception $e) {
            Log::error('Gagal mendapatkan Midtrans Snap Token: ' . $e->getMessage());
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * 3. Menampilkan Halaman Pembayaran (Snap Midtrans Embed)
     */
    public function payment($order_id)
    {
        $categories  = Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();

        return view('checkout.payment', compact('transaction', 'categories'));
    }

    /**
     * 4. Menampilkan Halaman Sukses & Validasi Status Midtrans
     */
    public function success($order_id)
    {
        $categories  = Category::all();
        $transaction = Transaction::with('event')->where('order_id', $order_id)->firstOrFail();
        
        // Jika status lokal sudah 'success', langsung tampilkan view tanpa hit API lagi
        if (strtolower($transaction->status) === 'success') {
            return view('checkout.success', compact('transaction', 'categories'));
        }

        // Validasi ke Midtrans khusus transaksi berbayar
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        try {
            // Mengecek status pesanan langsung dari API Midtrans
            $status = \Midtrans\Transaction::status($order_id);
            
            if ($status) {
                $trx_status = is_array($status) ? ($status['transaction_status'] ?? '') : ($status->transaction_status ?? '');
                
                // Jika pembayaran dikonfirmasi berhasil (settlement / capture)
                if (in_array($trx_status, ['settlement', 'capture'])) {
                    
                    if (strtolower($transaction->status) === 'pending') {
                        $transaction->update(['status' => 'success']);
                        
                        // Potong stok tiket secara aman
                        if ($transaction->event && $transaction->event->stock > 0) {
                            $transaction->event->decrement('stock');
                        }
                        
                        // Kirimkan E-Ticket ke Email Pembeli (Gunakan Queue agar Async)
                        try {
                            Mail::to($transaction->customer_email)->queue(new EventTicketMail($transaction));
                        } catch (Exception $e) {
                            Log::error('Gagal mengirim email E-Ticket: ' . $e->getMessage());
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // Tetap izinkan melihat halaman sukses jika status lokal sudah 'success'
            if ($transaction->status !== 'success') {
                return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan atau gagal diproses oleh sistem.');
            }
        }

        return view('checkout.success', compact('transaction', 'categories'));
    }
}