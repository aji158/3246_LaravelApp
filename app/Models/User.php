<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'google_id', 'role', 'organization_name'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Relasi ke Event yang dibuat oleh Organizer/Panitia (Soal 1 Fitur 3: Multi-Tenant)
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    /**
     * Relasi ulasan yang diterima oleh Organizer (Soal 1 Fitur 2: Rating & Review)
     */
    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'organizer_id');
    }

    /**
     * Menghitung rata-rata rating bintang untuk profil Organizer
     */
    public function averageRating()
    {
        return round($this->reviewsReceived()->avg('rating') ?? 0, 1);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}