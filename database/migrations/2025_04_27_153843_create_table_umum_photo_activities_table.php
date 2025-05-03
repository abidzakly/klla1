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
        Schema::create('table_umum_photo_activities', function (Blueprint $table) {
            $table->id();
            $table->enum('kategori', ['public_display','grassroots','digital_marketing','customer_gathering']);
            $table->unsignedBigInteger('cabang_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_umum_photo_activities');
    }
};
