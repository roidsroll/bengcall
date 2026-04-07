<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE menus MODIFY url VARCHAR(255) NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE menus ALTER COLUMN url DROP NOT NULL');
        } elseif ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE menus ALTER COLUMN url NVARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("UPDATE menus SET url = '' WHERE url IS NULL");
            DB::statement('ALTER TABLE menus MODIFY url VARCHAR(255) NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement("UPDATE menus SET url = '' WHERE url IS NULL");
            DB::statement('ALTER TABLE menus ALTER COLUMN url SET NOT NULL');
        } elseif ($driver === 'sqlsrv') {
            DB::statement("UPDATE menus SET url = '' WHERE url IS NULL");
            DB::statement('ALTER TABLE menus ALTER COLUMN url NVARCHAR(255) NOT NULL');
        }
    }
};

