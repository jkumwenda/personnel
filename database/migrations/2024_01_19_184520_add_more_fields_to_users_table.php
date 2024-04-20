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
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone_number')->nullable()->after('last_name');
            $table->foreignId('personnel_category_id')->nullable()->after('phone_number')->constrained('personnel_categories');
            $table->string('postal_address')->nullable()->after('personnel_category_id');
            $table->string('physical_address')->nullable()->after('postal_address');
            $table->string('national_id')->nullable()->after('physical_address');
            $table->string('national_file')->nullable()->after('national_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
