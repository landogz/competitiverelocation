<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamps();
            
            // Ensure unique combination of transaction and inventory item
            $table->unique(['transaction_id', 'inventory_item_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_inventory_items');
    }
}; 