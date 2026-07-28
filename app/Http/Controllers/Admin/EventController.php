<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * 🔹 INDEX (Soal 1 Fitur 3: Multi-Tenant Filter)
     * Superadmin melihat semua event, sedangkan Organizer hanya melihat event miliknya.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->role === 'superadmin') {
            // Superadmin dapat melihat semua event beserta informasi penyelenggaranya
            $events = Event::with(['category', 'user'])->latest()->paginate(10);
        } else {
            // Organizer / HIMA hanya bisa melihat event buatan sendiri
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
     * Menyimpan event baru dan menetapkan `user_id` pemilik event.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',    
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:0',
            'poster'      => 'nullable|image|max:2048' // Maksimal 2MB
        ]);

        // Tetapkan user_id sesuai panitia/admin yang sedang login
        $data['user_id'] = auth()->id();

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        Event::create($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Data Event berhasil ditambahkan.');
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
     * Memperbarui data event dengan proteksi kepemilikan.
     */
    public function update(Request $request, Event $event)
    {
        // Proteksi Akses
        if (auth()->user()->role !== 'superadmin' && $event->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk memperbarui event ini.');
        }

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:0',
            'poster'      => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('poster')) {
            // Hapus poster lama jika ada
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }

            // Upload poster baru
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * 🔹 DESTROY
     * Menghapus event beserta filenya dengan proteksi kepemilikan.
     */
    public function destroy(Event $event)
    {
        // Proteksi Akses
        if (auth()->user()->role !== 'superadmin' && $event->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus event ini.');
        }

        // Hapus poster dari storage jika ada
        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Data event berhasil dihapus secara permanen.');
    }
}