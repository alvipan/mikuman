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
        Schema::create('pppoe_secrets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('router_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profile_id')->nullable()->constrained('pppoe_profiles')->nullOnDelete();
            $table->string('mikrotik_id')->nullable();

            $table->string('username');
            $table->string('password');

            $table->string('service')->default('pppoe');
            $table->string('local_address')->nullable();
            $table->string('remote_address')->nullable();

            $table->boolean('disabled')->default(true);
            $table->timestamp('activated_at')->useCurrent();
            $table->timestamp('expired_at')->nullable();

            $table->timestamps();

            $table->unique(['router_id', 'username']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pppoe_secrets');
    }
};
