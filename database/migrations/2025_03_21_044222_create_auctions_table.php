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
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();
            $table->string('title',255);
            $table->text('description');
            $table->string('image')->nullable();
            $table->decimal('donate_share',10,2);
            $table->string('name',255);
            $table->string('email',255);
            $table->string('contact_number',100);
            $table->string('city')->nullable();
            $table->string('address',255)->nullable();
            $table->string('profile',255)->nullable();
            $table->enum('payment_type',['card','apple_pay','google_pay','paypal_pay'])->nullable();
            $table->string('card_number')->nullable();
            $table->decimal('start_budget',10,2)->default(0);
            $table->decimal('end_budget',10,2)->default(0);
            $table->integer('duration')->nullable();
            $table->enum('status',['Pending','Declared','Completed'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
