<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('routers', function (Blueprint $table) {
            // Tambah kolom is_active setelah password
            $table->boolean('is_active')->default(true)->after('password');

            // Hapus kolom status dan last_seen_at
            $table->dropColumn(['status', 'last_seen_at']);
        });
    }

    public function down(): void
    {
        Schema::table('routers', function (Blueprint $table) {
            // Kembalikan kolom jika di-rollback
            $table->string('status')->default('offline');
            $table->timestamp('last_seen_at')->nullable();

            // Hapus is_active
            $table->dropColumn('is_active');
        });
    }
};
