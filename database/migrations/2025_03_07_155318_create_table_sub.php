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
        Schema::create('tabel_sub', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tabel_umum_id')->nullable()->constrained('tabel_umum')->cascadeOnDelete();
            $table->ulid('row_id');
            $table->enum('sub_kategori', ['leads', 'prospek', 'hot_prospek', 'spk', 'do']);
            $table->integer(column: 'target')->nullable();
            $table->integer('act')->nullable();
            $table->timestamp('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_sub');
    }
};
