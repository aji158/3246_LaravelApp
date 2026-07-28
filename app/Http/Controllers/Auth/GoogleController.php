<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari atau buat user baru berdasarkan email
            $user = User::updateOrCreate([
                'email' => $googleUser->getEmail(),
            ], [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                // Buat password acak karena user login via Google
                'password' => bcrypt(Str::random(16)), 
            ]);

            Auth::login($user);

            return redirect()->route('home')->with('success', 'Berhasil login dengan Google!');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Gagal login via Google.');
        }
    }
}