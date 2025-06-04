<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sms_consents', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->string('ip_address');
            $table->text('user_agent');
            $table->text('consent_text');
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_consents');
    }
}; 