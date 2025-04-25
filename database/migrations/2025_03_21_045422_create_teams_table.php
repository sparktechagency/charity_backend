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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('designation',255);
            $table->string('work_experience',255);
            $table->string('photo',255)->default('deafult/profile.png');
            $table->text('twitter_link')->nullable();
            $table->text('linkedIn_link')->nullable();
            $table->text('instagram_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
