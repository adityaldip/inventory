<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->uuid('imageable_id')->nullable()->after('size');
            $table->string('imageable_type')->nullable()->after('imageable_id');
            
            $table->index(['imageable_id', 'imageable_type']);
        });
    }

    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropIndex(['imageable_id', 'imageable_type']);
            $table->dropColumn(['imageable_id', 'imageable_type']);
        });
    }
}; 