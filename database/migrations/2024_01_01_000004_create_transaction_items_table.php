<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('quantity');
            $table->decimal('total_amount', 14, 2);
            $table->integer('quantity_before');
            $table->integer('quantity_after');
            $table->foreignUuid('product_id')->constrained('products');
            $table->foreignUuid('transaction_id')->constrained('transactions');
            $table->foreignUuid('created_by_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_items');
    }
}; 