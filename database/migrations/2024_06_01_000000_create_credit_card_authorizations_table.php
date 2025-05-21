<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('credit_card_authorizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->string('full_name')->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('card_type')->nullable();
            $table->string('last_8_digits')->nullable();
            $table->string('cvc')->nullable();
            $table->string('expiration_date')->nullable();
            $table->string('cardholder_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('street_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('signature')->nullable();
            $table->date('date')->nullable();
            $table->string('comments')->nullable();
            $table->timestamps();
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('credit_card_authorizations');
    }
}; 