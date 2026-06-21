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
        Schema::create('hotspot_users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('router_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profile_id')->constrained('hotspot_profiles');
            $table->foreignId('reseller_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('mikrotik_id')->nullable()->index();

            // core
            $table->string('username')->unique();
            $table->string('password');

            // grouping
            $table->string('batch')->index();

            // snapshot
            $table->decimal('sale_price', 12, 2)->default(0);
            $table->decimal('cost_price', 12, 2)->default(0);

            // lifecycle & usage tracking
            $table->timestamp('used_at')->nullable()->index();
            $table->string('used_ip')->nullable();
            $table->string('used_mac')->nullable();
            $table->timestamp('expired_at')->nullable()->index();

            $table->enum('status', [
                'unused',
                'used',
                'expired'
            ])->default('unused');

            $table->timestamps();

            // indexes
            $table->index(['router_id', 'status']);
            $table->index(['profile_id', 'status']);
            $table->index(['batch', 'status']);
            $table->index(['reseller_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotspot_users');
    }
};
