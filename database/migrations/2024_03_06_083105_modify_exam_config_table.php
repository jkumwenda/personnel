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
        Schema::table('exam_configs', function (Blueprint $table) {
            // drop the venues column
            $table->dropColumn('venues');
            // add the northern_region column
            $table->string('northern_region')->nullable();
            // add the central_region column
            $table->string('central_region')->nullable();
            // add the southern_region column
            $table->string('southern_region')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_configs', function (Blueprint $table) {
            // drop the northern_region column
            $table->dropColumn('northern_region');
            // drop the central_region column
            $table->dropColumn('central_region');
            // drop the southern_region column
            $table->dropColumn('southern_region');
            // add the venues column
            $table->json('venues');
        });
    }
};
