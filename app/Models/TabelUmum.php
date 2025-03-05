<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class TabelUmum extends Model
{
    use HasFactory;
    protected $table = 'tabel_umum';
    protected $fillable = ['kategori', 'tempat_id', 'cabang_id'];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function tempat()
    {
        return $this->belongsTo(Tempat::class, 'tempat_id');
    }

    public function sub()
    {
        return $this->hasMany(TabelSub::class, 'tabel_umum_id');
    }
}
