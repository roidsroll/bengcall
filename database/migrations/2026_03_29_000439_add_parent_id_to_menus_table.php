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
       Schema::table('menus', function (Blueprint $table) {
            // Tambah kolom parent_id setelah kolom id
            // nullable() karena menu utama tidak punya parent
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');

            // Opsional: Tambah foreign key ke tabel itu sendiri (Self-Referencing)
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            //
        });
    }
};
