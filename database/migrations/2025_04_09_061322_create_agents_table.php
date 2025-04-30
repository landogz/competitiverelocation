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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('unique_url')->nullable();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('contact_title');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->string('phone_number');
            $table->string('email');
            $table->string('company_website')->nullable();
            $table->decimal('corporate_sales', 15, 2)->default(0);
            $table->decimal('consumer_sales', 15, 2)->default(0);
            $table->decimal('local_sales', 15, 2)->default(0);
            $table->decimal('long_distance_sales', 15, 2)->default(0);
            $table->decimal('delivery_service_sales', 15, 2)->default(0);
            $table->decimal('total_sales', 15, 2)->default(0);
            $table->string('truck_size')->nullable();
            $table->text('truck_image')->nullable();
            $table->integer('num_trucks')->default(0);
            $table->integer('num_crews')->default(0);
            $table->string('affiliated_company')->nullable();
            $table->enum('local_moving_service', ['yes', 'no'])->default('no');
            $table->enum('delivery_service', ['yes', 'no'])->default('no');
            $table->enum('labor_services', ['yes', 'no'])->default('no');
            $table->enum('commercial_moving', ['yes', 'no'])->default('no');
            $table->text('carrierInterestReason')->nullable();
            $table->timestamp('external_created_at')->nullable();
            $table->string('status')->default('approved');
            $table->string('randomcodes')->nullable();
            $table->enum('booking_agent', ['yes', 'no'])->default('no');
            $table->enum('general_freight', ['yes', 'no'])->default('no');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
