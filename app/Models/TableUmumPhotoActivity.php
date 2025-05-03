<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableUmumPhotoActivity extends Model
{
    protected $table = 'table_umum_photo_activities';
    protected $fillable = ['kategori', 'cabang_id'];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }
}
