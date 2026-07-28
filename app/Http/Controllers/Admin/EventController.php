<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Helper privat untuk inisialisasi Cloudinary SDK
     */
    private function getCloudinaryInstance()
{
    $cloudName = config('services.cloudinary.cloud_name') ?? env('CLOUDINARY_CLOUD_NAME');
    $apiKey    = config('services.cloudinary.api_key') ?? env('CLOUDINARY_API_KEY');
    $apiSecret = config('services.cloudinary.api_secret') ?? env('CLOUDINARY_API_SECRET');

    // Validasi sederhana agar tidak melempar Exception tak terduga
    if (!$cloudName || !$apiKey || !$apiSecret) {
        throw new \Exception('Konfigurasi Cloudinary belum lengkap di file .env!');
    }

    return new Cloudinary([
        'cloud' => [
            'cloud_name' => $cloudName,
            'api_key'    => $apiKey,
            'api_secret' => $apiSecret,
        ],
        'url' => [
            'secure' => true
        ]
    ]);
}

    /**
     * 🔹 INDEX (Soal 1 Fitur 3: Multi-Tenant Filter)
     * Superadmin melihat semua event, sedangkan Organizer hanya melihat event miliknya.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->role === 'superadmin') {
            // Superadmin melihat semua event beserta penyelenggaranya
            $events = Event::with(['category', 'user'])->latest()->paginate(10);
        } else {
            // Organizer / HIMA hanya melihat event buatan sendiri
            $events = Event::where('user_id', $user->id)
                ->with('category')
                ->latest()
                ->paginate(10);
        }

        return view('admin.events.index', compact('events'));
    }

    /**
     * 🔹 CREATE
     * Menampilkan form pembuatan event.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    /**
     * 🔹 STORE
     * Menyimpan event baru ke database & upload poster ke Cloudinary.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:0',
            'poster'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        // Set pemilik event (Multi-tenant)
        $data['user_id'] = auth()->id();

        // 2. Upload file poster ke Cloudinary jika ada
       if ($request->hasFile('poster')) {
    $cloudinary = $this->getCloudinaryInstance();

    $uploadedFile = $cloudinary->uploadApi()->upload(
        $request->file('poster')->getRealPath(),
        [
            'folder' => 'amikom_event_posters'
        ]
    );

    // ✅ Simpan URL Cloudinary ke 'poster_path'
    $data['poster_path'] = $uploadedFile['secure_url'];
}

unset($data['poster']);

Event::create($data);

        // 3. Simpan ke database
        Event::create($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Data Event berhasil ditambahkan dengan poster Cloudinary.');
    }

    /**
     * 🔹 EDIT
     * Menampilkan form edit dengan proteksi kepemilikan tenant.
     */
    public function edit(Event $event)
    {
        // Proteksi: Pastikan hanya pemilik event atau Superadmin yang bisa akses
        if (auth()->user()->role !== 'superadmin' && $event->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah event ini.');
        }

        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    /**
     * 🔹 UPDATE
     * Memperbarui data event & memperbarui poster ke Cloudinary.
     */
   public function update(Request $request, Event $event)
{
    // ... proteksi & validasi tetap sama ...

    $data = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'date'        => 'required|date',
        'location'    => 'required|string|max:255',
        'price'       => 'required|numeric|min:0',
        'stock'       => 'required|numeric|min:0',
        'poster'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
    ]);

    // Jika ada file poster baru diunggah ke Cloudinary
    if ($request->hasFile('poster')) {
        $cloudinary = $this->getCloudinaryInstance();

        $uploadedFile = $cloudinary->uploadApi()->upload(
            $request->file('poster')->getRealPath(),
            [
                'folder' => 'amikom_event_posters'
            ]
        );

        // ✅ KUNCI PERBAIKAN: Gunakan key 'poster_path' sesuai nama kolom DB
        $data['poster_path'] = $uploadedFile['secure_url'];
    }

    // Hapus key 'poster' dari array $data agar tidak memicu error "Unknown column poster"
    unset($data['poster']);

    $event->update($data);

    return redirect()
        ->route('admin.events.index')
        ->with('success', 'Event berhasil diperbarui.');
}

    /**
     * 🔹 DESTROY
     * Menghapus event dengan proteksi kepemilikan.
     */
    public function destroy(Event $event)
    {
        // Proteksi Akses
        if (auth()->user()->role !== 'superadmin' && $event->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus event ini.');
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Data event berhasil dihapus secara permanen.');
    }
}