<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales_reps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('office');
            $table->string('email')->unique();
            $table->string('phone');
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_reps');
    }
}; 