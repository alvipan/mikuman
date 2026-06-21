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
        Schema::create('pppoe_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('router_id')->constrained()->cascadeOnDelete();
            $table->string('mikrotik_id')->nullable();

            $table->string('name');
            $table->decimal('price', 12, 2)->default(0);

            $table->string('rate_limit')->nullable(); // contoh: 10M/10M
            $table->string('local_address')->nullable();
            $table->string('remote_address')->nullable();

            $table->boolean('only_one')->default(true);
            $table->boolean('change_tcp_mss')->default(true);

            $table->timestamps();

            $table->unique(['router_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pppoe_profiles');
    }
};
