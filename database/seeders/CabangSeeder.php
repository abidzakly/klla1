<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('cabang')->insert([
            ['id' => 1, 'nama' => 'Bone', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'Soppeng', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'Sengkang', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'Kendari', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'Tandean', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama' => 'Kendari 3', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama' => 'Kolaka', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nama' => 'Bau Bau', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'nama' => 'Palu Metadinata', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'nama' => 'Palu Juanda', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'nama' => 'Poso', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'nama' => 'Luwuk Benggal', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
