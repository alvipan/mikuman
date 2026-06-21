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
        Schema::create('hotspot_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('router_id')->constrained()->cascadeOnDelete()->index();
            $table->string('mikrotik_id')->nullable();

            $table->string('name');
            $table->decimal('sale_price', 12, 2);
            $table->decimal('cost_price', 12, 2)->default(0);

            $table->string('validity')->nullable(); 
            $table->unsignedInteger('shared_users')->default(1);
            $table->string('rate_limit')->nullable();
            $table->integer('code_length')->default(6);

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotspot_profiles');
    }
};
