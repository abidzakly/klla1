<?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration {
//     public function up()
//     {
//         Schema::create('cabang', function (Blueprint $table) {
//             $table->id();
//             $table->string('nama')->nullable();
//             $table->timestamps();
//         });

//         Schema::create('tempat', function (Blueprint $table) {
//             $table->id();
//             $table->string('nama');
//             $table->timestamps();
//         });

//         Schema::create('tabel_umum', function (Blueprint $table) {
//             $table->id();
//             $table->enum('kategori', ['public_display', 'grassroots', 'digital_marketing', 'customer_gathering']);
//             $table->foreignId('tempat_id')->nullable()->constrained('tempat')->nullOnDelete();
//             $table->foreignId('cabang_id')->nullable()->constrained('cabang')->nullOnDelete();
//             $table->timestamps();
//         });

//         Schema::create('tabel_sub', function (Blueprint $table) {
//             $table->id();
//             $table->foreignId('tabel_umum_id')->nullable()->constrained('tabel_umum')->cascadeOnDelete();
//             $table->enum('sub_kategori', ['leads', 'prospek', 'hot_prospek', 'spk', 'do']);
//             $table->integer('target');
//             $table->integer('act');
//             $table->timestamps();
//         });
//     }

//     public function down()
//     {
//         Schema::dropIfExists('tabel_sub');
//         Schema::dropIfExists('tabel_umum');
//         Schema::dropIfExists('tempat');
//         Schema::dropIfExists('cabang');
//     }
// };
