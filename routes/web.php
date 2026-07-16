<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CheckoutController; // <-- 1. AKTIF DI MODUL 10
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TransactionController; // <-- 2. AKTIF DI MODUL 10
use App\Mail\EventTicketMail;
use App\Models\Transaction;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PengurusController;

// ==========================================
// Rute Area User / Publik
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Mengalihkan otomatis jika ada yang mengakses /admin atau /admin/ langsung ke halaman login admin
Route::redirect('/admin', '/admin/login');

// === Modul Pertemuan 10: Rute Alur Checkout Tamu (Guest) ===
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');

// Cadangan rute checkout lama kamu (Tetap dipertahankan agar tidak merusak kode lain)
Route::get('/checkout', [EventController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [EventController::class, 'processCheckout'])->name('checkout.process');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// Rute Alur Checkout Midtrans (Modul 11)
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

// === Modul Pertemuan 12: Webhook Endpoint (Publik & Bebas CSRF/Auth) ===
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle']);

// ==========================================
// Rute Area Admin
// ==========================================
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Grouping Utama URL /admin
Route::prefix('admin')->name('admin.')->group(function () {

    // Otentikasi Admin (Bisa diakses tanpa login)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Mengamankan Rute Administrasi di balik Lapisan Middleware (Wajib Login)
    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Resource CRUD Utama Proyek
        Route::resource('events', EventAdminController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('partners', PartnerController::class);

        // === Modul Pertemuan 10: Rute Laporan Transaksi Admin ===
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');

        // =========================================================================
        // RUTE UAS: Jabatan & Pengurus aman di dalam proteksi login admin
        // =========================================================================
        Route::resource('jabatan', JabatanController::class);
        Route::resource('pengurus', PengurusController::class);
    });
});