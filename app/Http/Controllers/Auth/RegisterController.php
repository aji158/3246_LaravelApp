<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users',
            'password'          => 'required|string|min:8|confirmed',
            'role'              => 'required|in:customer,organizer',
            'organization_name' => 'nullable|required_if:role,organizer|string|max:255',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => $request->role,
            'organization_name' => $request->role === 'organizer' ? $request->organization_name : null,
        ]);

        Auth::login($user);

        // Jika pendaftar adalah Organizer/Ormawa, arahkan langsung ke Dashboard Admin
        if ($user->role === 'organizer') {
            return redirect()->route('admin.dashboard')->with('success', 'Registrasi Organisasi Berhasil! Selamat datang di Dashboard.');
        }

        return redirect('/')->with('success', 'Registrasi berhasil! Selamat datang.');
    }
}