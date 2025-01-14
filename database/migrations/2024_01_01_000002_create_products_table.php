<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->decimal('price', 14, 2);
            $table->enum('unit', ['pcs', 'kilogram', 'mililiter', 'liter', 'gram', 'ton']);
            $table->integer('quantity')->default(0);
            $table->foreignUuid('created_by_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}; 