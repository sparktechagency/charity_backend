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
        Schema::create('podcast_stores', function (Blueprint $table) {
            $table->id();
            $table->string('podcast_title',255);
            $table->string('host_title',255);
            $table->string('guest_title',255);
            $table->string('host_profile')->default('deafult/user.png');
            $table->string('guest_profile')->default('deafult/user.png');
            $table->text('description')->nullable();
            $table->string('mp3');
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcast_stores');
    }
};
