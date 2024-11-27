<?php

namespace App\Http\Controllers;

use App\Models\JenisSertifikasiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class JenisSertifikasiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Jenis Sertifikasi',
            'list' => ['Home', 'Jenis Sertifikasi']
        ];

        $page = (object)[
            'title' => 'Daftar jenis sertifikasi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'jenissertifikasi';

        return view('jenissertifikasi.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    // Ambil data jenis sertifikasi dalam bentuk json untuk datatables
    public function list()
    {
        $jenisSertifikasi = JenisSertifikasiModel::select('id_jenis_sertifikasi', 'nama_jenis_sertifikasi', 'kode_jenis_sertifikasi');

        return DataTables::of($jenisSertifikasi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jenis) {
                $btn = '<button onclick="modalAction(\'' . url('/jenissertifikasi/' . $jenis->id_jenis_sertifikasi . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenissertifikasi/' . $jenis->id_jenis_sertifikasi . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenissertifikasi/' . $jenis->id_jenis_sertifikasi . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('jenissertifikasi.create');
    }

    // Menyimpan data jenis sertifikasi baru
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_jenis_sertifikasi'   => 'required|string|min:3|unique:jenis_sertifikasi,kode_jenis_sertifikasi',
                'nama_jenis_sertifikasi'   => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            JenisSertifikasiModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data jenis sertifikasi berhasil disimpan'
            ]);
        }
        return redirect('/');
    }

    public function show(String $id)
    {
        $jenisSertifikasi = JenisSertifikasiModel::find($id);

        return view('jenissertifikasi.show', ['jenisSertifikasi' => $jenisSertifikasi]);
    }

    public function edit(String $id)
    {
        $jenisSertifikasi = JenisSertifikasiModel::find($id);

        return view('jenissertifikasi.edit', ['jenisSertifikasi' => $jenisSertifikasi]);
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_jenis_sertifikasi'   => 'required|string|min:3',
                'nama_jenis_sertifikasi'   => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $check = JenisSertifikasiModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm(string $id)
    {
        $jenisSertifikasi = JenisSertifikasiModel::find($id);

        return view('jenissertifikasi.confirm', ['jenisSertifikasi' => $jenisSertifikasi]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $jenisSertifikasi = JenisSertifikasiModel::find($id);
            if ($jenisSertifikasi) {
                $jenisSertifikasi->delete();
                return response()->json([
                    'status'    => true,
                    'message'   => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function import()
    {
        return view('jenissertifikasi.import');
    }

    public function import_ajax(Request $request)
{
    // Memastikan request adalah AJAX atau JSON
    if ($request->ajax() || $request->wantsJson()) {
        // Validasi file yang diupload
        $rules = [
            'file_jenis_sertifikasi' => ['required', 'mimes:xlsx', 'max:1024'] // Validasi file .xlsx
        ];

        // Melakukan validasi terhadap request
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        // Mengambil file dari request
        $file = $request->file('file_jenis_sertifikasi');
        
        // Membaca file excel dengan PHPSpreadsheet
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, false, true, true);

        // Menyiapkan data untuk disimpan
        $insert = [];

        // Memeriksa apakah ada data dalam file selain header
        if (count($data) > 1) {
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    // Menyiapkan data untuk dimasukkan
                    $insert[] = [
                        'id_jenis_sertifikasi' => $value['A'], // Kolom A untuk id_jenis_sertifikasi
                        'nama_jenis_sertifikasi' => $value['B'], // Kolom B untuk nama_jenis_sertifikasi
                        'kode_jenis_sertifikasi' => $value['C'], // Kolom C untuk kode_jenis_sertifikasi
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Menyimpan data ke database, jika ada data yang diinsert
            if (count($insert) > 0) {
                JenisSertifikasiModel::insertOrIgnore($insert); // Insert data ke tabel Jenis Sertifikasi
            }

            // Response jika berhasil
            return response()->json([
                'status' => true,
                'message' => 'Data jenis sertifikasi berhasil diimport'
            ]);
        } else {
            // Jika tidak ada data yang diimport
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data jenis sertifikasi yang diimport'
            ]);
        }
    }

    // Jika bukan request AJAX atau JSON
    return redirect('/');
}


}
