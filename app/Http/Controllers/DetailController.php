<?php

namespace App\Http\Controllers;

use App\Models\TabelUmum;
use App\Models\TabelSub;
use App\Models\Cabang;
use App\Models\Tempat;
use App\DTOs\DetailTabel;
use App\DTOs\SubTabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DetailController extends Controller
{
    public function show($id, Request $request)
    {
        // Ambil data dari tabel utama
        $tabelUmum = TabelUmum::findOrFail($id);
        $cabang = Cabang::find($tabelUmum->cabang_id);
        $tempat = Tempat::find($tabelUmum->tempat_id);

        $date = $request->query('date', now()->toDateString());
        // Ambil data dari sub-tabel berdasarkan kategori
        $leads = TabelSub::where('tabel_umum_id', $id)->where('sub_kategori', 'leads')->whereDate('date', $date)->get();
        $prospek = TabelSub::where('tabel_umum_id', $id)->where('sub_kategori', 'prospek')->whereDate('date', $date)->get();
        $hotProspek = TabelSub::where('tabel_umum_id', $id)->where('sub_kategori', 'hot_prospek')->whereDate('date', $date)->get();
        $spk = TabelSub::where('tabel_umum_id', $id)->where('sub_kategori', 'spk')->whereDate('date', $date)->get();
        $spkDo = TabelSub::where('tabel_umum_id', $id)->where('sub_kategori', 'do')->whereDate('date', $date)->get();

        // Konversi hasil query ke dalam DTO
        $detailTable = new DetailTabel(
            $tabelUmum->id,
            $cabang ? $cabang->id : '',
            $tempat ? $tempat->id : '',
            $tabelUmum->kategori,
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

        return view('monitoring-do-&-spk', compact('detailTable'));
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
        // Validasi data dari request (ubah ke nullable)
        $validatedData = $request->validate([
            'data.*.id' => 'nullable|exists:tabel_sub,id',
            'data.*.sub_kategori' => 'nullable|in:leads,prospek,hot_prospek,spk,do',
            'data.*.target' => 'nullable|integer',
            'data.*.act' => 'nullable|integer',
            'data.*.date' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            if (isset($validatedData['data'])) {
                $newRow = collect($validatedData['data'])?->where('id', '=', null);
                $isFill = $newRow?->filter(function ($item) {
                    return !empty($item['target']) || !empty($item['act']);
                })->count();

                if(!$isFill){
                    return response()->json(['message' => 'Data tidak boleh kosong'], 400);
                }

                $rowId = Str::ulid();
                foreach ($validatedData['data'] as $item) {

                    // Lewati jika target dan act tidak diisi
                    if (empty($item['target']) && empty($item['act']) && !((boolean) $isFill)) {
                        continue;
                    }

                    if (isset($item['id'])) {
                        // Update existing record
                        $tabelSub = TabelSub::find($item['id']);
                        $tabelSub->update([
                            'sub_kategori' => $item['sub_kategori'] ?? $tabelSub->sub_kategori,
                            'target' => $item['target'] ?? $tabelSub->target,
                            'act' => $item['act'] ?? $tabelSub->act,
                        ]);
                    } else {
                        // Create new record
                        TabelSub::create([
                            'tabel_umum_id' => $tabelUmumId,
                            'sub_kategori' => $item['sub_kategori'] ?? null,
                            'row_id' => $rowId,
                            'target' => $item['target'] ?? null,
                            'act' => $item['act'] ?? null,
                            'date' => $item['date'] ?? now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['message' => 'Data berhasil disimpan'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data', 'error' => $e->getMessage()], 500);
        }
    }

    public function getData($tabelUmumId)
    {
        $tabelUmum = TabelUmum::findOrFail($tabelUmumId);
        $tabelUmumKategori = TabelUmum::where('kategori', $tabelUmum->kategori)->get();
        // get cabang and tempat usin tabelUmum
        $idsCabang = $tabelUmumKategori->pluck('cabang_id')->unique()->toArray();
        $idsTempat = $tabelUmumKategori->pluck('tempat_id')->unique()->toArray();

        $cabang = Cabang::whereIn('id', $idsCabang)->get()->mapWithKeys(fn($item) => [$item->nama => ['id' => $item->id, 'nama' => $item->nama]]);
        $tempat = Tempat::whereIn('id', $idsTempat)->get()->mapWithKeys(fn($item) => [$item->nama => ['id' => $item->id, 'nama' => $item->nama]]);

        return response()->json([
            'cabang' => $cabang,
            'tempat' => $tempat
        ]);
    }
}
