<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Tempat extends Model
{
    use HasFactory;
    protected $table = 'tempat';
    protected $fillable = ['nama'];
}
