<?php

namespace App\Http\Controllers;

use App\DTOs\DetailTabel;
use App\DTOs\SubTabel;
use App\Helpers\Response;
use App\Models\Cabang;
use App\Models\PhotoActivity;
use App\Models\TableUmumPhotoActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PhotoActivityController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $tableUmum = TableUmumPhotoActivity::where('id', $request->id_photo_activity)->first();
        if (!$tableUmum) {
            return abort(404);
        }
        $cabang = Cabang::where('id', $tableUmum->cabang_id)->first();

        $photoActivities = PhotoActivity::where('tabel_umum_id', $tableUmum->id)
            ->whereDate('photo_activity_date', $date)
            ->count();

        $kategoriList = TableUmumPhotoActivity::select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->mapWithKeys(function($item) {
                return [$item => ucwords(str_replace(['_', '.'], [' ', ' '], $item))];
            });

        $detailTable = new DetailTabel(
            $tableUmum->id,
            $cabang ? $cabang->id : '',
            '', // Tidak ada tempat
            $tableUmum->kategori,
            '', // Tidak ada nama tempat
            $cabang ? $cabang->nama : '',
            [],
            [],
            [],
            [],
            []
        );

        $routeName = request()->route()->getName();
        $viewMap = [
            'public.display' => 'publicDisplay',
            'customer.gathering' => 'customerGathering',
            'digital.marketing' => 'digitalMarketing',
            'grassroot' => 'grassroot'
        ];
        $viewName = $viewMap[$routeName] ?? 'publicDisplay';

        return view('photo-activity.index', compact('detailTable', 'photoActivities', 'kategoriList'));
    }

    private function convertToSubTabelArray($data)
    {
        return $data->map(fn($item) => new SubTabel($item->id, $item->target, $item->act, $item->tabel_umum_id, $item->row_id))->toArray();
    }

    public function getData(Request $request)
    {
        $data = PhotoActivity::where('tabel_umum_id', $request->tabel_umum_id)
            ->where('photo_activity_date', Carbon::parse($request->date)->format('Y-m-d'));

        $listSearch = ['file_path'];
        $data = self::filterDatatable($data, $listSearch);

        $data = $data->get()->chunk(5);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('photos', function ($rows) {
                $html = '<div class="grid justify-center w-full grid-cols-1 gap-8 m-8 lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 xl:grid-cols-5">';
                foreach ($rows as $row) {
                    $html .= view('photo-activity.partials.photo', compact('row'))->render();
                }
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['photos'])
            ->make(true);
    }

    private static function filterDatatable($query, $searchColumns)
    {
        $searchValue = request('search.value');
        if ($searchValue) {
            $query->where(function ($query) use ($searchColumns, $searchValue) {
                foreach ($searchColumns as $column) {
                    $query->orWhere($column, 'like', '%' . $searchValue . '%');
                }
            });
        }
        return $query;
    }

    public function store(Request $request)
    {
        $request->validate([
            'tabel_umum_id' => 'required|exists:table_umum_photo_activities,id',
            'cabang_id' => 'required|exists:cabang,id',
            'photo_activity_file_name' => 'required|array',
            'photo_activity_file_name.*' => 'required|string|max:255',
            'photo_activity_name' => 'required|array',
            'photo_activity_name.*' => 'required|string|max:255',
            'photo_activity_location' => 'required|array',
            'photo_activity_location.*' => 'required|string|max:255',
            'photo_activity_caption' => 'required|array',
            'photo_activity_caption.*' => 'required|string|max:500',
            'photo_activity_date' => 'required|array',
            'photo_activity_date.*' => 'required|date',
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,svg|max:10240',
        ], [
            'tabel_umum_id.required' => 'ID tabel umum wajib diisi.',
            'tabel_umum_id.exists' => 'ID tabel umum tidak valid.',
            'photo_activity_type_id.required' => 'Jenis event wajib dipilih.',
            'photo_activity_type_id.ulid' => 'Format jenis event tidak valid.',
            'photo_activity_file_name.required' => 'Nama file wajib diisi.',
            'photo_activity_file_name.*.required' => 'Nama file wajib diisi.',
            'photo_activity_file_name.*.string' => 'Nama file harus berupa teks.',
            'photo_activity_file_name.*.max' => 'Nama file maksimal 255 karakter.',
            'photo_activity_name.required' => 'Nama event wajib diisi.',
            'photo_activity_name.*.required' => 'Nama event wajib diisi.',
            'photo_activity_name.*.string' => 'Nama event harus berupa teks.',
            'photo_activity_name.*.max' => 'Nama event maksimal 255 karakter.',
            'photo_activity_location.required' => 'Lokasi event wajib diisi.',
            'photo_activity_location.*.required' => 'Lokasi event wajib diisi.',
            'photo_activity_location.*.string' => 'Lokasi event harus berupa teks.',
            'photo_activity_location.*.max' => 'Lokasi event maksimal 255 karakter.',
            'photo_activity_caption.required' => 'Caption wajib diisi.',
            'photo_activity_caption.*.required' => 'Caption wajib diisi.',
            'photo_activity_caption.*.string' => 'Caption harus berupa teks.',
            'photo_activity_caption.*.max' => 'Caption maksimal 500 karakter.',
            'photo_activity_date.required' => 'Tanggal event wajib diisi.',
            'photo_activity_date.*.required' => 'Tanggal event wajib diisi.',
            'photo_activity_date.*.date' => 'Format tanggal tidak valid.',
            'files.required' => 'File wajib diunggah.',
            'files.*.required' => 'File wajib diunggah.',
            'files.*.file' => 'File harus berupa berkas.',
            'files.*.mimes' => 'File harus berupa jpg, jpeg, png, svg atau pdf.',
            'files.*.max' => 'Ukuran maksimal file adalah 10MB.',
        ]);

        DB::beginTransaction();

        try {
            // Ambil data berdasarkan id dan cabang_id dari request (bukan hanya != null)
            $tableUmum = TableUmumPhotoActivity::where('id', $request->tabel_umum_id)->first();                                
            $kategoriName = str_replace(' ', '-', $tableUmum->kategori);
            $branchName = str_replace(' ', '-', Cabang::where('id', $tableUmum->cabang_id)->first()->nama);
            foreach ($request->file('files') as $index => $file) {
                $fileNameOriginal = $request->photo_activity_file_name[$index];
                $ext = $file->getClientOriginalExtension();
                $fileName = $fileNameOriginal . '.' . $ext;
                $folderPath = "photo-activity/{$kategoriName}/{$branchName}/" . $request->photo_activity_date[$index];
                $newPath = "{$folderPath}/{$fileName}";

                $counter = 1;
                while (Storage::exists($newPath)) {
                    $fileName = "{$fileNameOriginal} ({$counter}).{$ext}";
                    $newPath = "{$folderPath}/{$fileName}";
                    $counter++;
                }

                $path = $file->storeAs($folderPath, $fileName);

                PhotoActivity::create([
                    'tabel_umum_id' => $request->tabel_umum_id,
                    'file_path' => $path,
                    'photo_activity_name' => $request->photo_activity_name[$index],
                    'photo_activity_location' => $request->photo_activity_location[$index],
                    'photo_activity_caption' => $request->photo_activity_caption[$index],
                    'photo_activity_date' => $request->photo_activity_date[$index],
                ]);
            }

            DB::commit();
            return Response::success(null, 'Upload berhasil');
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::errorCatch($e, 'Upload gagal');
        }
    }

    public function show($id_photo_activity)
    {
        $photoActivity = PhotoActivity::where('id_photo_activity', $id_photo_activity)->first();
        $data = $photoActivity->toArray();
        $data['file_url'] = $photoActivity->file_path ? asset('storage/' . $photoActivity->file_path) : '';
        $data['photo_activity_file_name'] = pathinfo($photoActivity->file_path, PATHINFO_FILENAME);
        $data['photo_activity_date'] = Carbon::parse($photoActivity->photo_activity_date)->format('Y-m-d');
        $data['photo_activity_date_text'] = Carbon::parse($photoActivity->photo_activity_date)->translatedFormat('l, d F Y');
        return response()->json(['data' => $data]);
    }

    public function update(Request $request, $id_photo_activity)
    {
        $photoActivity = PhotoActivity::where('id_photo_activity', $id_photo_activity)->first();
        if (!$photoActivity) {
            return Response::error(null, 'Data tidak ditemukan');
        }

        $rules = [
            'photo_activity_file_name' => 'required|string|max:255',
            'photo_activity_name' => 'required|string|max:255',
            'photo_activity_location' => 'required|string|max:255',
            'photo_activity_caption' => 'required|string|max:500',
            'photo_activity_date' => 'required|date',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,svg|max:10240',
        ];
        $messages = [
            'photo_activity_file_name.required' => 'Nama file wajib diisi.',
            'photo_activity_name.required' => 'Nama event wajib diisi.',
            'photo_activity_location.required' => 'Lokasi event wajib diisi.',
            'photo_activity_caption.required' => 'Caption wajib diisi.',
            'photo_activity_date.required' => 'Tanggal event wajib diisi.',
            'file.mimes' => 'File harus berupa jpg, jpeg, png, svg.',
            'file.max' => 'Ukuran maksimal file adalah 10MB.',
        ];
        $validated = $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $updateData = [
                'photo_activity_name' => $request->photo_activity_name,
                'photo_activity_location' => $request->photo_activity_location,
                'photo_activity_caption' => $request->photo_activity_caption,
                'photo_activity_date' => $request->photo_activity_date,
            ];

            $oldPath = $photoActivity->file_path;
            $ext = pathinfo($oldPath, PATHINFO_EXTENSION);
            $dir = dirname($oldPath);
            $newFileName = $request->photo_activity_file_name . '.' . $ext;
            $newPath = $dir . '/' . $newFileName;

            if ($request->photo_activity_file_name !== pathinfo($oldPath, PATHINFO_FILENAME)) {
                $counter = 1;
                while (Storage::exists($newPath)) {
                    $newFileName = $request->photo_activity_file_name . "($counter)." . $ext;
                    $newPath = $dir . '/' . $newFileName;
                    $counter++;
                }
                if (Storage::exists($oldPath)) {
                    Storage::move($oldPath, $newPath);
                }
                $updateData['file_path'] = $newPath;
            }

            if ($request->hasFile('file')) {
                if (Storage::exists($photoActivity->file_path)) {
                    Storage::delete($photoActivity->file_path);
                }
                $file = $request->file('file');
                $file->storeAs($dir, $newFileName);
                $updateData['file_path'] = $dir . '/' . $newFileName;
            }

            $photoActivity->update($updateData);

            DB::commit();
            return Response::success(null, 'Update berhasil');
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::errorCatch($e, 'Update gagal');
        }
    }

    public function rename(Request $request, $id_photo_activity)
    {
        $photoActivity = PhotoActivity::where('id_photo_activity', $id_photo_activity)->first();
        $request->validate([
            'file_name' => 'required|string',
        ]);

        $pathInfo = pathinfo($photoActivity->file_path);
        $extension = isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';

        $oldPath = $photoActivity->file_path;
        $dir = dirname($oldPath);
        $newFileName = $request->file_name . $extension;
        $newPath = $dir . '/' . $newFileName;      

        // Jika nama file berubah, cek duplikasi
        $counter = 1;
        while (Storage::exists($newPath)) {
            if ($newPath === $oldPath) break;
            $newFileName = $request->file_name . ' (' . $counter . ')' . $extension;
            $newPath = $dir . '/' . $newFileName;
            $counter++;
        }

        if (Storage::exists($oldPath)) {
            Storage::move($oldPath, $newPath);
        }

        $photoActivity->update([
            'file_path' => $newPath,
        ]);

        return Response::success(null, 'Rename berhasil');
    }

    public function destroy($photo_activity)
    {
        DB::beginTransaction();

        try {
            $photoActivity = PhotoActivity::findOrFail($photo_activity);
            $path = $photoActivity->file_path;

            if (Storage::exists($path)) {
                Storage::delete($path);
            }

            $photoActivity->delete();
            DB::commit();
            return Response::success(null, 'Foto berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::errorCatch($e, 'Failed to delete data.');
        }
    }
}
