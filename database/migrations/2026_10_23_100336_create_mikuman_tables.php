<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Routers (NAS)
        Schema::create('routers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->unique();
            $table->enum('protocol', ['http', 'https'])->default('https');
            $table->integer('port')->default(443);
            $table->string('username')->nullable();
            $table->text('password')->nullable(); // Password REST (Encrypted)
            $table->string('status')->default('offline');
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Resellers (Bisa login)
        Schema::create('resellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable(); // Untuk login
            $table->string('password'); // Hash password
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        // 3. Tabel Packages
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['hotspot', 'pppoe']);
            $table->string('rate_limit')->default('3M/3M');
            $table->integer('validity_value')->default(30);
            $table->enum('validity_unit', ['hour', 'day', 'month'])->default('day');
            $table->integer('shared_users')->default(1);
            $table->decimal('sale_price', 12, 2)->default(0);
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Tabel Customers (Data Personal Pelanggan)
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable(); // Untuk login portal pelanggan
            $table->string('password'); // Hash password portal
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true); // Status akun portal aktif/tidak
            $table->timestamps();
        });

        // 5. Tabel Secrets (Kredensial PPPoE)
        Schema::create('secrets', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique(); // Username PPPoE
            $table->string('password'); // Password PPPoE (Cleartext untuk RADIUS)
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->foreignId('router_id')->nullable()->constrained('routers')->nullOnDelete();
            $table->enum('status', ['active', 'isolir', 'expired'])->default('active');
            $table->date('billing_date')->nullable(); // Jatuh tempo spesifik per koneksi
            $table->timestamps();
        });

        // 6. Tabel Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('reseller_id')->nullable()->constrained('resellers')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->decimal('total_amount', 12, 2);
            $table->enum('type', ['collection', 'pppoe_payment']);
            $table->string('payment_method')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed'])->default('paid');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // 7. Tabel Vouchers
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->foreignId('reseller_id')->nullable()->constrained('resellers')->nullOnDelete();
            $table->foreignId('router_id')->nullable()->constrained('routers')->nullOnDelete();
            $table->enum('status', ['unused', 'used', 'expired'])->default('unused');
            $table->decimal('sale_price', 12, 2)->default(0);
            $table->decimal('cost_price', 12, 2)->default(0);
            $table->timestamp('used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // 8. Tabel Reseller Commissions 
        Schema::create('reseller_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained('resellers')->cascadeOnDelete();
            $table->foreignId('voucher_id')->constrained('vouchers')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // 9. Tabel Settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->string('group')->default('general'); // general, billing, radius, whatsapp
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reseller_commissions');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('secrets');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('resellers');
        Schema::dropIfExists('routers');
        Schema::dropIfExists('settings');
    }
};
