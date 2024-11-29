<?php

namespace App\Http\Controllers;

use App\Models\VendorPelatihanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class VendorPelatihanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Vendor Pelatihan',
            'list' => ['Home', 'Vendor Pelatihan']
        ];

        $page = (object)[
            'title' => 'Daftar vendor pelatihan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'vendorpelatihan'; //set menu yang sedang aktif

        return view('vendorpelatihan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    //Ambil data vendorpelatihan dalam bentuk json untuk datables
    public function list()
    {
        $vendorpelatihans = VendorPelatihanModel::select('id_vendor_pelatihan', 'nama', 'alamat', 'kota', 'no_telp', 'alamat_web');

        return DataTables::of($vendorpelatihans)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addIndexColumn()
            ->addColumn('aksi', function ($vendorpelatihan) {
                // menambahkan kolom aksi 
                $btn = '<button onclick="modalAction(\'' . url('/vendorpelatihan/' . $vendorpelatihan->id_vendor_pelatihan .
                    '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/vendorpelatihan/' . $vendorpelatihan->id_vendor_pelatihan .
                    '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/vendorpelatihan/' . $vendorpelatihan->id_vendor_pelatihan .
                    '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true);
    }

    public function create()
    {

        return view('vendorpelatihan.create');
    }

    // Menyimpan data vendor pelatihan baru ajax
    public function store(Request $request)
    {
        // cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama'   => 'required|string|max:100|unique:vendor_pelatihan,nama',
                'alamat'   => 'required|string|max:100',
                'kota'   => 'required|string|max:100',
                'no_telp'   => 'required|string|max:20',
                'alamat_web'   => 'required|string|max:255'
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

            VendorPelatihanModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data vendor pelatihan berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    // Menampilkan detail vendor pelatihan
    public function show(String $id)
    {
        $vendorpelatihan = VendorPelatihanModel::find($id);

        return view('vendorpelatihan.show', ['vendorpelatihan' => $vendorpelatihan]);
    }

    // Menampilkan halaman form edit vendor pelatihan
    public function edit(String $id)
    {
        $vendorpelatihan = VendorPelatihanModel::find($id);

        return view('vendorpelatihan.edit', ['vendorpelatihan' => $vendorpelatihan]);
    }


    // Menyimpan perubahan data vendor pelatihan
    public function update(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama'   => 'required|string|max:100',
                'alamat'   => 'required|string|max:100',
                'kota'   => 'required|string|max:100',
                'no_telp'   => 'required|string|max:20',
                'alamat_web'   => 'required|string|max:255'
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
            $check = VendorPelatihanModel::find($id);
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

    // Menampilkan hapus data vendor pelatihan ajax
    public function confirm(string $id) {
        $vendorpelatihan = VendorPelatihanModel::find($id);
        
        return view('vendorpelatihan.confirm', ['vendorpelatihan' => $vendorpelatihan]);
    }

    // Menghapus data vendor pelatihan ajax
    public function delete(Request $request, $id) {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $vendorpelatihan = VendorPelatihanModel::find($id);
            if ($vendorpelatihan) {
                $vendorpelatihan->delete();
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
        return view('vendorpelatihan.import');
    }

    public function import_ajax(Request $request)
{
    // Memastikan request adalah AJAX atau JSON
    if ($request->ajax() || $request->wantsJson()) {
        // Validasi file yang diupload
        $rules = [
            'file_vendor_pelatihan' => ['required', 'mimes:xlsx', 'max:1024'] // Validasi file .xlsx
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
        $file = $request->file('file_vendor_pelatihan');
        
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
                        'id_vendor_pelatihan' => $value['A'], 
                        'nama' => $value['B'], 
                        'alamat' => $value['C'], 
                        'kota' => $value['D'], 
                        'no_telp' => $value['E'], 
                        'alamat_web' => $value['F'], 
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Menyimpan data ke database, jika ada data yang diinsert
            if (count($insert) > 0) {
                VendorpelatihanModel::insertOrIgnore($insert); 
            }

            // Response jika berhasil
            return response()->json([
                'status' => true,
                'message' => 'Data vendor pelatihan berhasil diimport'
            ]);
        } else {
            // Jika tidak ada data yang diimport
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data vendor pelatihan yang diimport'
            ]);
        }
    }

    // Jika bukan request AJAX atau JSON
    return redirect('/');
}
}
