<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class TabelSub extends Model
{
    use HasFactory;
    protected $table = 'tabel_sub';
    protected $fillable = ['tabel_umum_id', 'sub_kategori', 'target', 'act'];

    public function tabelUmum()
    {
        return $this->belongsTo(TabelUmum::class, 'tabel_umum_id');
    }
}
