<?php

namespace App\Http\Controllers;

use App\Models\TabelUmum;
use App\Models\TabelSub;
use App\Models\Cabang;
use App\Models\Tempat;
use App\DTOs\DetailTabel;
use App\DTOs\SubTabel;
use Illuminate\Http\Request;


class DetailController extends Controller
{
    public function show($id)
    {
        // Ambil data dari tabel utama
        $tabelUmum = TabelUmum::findOrFail($id);
        $cabang = Cabang::find($tabelUmum->cabang_id);
        $tempat = Tempat::find($tabelUmum->tempat_id);

        // Ambil data dari sub-tabel berdasarkan kategori
        $leads = TabelSub::where('tabel_umum_id', $id)->where('sub_kategori', 'leads')->get();
        $prospek = TabelSub::where('tabel_umum_id', $id)->where('sub_kategori', 'prospek')->get();
        $hotProspek = TabelSub::where('tabel_umum_id', $id)->where('sub_kategori', 'hot_prospek')->get();
        $spk = TabelSub::where('tabel_umum_id', $id)->where('sub_kategori', 'spk')->get();
        $spkDo = TabelSub::where('tabel_umum_id', $id)->where('sub_kategori', 'do')->get();

        // Konversi hasil query ke dalam DTO
        $detailTable = new DetailTabel(
            $tabelUmum->id,
            $cabang ? $cabang->id : '',
            $tempat ? $tempat->id : '',
            $tabelUmum->nama_tabel,
            $tempat ? $tempat->nama : '',
            $cabang ? $cabang->nama : '',
            $this->convertToSubTabelArray($leads),
            $this->convertToSubTabelArray($prospek),
            $this->convertToSubTabelArray($hotProspek),
            $this->convertToSubTabelArray($spk),
            $this->convertToSubTabelArray($spkDo)
        );

        // Dapatkan nama route saat ini
        $routeName = request()->route()->getName();

        // Tentukan tampilan berdasarkan route
        $viewMap = [
            'public.display' => 'publicDisplay',
            'customer.gathering' => 'customerGathering',
            'digital.marketing' => 'digitalMarketing',
            'grassroot' => 'grassroot'
        ];

        // Pilih view berdasarkan route, default ke 'publicDisplay' jika tidak ditemukan
        $viewName = $viewMap[$routeName] ?? 'publicDisplay';

        return view($viewName, compact('detailTable'));
    }
    public function searchTableUmum($cabangId, $tempatId)
    {
        $tabelUmum = TabelUmum::where('cabang_id', $cabangId)
            ->where('tempat_id', $tempatId)
            ->first();

        if ($tabelUmum) {
            return response()->json(['id' => $tabelUmum->id]);
        } else {
            return response()->json(['message' => '$cabangId, $tempatId'], 404);
        }
    }

    private function convertToSubTabelArray($data)
    {
        return $data->map(fn($item) => new SubTabel($item->id, $item->target, $item->act, $item->tabel_umum_id))->toArray();
    }


    public function submit($tabelUmumId, Request $request)
    {
        // return response()->json($request);
        // Validasi data dari request (ubah ke nullable)
        $validatedData = $request->validate([
            'data.*.sub_kategori' => 'nullable|in:leads,prospek,hot_prospek,spk,do',
            'data.*.target' => 'nullable|integer',
            'data.*.act' => 'nullable|integer',
        ]);

        foreach ($validatedData['data'] as $item) {
            // Lewati jika target dan act tidak diisi
            if (empty($item['target']) && empty($item['act'])) {
                continue;
            }

            $subTabel = new SubTabel(
                id: uniqid(),
                target: $item['target'] ?? null, // Jika kosong, set null
                act: $item['act'] ?? null,
                tableUmumId: $tabelUmumId
            );

            TabelSub::create([
                'tabel_umum_id' => $subTabel->tableUmumId,
                'sub_kategori' => $item['sub_kategori'] ?? null,
                'target' => $subTabel->target,
                'act' => $subTabel->act,
            ]);
        }

        return response()->json(['message' => 'Data berhasil disimpan'], 201);
    }
}
