<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('branch_code')->unique();
            $table->string('name');
            $table->text('address');
            $table->string('phone', 20)->nullable();
            
            // Geolocation (Opsional untuk Maps)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Operasional
            $table->time('opening_time')->default('08:00');
            $table->time('closing_time')->default('22:00');
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            
            // Relasi (Contoh ke tabel users untuk Manager)
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // Biar data nggak langsung hilang permanen (deleted_at)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};

