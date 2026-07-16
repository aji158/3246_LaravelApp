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
}
