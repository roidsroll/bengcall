<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_services', function (Blueprint $table) {
            $table->id();
            // Nomor Invoice Unik (Contoh: BNC-20260403-0001)
            $table->string('order_number')->unique();
            
            // Relasi ke User (Customer)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke Teknisi (Bisa kosong jika belum di-assign)
            $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');

            // Data Kendaraan (Disimpan string agar history tetap ada meski data kendaraan user berubah)
            $table->string('vehicle_name');
            $table->string('license_plate');
            
            // Keluhan Pelanggan
            $table->text('customer_notes')->nullable();
            
            // Keuangan
            $table->decimal('total_price', 12, 2)->default(0);
            
            // Status Tracking
            $table->enum('status', ['pending', 'processing', 'testing', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');
            
            // Bukti Kerja (Path Gambar)
            $table->string('completion_proof')->nullable();

            $table->timestamps();
            
            // Indexing untuk pencarian cepat
            $table->index(['order_number', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_services');
    }
};
