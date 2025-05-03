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
        Schema::create('photo_activities', function (Blueprint $table) {
            $table->uuid('id_photo_activity')->primary();
            $table->unsignedBigInteger('tabel_umum_id'); // Sekarang mengacu ke table_umum_photo_activities
            $table->string('photo_activity_name');
            $table->string('photo_activity_location');
            $table->string('photo_activity_caption')->nullable();
            $table->date('photo_activity_date');
            $table->string('file_path')->index();
            $table->timestamps();

            $table->foreign('tabel_umum_id')
                ->references('id')
                ->on('table_umum_photo_activities')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_activities');
    }
};
