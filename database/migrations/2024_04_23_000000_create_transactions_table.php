<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('sales_name')->nullable();
            $table->string('sales_email')->nullable();
            $table->string('sales_location')->nullable();
            $table->dateTime('date')->nullable();
            $table->text('pickup_location')->nullable();
            $table->text('delivery_location')->nullable();
            $table->decimal('miles', 10, 2)->default(0);
            $table->decimal('add_mile', 10, 2)->default(0);
            $table->decimal('mile_rate', 10, 2)->default(0);
            $table->string('service')->nullable();
            $table->decimal('service_rate', 10, 2)->default(0);
            $table->integer('no_of_items')->default(0);
            $table->integer('no_of_crew')->default(0);
            $table->decimal('crew_rate', 10, 2)->default(0);
            $table->decimal('delivery_rate', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('software_fee', 10, 2)->default(0);
            $table->decimal('truck_fee', 10, 2)->default(0);
            $table->decimal('downpayment', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('uploaded_image')->nullable();
            $table->json('services')->nullable();
            $table->string('status')->default('pending');
            $table->string('insurance_number')->nullable();
            $table->string('insurance_document')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}; 