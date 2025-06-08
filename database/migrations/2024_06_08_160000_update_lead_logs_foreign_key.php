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
        Schema::table('lead_logs', function (Blueprint $table) {
            // First drop the existing foreign key constraint
            $table->dropForeign(['lead_id']);
            
            // Then add the new foreign key constraint pointing to transactions
            $table->foreign('lead_id')
                  ->references('id')
                  ->on('transactions')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_logs', function (Blueprint $table) {
            // Drop the transactions foreign key
            $table->dropForeign(['lead_id']);
            
            // Restore the original leads foreign key
            $table->foreign('lead_id')
                  ->references('id')
                  ->on('leads')
                  ->onDelete('cascade');
        });
    }
}; 