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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('lead_source')->nullable()->after('phone');
            $table->enum('lead_type', ['local', 'long_distance'])->nullable()->after('lead_source');
            $table->string('assigned_agent')->nullable()->after('lead_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['lead_source', 'lead_type', 'assigned_agent']);
        });
    }
};
