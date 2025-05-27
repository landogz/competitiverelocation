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
        Schema::create('service_rates', function (Blueprint $table) {
            $table->id();
            $table->string('service_type');
            $table->string('category')->nullable();
            $table->string('title')->nullable();
            $table->string('value_range')->nullable();
            $table->decimal('rate', 10, 2);
            $table->string('unit')->default('flat');
            $table->text('description')->nullable();
            $table->string('badge_color')->default('primary');
            $table->string('icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_rates');
    }
};
