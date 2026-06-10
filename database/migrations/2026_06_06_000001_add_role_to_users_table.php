<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Role sudah ditambahkan di create_users_table migration
        // Migration ini bisa di-skip atau dihapus
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip
    }
};
