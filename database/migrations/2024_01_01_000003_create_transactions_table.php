<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', ['IN', 'OUT', 'EXPIRED', 'BROKEN', 'OTHERS']);
            $table->string('customer_email')->nullable();
            $table->string('customer_name')->nullable();
            $table->decimal('total_amount', 14, 2)->nullable();
            $table->string('supplier_name')->nullable();
            $table->text('notes')->nullable();
            $table->foreignUuid('created_by_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}; 