<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint; // Pastikan baris ini ada
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Gunakan Blueprint, bukan Table
        Schema::table('categories', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('code_parts')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('code');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('code_parts');
        });
    }
};
