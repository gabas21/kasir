<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Perluas enum 'role' di tabel users agar mencakup 'owner'.
     * Migration sebelumnya hanya mendefinisikan ['admin', 'kasir'].
     */
    public function up(): void
    {
        // MySQL: ALTER untuk ubah definisi enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'kasir', 'owner') NOT NULL DEFAULT 'admin'");
    }

    public function down(): void
    {
        // Kembalikan ke definisi awal (WARNING: user dengan role 'owner' akan error)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'kasir') NOT NULL DEFAULT 'admin'");
    }
};
