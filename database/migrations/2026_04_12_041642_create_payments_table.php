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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pppoe_secret_id')
                ->constrained()
                ->cascadeOnDelete();

            // nominal pembayaran
            $table->decimal('amount', 12, 2);
            $table->unsignedInteger('months')->default(1);

            // waktu bayar (penting untuk laporan)
            $table->timestamp('paid_at')->index();

            // optional (manual note / bukti transfer)
            $table->text('note')->nullable();

            $table->timestamps();

            // index tambahan
            $table->index('pppoe_secret_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
