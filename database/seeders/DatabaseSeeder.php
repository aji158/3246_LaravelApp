<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Utama
        \App\Models\User::create([
            'name' => 'Admin Amikom',
            'email' => 'admin@amikom.ac.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 2. Insert Kategori Event
        $category = \App\Models\Category::create([
            'name' => 'Seminar IT',
            'slug' => 'seminar-it',
        ]);


        $category2 = \App\Models\Category::firstOrCreate([
            'name' => 'Entertaiment',
            'slug' => 'entertaiment',
        ]);

        $category3 = \App\Models\Category::firstOrCreate([
            'name' => 'Festival',
            'slug' => 'festival',
        ]);

        $category4 = \App\Models\Category::firstOrCreate([
            'name' => 'Pengajinan',
            'slug' => 'pengajian',
        ]);

        $category5 = \App\Models\Category::firstOrCreate([
            'name' => 'Hadroh',
            'slug' => 'hadroh',
        ]);


        // 3. Insert Sampel Events
        \App\Models\Event::create([
            'category_id' => $category2->id,
            'title' => 'Jazz Night 2025',
            'description' => 'Nikmati malam yang indah dengan alunan musik jazz yang merdu.',

            'date' => '2026-05-10 19:00:00',
            'location' => 'Amikom Baru',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-1.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'Hackaton - Unleash Your Inner Developer',
            'description' => 'Ayo asah skill coding kamu dan ciptakan solusi inovatif untuk tantangan masa depan!',
            'date' => '2026-05-05 10:00:00',



            'location' => 'Inkubator Amikom',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-2.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'AI & FUTURE TECH SUMMIT 2026',
            'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan teknologi masa depan bersama para ahli di bidangnya.',

            'date' => '2026-05-01 13:00:00',
            'location' => 'Cinema Unit 6',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-3.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category->id,
            'title' => 'Festival metal',
            'description' => 'Jelajahi tren terkini dalam kecerdasan buatan dan teknologi masa depan bersama para ahli di bidangnya.',

            'date' => '2026-05-01 13:00:00',
            'location' => 'Cinema Unit 6',
            'price' => 50000,
            'stock' => 100,
            'poster_path' => 'posters/event-3.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category3->id,
            'title' => 'Festival Metal Underground',
            'description' => 'Festival musik metal yang menghadirkan band-band underground terbaik dari berbagai daerah.',
            'date' => '2026-05-02 15:00:00',
            'location' => 'Lapangan Unit 1',
            'price' => 75000,
            'stock' => 150,
            'poster_path' => 'posters/event-1.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category3->id,
            'title' => 'Metal Night Experience',
            'description' => 'Nikmati pengalaman konser metal dengan lighting dan sound system spektakuler.',
            'date' => '2026-05-05 19:00:00',
            'location' => 'Auditorium Kampus',
            'price' => 100000,
            'stock' => 200,
            'poster_path' => 'posters/event-2.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category4->id,
            'title' => 'Pengajian Komunitas Metal',
            'description' => 'Kajian islami dengan pendekatan komunitas metal, membahas kehidupan dan spiritualitas.',
            'date' => '2026-05-03 18:00:00',
            'location' => 'Masjid Kampus',
            'price' => 0,
            'stock' => 300,
            'poster_path' => 'posters/event-3.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category4->id,
            'title' => 'Ngaji Santai Anak Metal',
            'description' => 'Diskusi santai seputar agama dengan gaya anak metal, terbuka untuk umum.',
            'date' => '2026-05-06 19:30:00',
            'location' => 'Ruang Seminar 2',
            'price' => 0,
            'stock' => 150,
            'poster_path' => 'posters/event-4.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category5->id,
            'title' => 'Hadroh Metal Fusion',
            'description' => 'Perpaduan musik hadroh dengan nuansa metal yang unik dan energik.',
            'date' => '2026-05-04 20:00:00',
            'location' => 'Panggung Terbuka',
            'price' => 25000,
            'stock' => 120,
            'poster_path' => 'posters/event-5.png',
        ]);

        \App\Models\Event::create([
            'category_id' => $category5->id,
            'title' => 'Sholawat Metal Night',
            'description' => 'Lantunan sholawat dengan aransemen metal modern yang menggugah semangat.',
            'date' => '2026-05-07 20:00:00',
            'location' => 'Gedung Serbaguna',
            'price' => 30000,
            'stock' => 180,
            'poster_path' => 'posters/event-6.png',
        ]);
    }
}
