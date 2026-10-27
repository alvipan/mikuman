<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('routers', function (Blueprint $table) {
            $table->string('lan_ip')->default('192.168.88.1')->after('is_active');
            $table->string('lan_interface')->default('bridge-lan')->after('lan_ip');
        });
    }

    public function down(): void
    {
        Schema::table('routers', function (Blueprint $table) {
            $table->dropColumn(['lan_ip', 'lan_interface']);
        });
    }
};
