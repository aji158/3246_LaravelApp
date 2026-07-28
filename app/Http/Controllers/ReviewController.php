<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Menyimpan ulasan dari halaman detail event (Publik)
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        Review::create([
            'user_id'      => auth()->id(),
            'event_id'     => $event->id,
            'organizer_id' => $event->user_id,
            'rating'       => $request->rating,
            'comment'      => $request->comment,
        ]);

        return back()->with('success', 'Ulasan dan rating bintang berhasil dikirim!');
    }

    /**
     * Menampilkan daftar ulasan di Dashboard Admin
     */
    public function indexAdmin()
    {
        $user = auth()->user();

        // Jika superadmin, lihat semua review. Jika organizer, lihat review event miliknya saja
        if ($user && $user->role === 'superadmin') {
            $reviews = Review::with(['user', 'event'])->latest()->paginate(15);
        } else {
            $reviews = Review::whereHas('event', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->with(['user', 'event'])->latest()->paginate(15);
        }

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Menghapus ulasan (Moderasi Admin)
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}