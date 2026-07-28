<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek jika kolom 'role' belum ada, baru tambahkan
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['superadmin', 'organizer', 'customer'])->default('customer')->after('email');
            }
            
            // Cek jika kolom 'organization_name' belum ada, baru tambahkan
            if (!Schema::hasColumn('users', 'organization_name')) {
                $table->string('organization_name')->nullable()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'organization_name')) {
                $table->dropColumn('organization_name');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};