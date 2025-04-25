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
        Schema::create('contributors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained('auctions')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('contact_number', 100);
            $table->decimal('bit_online', 10, 2);
            $table->enum('payment_type', ['card', 'apple_pay', 'google_pay', 'paypal_pay'])->default('card');
            $table->string('card_number');
            $table->enum('status',['winner','pending','rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributors');
    }
};
