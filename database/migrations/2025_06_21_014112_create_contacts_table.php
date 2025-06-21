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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->nullable();        // Nomor telepon
            $table->string('email')->nullable();        // Alamat email
            $table->string('address')->nullable();      // Alamat fisik
            $table->string('whatsapp_link')->nullable(); // Link ke WA jika ada
            $table->text('map_embed')->nullable();      // Embed iframe Google Maps
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
