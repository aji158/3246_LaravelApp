<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    // Mengatur kolom yang boleh diisi secara massal (Mass Assignment) sesuai ERD
    protected $fillable = ['name', 'created_by', 'updated_by'];

    /**
     * Relasi One-to-Many ke model Pengurus
     * Satu Jabatan bisa dimiliki oleh banyak Pengurus
     */
    public function penguruses()
    {
        return $this->hasMany(Pengurus::class);
    }
}