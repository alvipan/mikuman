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
        Schema::create('routers', function (Blueprint $table) {
            $table->id();
            $table->string('host')->unique();
            $table->string('user');
            $table->string('pass');
            $table->string('name')->default('Mikuman.NET');
            $table->string('currency')->default('Rp');
            $table->string('interface')->nullable();
            $table->string('theme')->default('dark');
            $table->string('mikhmon_expire_monitor')->default('enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routers');
    }
};
