<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableUmumPhotoActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabangs = DB::table('cabang')->pluck('id')->toArray();
        $kategoriList = [
            'public_display',
            'grassroots',
            'digital_marketing',
            'customer_gathering'
        ];

        // Seeder untuk table_umum_photo_activities (tanpa tempat)
        $dataPhotoActivity = [];
        foreach ($cabangs as $cabang_id) {
            foreach ($kategoriList as $kategori) {
                $dataPhotoActivity[] = [
                    'kategori' => $kategori,
                    'cabang_id' => $cabang_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('table_umum_photo_activities')->insert($dataPhotoActivity);
    }
}
