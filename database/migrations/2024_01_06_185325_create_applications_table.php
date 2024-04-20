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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->string('application_id')->unique();
            $table->foreignId('personnel_category_id')->constrained();
            $table->string('training');
            $table->string('present_employer')->nullable();
            $table->text('present_employer_address')->nullable();
            $table->json('academic_qualification')->nullable();
            $table->json('professional_qualification')->nullable();
            $table->json('relevant_files')->nullable();
            $table->string('application_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
