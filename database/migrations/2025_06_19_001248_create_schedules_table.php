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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('therapist_id')->constrained('therapists')->onDelete('cascade');
            // $table->enum('type', ['weekly', 'date'])->default('weekly');

            // Untuk jadwal mingguan
            // $table->enum('day_of_week', ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'])->nullable();

            // Untuk override pada tanggal tertentu
            // $table->date('date')->nullable();

            // $table->time('start_time');
            // $table->time('end_time');

            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');

            $table->text('description')->nullable(); // penjelasan "Libur Idul Fitri", "Sesi ke-2", dll

            $table->enum('status', ['tersedia', 'penuh', 'libur'])->default('tersedia');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
