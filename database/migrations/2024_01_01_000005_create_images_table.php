<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('filename');
            $table->string('full_path');
            $table->string('mime_type');
            $table->decimal('size', 10, 2); // in kb
            $table->foreignUuid('created_by_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('images');
    }
}; 