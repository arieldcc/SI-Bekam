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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->foreignId('therapist_id')->nullable()->constrained('therapists')->onDelete('set null');
            $table->text('complaints');
            $table->string('therapy_area');
            $table->float('weight')->nullable();
            $table->string('blood_pressure')->nullable(); // ex: 120/80
            $table->integer('pulse')->nullable();         // optional
            $table->text('result_notes')->nullable();
            $table->dateTime('actual_start_time')->nullable();
            $table->dateTime('actual_end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
