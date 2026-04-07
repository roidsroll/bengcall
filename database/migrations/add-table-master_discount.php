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
        Schema::create('master_discount', function (Blueprint $table) {
            $table->id();
            $table->string('discount_code')->unique(); // Kode promo, misal: HEMAT20
            $table->string('name'); // Nama program, misal: Promo Ramadhan
            $table->text('description')->nullable();
            
            // Tipe diskon: percentage (persen) atau fixed (nominal rupiah)
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            
            // Nilai diskon
            $table->decimal('value', 15, 2);
            
            // Syarat minimal belanja
            $table->decimal('min_purchase', 15, 2)->default(0);
            
            // Batas maksimal potongan (penting untuk diskon tipe percentage)
            $table->decimal('max_discount', 15, 2)->nullable();
            
            // Pengaturan durasi
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            
            // Pengaturan kuota
            $table->integer('quota')->default(-1); // -1 berarti tidak terbatas
            $table->integer('used_count')->default(0); // Menghitung berapa kali sudah terpakai
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); // Opsional: agar data lama tetap tersimpan untuk audit
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_discount');
    }
};