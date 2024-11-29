<?php

namespace App\Http\Controllers;

use App\Models\JenisPelatihanModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class JenisPelatihanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Jenis Pelatihan',
            'list' => ['Home', 'Jenis Pelatihan']
        ];

        $page = (object)[
            'title' => 'Daftar jenis pelatihan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'jenispelatihan';
        $jenis_pelatihan = JenisPelatihanModel::all();

        return view('jenispelatihan.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu,
            'jenis_pelatihan' => $jenis_pelatihan  // Pass the variable to the view
        ]);
    }

    // Fetch data for DataTables (AJAX)
    public function list()
    {
        $jenisPelatihan = JenisPelatihanModel::select('id_jenis_pelatihan', 'nama_jenis_pelatihan', 'kode_pelatihan');

        return DataTables::of($jenisPelatihan)
            ->addIndexColumn()
            ->addColumn('aksi', function ($jenis) {
                $btn = '<button onclick="modalAction(\'' . url('/jenispelatihan/' . $jenis->id_jenis_pelatihan . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenispelatihan/' . $jenis->id_jenis_pelatihan . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/jenispelatihan/' . $jenis->id_jenis_pelatihan . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('jenispelatihan.create');
    }

    // Store new jenis pelatihan
    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_pelatihan'   => 'required|string|min:3|unique:jenis_pelatihan,kode_pelatihan',
                'nama_jenis_pelatihan' => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            JenisPelatihanModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data jenis pelatihan berhasil disimpan'
            ]);
        }
        return redirect('/');
    }

    public function show(String $id)
    {
        $jenisPelatihan = JenisPelatihanModel::find($id);

        return view('jenispelatihan.show', ['jenisPelatihan' => $jenisPelatihan]);
    }

    public function edit(String $id)
    {
        $jenisPelatihan = JenisPelatihanModel::find($id);

        return view('jenispelatihan.edit', ['jenisPelatihan' => $jenisPelatihan]);
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kode_pelatihan'   => 'required|string|min:3',
                'nama_jenis_pelatihan' => 'required|string|max:100'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $jenisPelatihan = JenisPelatihanModel::find($id);
            if ($jenisPelatihan) {
                $jenisPelatihan->update($request->all());
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
        $jenisPelatihan = JenisPelatihanModel::find($id);

        return view('jenispelatihan.confirm', ['jenisPelatihan' => $jenisPelatihan]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $jenisPelatihan = JenisPelatihanModel::find($id);
            if ($jenisPelatihan) {
                $jenisPelatihan->delete();
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

    public function export_pdf()
{
    // Mengambil data jenis pelatihan
    $jenisPelatihan = JenisPelatihanModel::select('kode_pelatihan', 'nama_jenis_pelatihan')
        ->orderBy('kode_pelatihan')
        ->get();

    // Generate PDF menggunakan view `jenis_pelatihan.export_pdf`
    $pdf = Pdf::loadView('jenispelatihan.export_pdf', ['jenisPelatihan' => $jenisPelatihan]);
    $pdf->setPaper('a4', 'portrait'); // Set ukuran kertas dan orientasi
    $pdf->setOption("isRemoteEnabled", true); // Set true jika ada gambar dari URL

    // Menghasilkan file PDF
    return $pdf->stream('Data_Jenis_Pelatihan_' . date('Y-m-d_H-i-s') . '.pdf');
}


public function import()
{
    return view('jenispelatihan.import');
}

public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            // validasi file harus xls atau xlsx, max 1MB
            'file_jenis_pelatihan' => ['required', 'mimes:xlsx', 'max:1024']
        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }
        
        $file = $request->file('file_jenis_pelatihan'); // ambil file dari request
        $reader = IOFactory::createReader('Xlsx'); // load reader file excel
        $reader->setReadDataOnly(true); // hanya membaca data
        $spreadsheet = $reader->load($file->getRealPath()); // load file excel
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
        $data = $sheet->toArray(null, false, true, true); // ambil data excel
        $insert = [];
        
        if (count($data) > 1) { // jika data lebih dari 1 baris
            foreach ($data as $baris => $value) {
                if ($baris > 1) { // baris ke 1 adalah header, maka lewati
                    $insert[] = [
                        'id_jenis_pelatihan' => $value['A'],
                        'nama_jenis_pelatihan' => $value['B'],
                        'kode_pelatihan' => $value['C'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            if (count($insert) > 0) {
                // insert data ke database, jika data sudah ada, maka diabaikan
                JenisPelatihanModel::insertOrIgnore($insert);
            }
            
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diimport'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }
    }
    return redirect('/');
}




}
