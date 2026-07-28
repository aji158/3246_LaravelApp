<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;

class EventController extends Controller
{
    public function show(Event $event)
    {
        // Mengambil daftar kategori untuk keperluan menu footer
        $categories = Category::all();

        // Me-render view dengan membawa data kategori dan data spesifik acara tersebut
        return view('event-detail', compact('categories', 'event'));
    }


    public function checkout()
    {
        return view('checkout');
    }

    public function ticket()
    {
        return view('ticket'); // halaman tiket user
    }

    public function indexAdmin()
    {
        return view('admin.events'); // halaman admin event list
    }

    public function update(Request $request, Event $event)
{
    // ...
    $data = $request->validate([
        'category_id' => 'required',
        'title'       => 'required',
        'description' => 'nullable',
        'date'        => 'required',
        'location'    => 'required',
        'price'       => 'required|numeric',
        'stock'       => 'required|numeric',
        'poster'      => 'nullable|image|max:2048'
    ]);

    if ($request->hasFile('poster')) {
    $cloudinary = $this->getCloudinaryInstance();
    $uploaded = $cloudinary->uploadApi()->upload(
        $request->file('poster')->getRealPath(),
        ['folder' => 'amikom_event_posters']
    );
    
    // Set kedua key agar mengisi kolom 'poster' maupun 'poster_path' di MySQL
    $data['poster']      = $uploaded['secure_url'];
    $data['poster_path'] = $uploaded['secure_url'];
}

    $event->update($data); // <-- Pastikan $data dipassing ke sini
    // ...
}
}
