<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoActivity extends Model
{
    use HasFactory, HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $primaryKey = 'id_photo_activity';
    protected $table = 'photo_activities';

    protected $guarded = [

    ];

    protected $appends = ['file_name'];

    public function tabelUmum()
    {
        return $this->belongsTo(\App\Models\TabelUmum::class, 'tabel_umum_id', 'id');
    }

    public function getFileNameAttribute()
    {
        return pathinfo($this->file_path, PATHINFO_FILENAME);
    }

    public function getImage()
    {
        return asset('storage/' . $this->file_path);
    }

    public function getFileName()
    {
        return pathinfo($this->file_path, PATHINFO_FILENAME);
    }

}
