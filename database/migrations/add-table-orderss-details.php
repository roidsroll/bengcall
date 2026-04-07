<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Pecah menjadi dua kolom Foreign Key (Nullable).
            // Jika beli barang, product_id terisi, service_id null. Begitu sebaliknya.
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->foreignId('service_id')->nullable()->index();

            // Kolom pendukung tetap diperlukan untuk 'Snapshot' data.
            $table->string('item_name');
            $table->enum('type', ['part', 'service']);
            $table->integer('quantity')->default(1);
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_details');
    }
};
