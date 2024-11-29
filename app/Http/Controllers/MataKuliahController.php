<?php

namespace App\Http\Controllers;

use App\Models\MataKuliahModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MataKuliahController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Mata Kuliah',
            'list' => ['Home', 'Mata Kuliah']
        ];

        $page = (object)[
            'title' => 'Daftar mata kuliah yang terdaftar dalam sistem'
        ];

        $activeMenu = 'matakuliah'; //set menu yang sedang aktif

        return view('matakuliah.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    //Ambil data matakuliah dalam bentuk json untuk datables
    public function list()
    {
        $mataKuliahs = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah', 'kode_matakuliah');

        return DataTables::of($mataKuliahs)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addIndexColumn()
            ->addColumn('aksi', function ($mataKuliah) {
                // menambahkan kolom aksi 
                $btn = '<button onclick="modalAction(\'' . url('/matakuliah/' . $mataKuliah->id_matakuliah .
                    '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/matakuliah/' . $mataKuliah->id_matakuliah .
                    '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/matakuliah/' . $mataKuliah->id_matakuliah .
                    '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true);
    }

    public function create()
    {

        return view('matakuliah.create');
    }

    // Menyimpan data mata kuliah baru ajax
    public function store(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_matakuliah'   => 'required|string|min:2|unique:mata_kuliah,kode_matakuliah',
                'nama_matakuliah'   => 'required|string|max:50'
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,  // response status , false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            MataKuliahModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data mata kuliah berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    // Menampilkan detail mata kuliah
    public function show(String $id)
    {
        $mataKuliah = MataKuliahModel::find($id);

        return view('matakuliah.show', ['mataKuliah' => $mataKuliah]);
    }

    // Menampilkan halaman form edit mata kuliah
    public function edit(String $id)
    {
        $mataKuliah = MataKuliahModel::find($id);

        return view('matakuliah.edit', ['mataKuliah' => $mataKuliah]);
    }


    // Menyimpan perubahan data mata kuliah
    public function update(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_matakuliah'   => 'required|string|min:2',
                'nama_matakuliah'   => 'required|string|max:50'
            ];

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
            $check = MataKuliahModel::find($id);
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

    // Menampilkan hapus data mata kuliah ajax
    public function confirm(string $id) {
        $mataKuliah = MataKuliahModel::find($id);
        
        return view('matakuliah.confirm', ['mataKuliah' => $mataKuliah]);
    }

    // Menghapus data mata kuliah ajax
    public function delete(Request $request, $id) {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $mataKuliah = MataKuliahModel::find($id);
            if ($mataKuliah) {
                $mataKuliah->delete();
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
    return view('matakuliah.import');
}

public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            // Validasi file harus xls atau xlsx, max 1MB
            'file_mata_kuliah' => ['required', 'mimes:xlsx', 'max:1024']
        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }
        
        $file = $request->file('file_mata_kuliah'); // Ambil file dari request
        $reader = IOFactory::createReader('Xlsx'); // Load reader file Excel
        $reader->setReadDataOnly(true); // Hanya membaca data
        $spreadsheet = $reader->load($file->getRealPath()); // Load file Excel
        $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif
        $data = $sheet->toArray(null, false, true, true); // Ambil data Excel
        $insert = [];
        
        if (count($data) > 1) { // Jika data lebih dari 1 baris
            foreach ($data as $baris => $value) {
                if ($baris > 1) { // Baris ke-1 adalah header, maka lewati
                    $insert[] = [
                        'id_matakuliah' => $value['A'], 
                        'nama_matakuliah' => $value['B'], 
                        'kode_matakuliah' => $value['C'], 
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            if (count($insert) > 0) {
                // Insert data ke database, jika data sudah ada, maka diabaikan
                MataKuliahModel::insertOrIgnore($insert);
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Data mata kuliah berhasil diimport'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data mata kuliah yang diimport'
            ]);
        }
    }
    return redirect('/');
}

}
