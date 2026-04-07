<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * STRUKTUR KOLOM (Urutan Logic):
     * 1. ID & Identifiers (kode unik)
     * 2. Customer Info (siapa yang order)
     * 3. Service Details (apa yang di-order)
     * 4. Technician (siapa yang handle)
     * 5. Pricing (berapa harga)
     * 6. Status & Tracking (kondisi saat ini)
     * 7. Timestamps (kapan)
     */
    public function up(): void
    {
        Schema::create('order_services', function (Blueprint $table) {
            
            // ========== 1. PRIMARY KEY & IDENTIFIERS ==========
            $table->id();
            $table->string('service_code')->unique()->comment('Unique service code (SRV-20260403-001)');
            $table->string('service_id')->nullable()->comment('External/3rd party service ID');

            // ========== 2. CUSTOMER INFORMATION ==========
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null')->comment('Customer account (nullable untuk walk-in)');
            $table->string('name')->comment('Customer full name');
            $table->string('call_number', 20)->comment('Customer phone number');
            $table->text('address')->comment('Service location/address');

            // ========== 3. SERVICE DETAILS ==========
            $table->text('description')->comment('Service description (oil change, etc)');
            $table->string('service_type')->nullable()->comment('Type: maintenance, repair, inspection, etc');
            $table->dateTime('scheduled_date')->nullable()->comment('Scheduled service date');

            // ========== 4. TECHNICIAN ASSIGNMENT ==========
            $table->foreignId('technician_id')->constrained('users')->onDelete('restrict')->comment('Assigned technician/mechanic');
            $table->dateTime('started_at')->nullable()->comment('When technician started');
            $table->dateTime('completed_at')->nullable()->comment('When service completed');

            // ========== 5. PRICING & PAYMENT ==========
            $table->decimal('total_price', 10, 2)->comment('Total service cost');
            $table->decimal('paid_amount', 10, 2)->default(0)->comment('Amount already paid');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->string('payment_method')->nullable()->comment('Payment method used');

            // ========== 6. STATUS & TRACKING ==========
            $table->enum('status', [
                'pending',      // Menunggu estimasi
                'quoted',       // Sudah kasih quote
                'confirmed',    // Customer confirm
                'in_progress',  // Sedang dikerjakan
                'completed',    // Selesai
                'cancelled'     // Dibatalkan
            ])->default('pending')->comment('Current service status');
            
            $table->string('notes')->nullable()->comment('Additional notes');

            // ========== 7. TIMESTAMPS & AUDIT ==========
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes()->comment('Soft delete for data integrity');

            // ========== INDEXES (Performance) ==========
            $table->index('service_code');
            $table->index('user_id');
            $table->index('technician_id');
            $table->index('status');
            $table->index('payment_status');
            $table->index('scheduled_date');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};