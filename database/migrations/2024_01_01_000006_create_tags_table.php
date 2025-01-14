<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->uuid('tag_id');
            $table->uuid('taggable_id');
            $table->string('taggable_type');
            
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            $table->index(['taggable_id', 'taggable_type']);
        });

        Schema::create('imageables', function (Blueprint $table) {
            $table->uuid('image_id');
            $table->uuid('imageable_id');
            $table->string('imageable_type');
            
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
            $table->index(['imageable_id', 'imageable_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('imageables');
        Schema::dropIfExists('tags');
    }
}; 