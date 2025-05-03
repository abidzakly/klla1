<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TabelUmumSeeder extends Seeder
{
    public function run()
    {
        $cabangs = DB::table('cabang')->pluck('id')->toArray();
        $tempatsByKategori = [
            'public_display' => DB::table('tempat')->whereIn('nama', ['Mall', 'Swalayan', 'Pasar'])->pluck('id')->toArray(),
            'grassroots' => DB::table('tempat')->whereIn('nama', ['Kantoran', 'Perumahan', 'Cafe to Cafe'])->pluck('id')->toArray(),
            'digital_marketing' => DB::table('tempat')->whereIn('nama', ['Instagram', 'Tiktok', 'Facebook'])->pluck('id')->toArray(),
            'customer_gathering' => DB::table('tempat')->whereIn('nama', ['Festival', 'Showroom Event', '...event'])->pluck('id')->toArray(),
        ];

        $data = [];
        foreach ($cabangs as $cabang_id) {
            foreach ($tempatsByKategori as $kategori => $tempat_ids) {
                foreach ($tempat_ids as $tempat_id) {
                    $data[] = [
                        'kategori' => $kategori,
                        'cabang_id' => $cabang_id,
                        'tempat_id' => $tempat_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        DB::table('tabel_umum')->insert($data);

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
