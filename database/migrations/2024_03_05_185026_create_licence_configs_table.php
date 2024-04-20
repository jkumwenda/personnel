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
        Schema::create('licence_configs', function (Blueprint $table) {
            $table->id();
            $table->string('INS');
            $table->string('DG_NAME');
            $table->string('BC_NAME');
            $table->string('DG_SIGNATURE');
            $table->string('BC_SIGNATURE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licence_configs');
    }
};
