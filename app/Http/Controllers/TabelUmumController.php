<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TabelUmum;

class TabelUmumController extends Controller
{
    public function index()
    {
        $publicDisplay = \App\Models\TabelUmum::where('kategori', 'public_display')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($publicDisplay) {
            $detailTable = \App\Models\TabelSub::where('tabel_umum_id', $publicDisplay->id)
                ->get();

            return view('publicDisplay', compact('publicDisplay', 'detailTable'));

        }
    }

}

