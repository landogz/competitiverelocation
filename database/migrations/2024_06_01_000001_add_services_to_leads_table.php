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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('sales_name')->nullable();
            $table->string('sales_email')->nullable();
            $table->string('sales_location')->nullable();
            $table->string('pickup_location')->nullable();
            $table->string('delivery_location')->nullable();
            $table->decimal('miles', 10, 2)->default(0);
            $table->dropColumn('service');
            $table->json('service')->nullable();
            $table->decimal('service_rate', 10, 2)->default(0);
            $table->integer('no_of_items')->default(0);
            $table->integer('no_of_crew')->default(0);
            $table->decimal('crew_rate', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('software_fee', 10, 2)->default(0);
            $table->decimal('truck_fee', 10, 2)->default(0);
            $table->decimal('downpayment', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->string('uploaded_image')->nullable();
            $table->timestamp('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'sales_name',
                'sales_email',
                'sales_location',
                'pickup_location',
                'delivery_location',
                'miles',
                'service_rate',
                'no_of_items',
                'no_of_crew',
                'crew_rate',
                'subtotal',
                'software_fee',
                'truck_fee',
                'downpayment',
                'grand_total',
                'uploaded_image',
                'date'
            ]);
            $table->string('service')->nullable();
        });
    }
}; 