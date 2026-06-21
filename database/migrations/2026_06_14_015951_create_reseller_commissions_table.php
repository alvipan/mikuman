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
        Schema::create('reseller_commissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reseller_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('hotspot_user_id')
                ->nullable()
                ->constrained('hotspot_users')
                ->nullOnDelete();

            $table->enum('type', [
                'earn',
                'payout',
                'adjustment',
            ]);

            $table->decimal('amount', 15, 2);

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller_commissions');
    }
};
