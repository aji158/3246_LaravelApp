<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        Review::create([
            'user_id'      => auth()->id(),
            'event_id'     => $event->id,
            'organizer_id' => $event->user_id, // <-- DITAMBAHKAN AMBIL DARI EVENT
            'rating'       => $request->rating,
            'comment'      => $request->comment,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim!');
    }
}