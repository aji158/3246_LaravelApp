<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Pembeli
            $table->foreignId('event_id')->constrained()->onDelete('cascade'); // Event yang dinilai
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade'); // Panitia/Penyelenggara
            $table->unsignedTinyInteger('rating'); // nilainya 1 - 5
            $table->text('comment')->nullable(); // ulasan teks
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};