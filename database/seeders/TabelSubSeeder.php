<?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;

// class TabelSubSeeder extends Seeder
// {
//     public function run()
//     {
//         $tabelUmumIds = DB::table('tabel_umum')->pluck('id')->toArray();
//         $subKategoriList = ['leads', 'prospek', 'hot_prospek', 'spk', 'do'];

//         $data = [];
//         foreach ($tabelUmumIds as $tabelUmumId) {
//             foreach ($subKategoriList as $subKategori) {
//                 $data[] = [
//                     'tabel_umum_id' => $tabelUmumId,
//                     'sub_kategori' => $subKategori,
//                     'target' => rand(10, 100), // Target random antara 10-100
//                     'act' => rand(5, 90), // Act random antara 5-90
//                     'created_at' => now(),
//                     'updated_at' => now(),
//                 ];
//             }
//         }

//         DB::table('tabel_sub')->insert($data);
//     }
// }
