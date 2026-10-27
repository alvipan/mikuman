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
        Schema::table('routers', function (Blueprint $table) {
            $table->string('hotspot_subnet')->default('10.10.0.0/16')->after('is_active');
            $table->string('pppoe_subnet')->default('10.20.0.0/16')->after('hotspot_subnet');
            $table->string('wlan_ssid')->nullable()->default('Mikuman.NET')->after('pppoe_subnet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routers', function (Blueprint $table) {
            //
        });
    }
};
