<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class TempatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('tempat')->insert([
            ['id' => 1, 'nama' => 'Mall', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama' => 'Swalayan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama' => 'Pasar', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama' => 'Festival', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama' => 'Showroom Event', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama' => '...event', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama' => 'Kantoran', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nama' => 'Perumahan', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'nama' => 'Cafe to Cafe', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'nama' => 'Instagram', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'nama' => 'Tiktok', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'nama' => 'Facebook', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
