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
        Schema::table('users', function (Blueprint $table) {
            // Split name into first_name and last_name
            $table->renameColumn('name', 'first_name');
            $table->string('last_name')->after('name')->nullable();
            
            // Add new profile fields
            $table->string('profile_photo')->nullable()->after('remember_token');
            $table->text('bio')->nullable()->after('profile_photo');
            $table->string('phone', 20)->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('city', 100)->nullable()->change();
            $table->string('state', 100)->nullable()->change();
            $table->string('zip_code', 20)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert name columns
            $table->renameColumn('first_name', 'name');
            $table->dropColumn('last_name');
            
            // Remove new profile fields
            $table->dropColumn([
                'profile_photo',
                'bio'
            ]);
            
            // Revert nullable fields
            $table->string('phone', 20)->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('city', 100)->nullable(false)->change();
            $table->string('state', 100)->nullable(false)->change();
            $table->string('zip_code', 20)->nullable(false)->change();
        });
    }
};
