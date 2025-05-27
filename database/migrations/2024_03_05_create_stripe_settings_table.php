<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stripe_settings', function (Blueprint $table) {
            $table->id();
            $table->string('public_key');
            $table->string('secret_key');
            $table->boolean('is_active')->default(false);
            $table->string('last_error')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stripe_settings');
    }
}; 