<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    /**
     * Menampilkan daftar semua penyelenggara / Ormawa (Khusus Superadmin)
     */
    public function index()
    {
        // Ambil semua user dengan role organizer atau admin
        $organizers = User::whereIn('role', ['organizer', 'admin'])
            ->withCount('events')
            ->latest()
            ->paginate(10);

        return view('admin.organizers.index', compact('organizers'));
    }

    /**
     * Menghapus/menonaktifkan akun penyelenggara
     */
    public function destroy(User $organizer)
    {
        // Cegah menghapus superadmin
        if ($organizer->role === 'superadmin') {
            return back()->with('error', 'Tidak dapat menghapus akun Superadmin!');
        }

        $organizer->delete();

        return back()->with('success', 'Akun Penyelenggara berhasil dihapus.');
    }
}