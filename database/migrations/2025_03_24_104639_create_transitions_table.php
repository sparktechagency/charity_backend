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
        Schema::create('transitions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice');
            $table->string('transaction_id');
            $table->enum('payment_type',['card','apple_pay','google_pay','paypal_pay'])->default('card');
            $table->enum('donation_type',['one_time_donate','recurring'])->default('one_time_donate');
            $table->enum('frequency',['montly','quantely','annually'])->nullable();
            $table->string('name',255);
            $table->string('email',255);
            $table->decimal('amount', 10, 2);
            $table->string('phone_number',100)->nullable();
            $table->text('remark')->nullable();
            $table->enum('payment_status', ['Paid', 'Pending','Failed'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transitions');
    }
};
