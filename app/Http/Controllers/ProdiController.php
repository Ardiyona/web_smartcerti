<?php

namespace App\Http\Controllers;

use App\Models\ProdiModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProdiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Program Studi',
            'list' => ['Home', 'Prodi']
        ];

        $page = (object)[
            'title' => 'Daftar program studi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'prodi';

        $prodi = ProdiModel::all();

        return view('prodi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'prodi' => $prodi,
        ]);
    }

    public function list()
    {
        $prodi = ProdiModel::select('id_prodi', 'nama_prodi', 'kode_prodi');

        return DataTables::of($prodi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($prodi) {
                $btn = '<button onclick="modalAction(\'' . url('/prodi/' . $prodi->id_prodi . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                // $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $prodi->id_prodi . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $prodi->id_prodi . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('prodi.create');
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_prodi' => 'required|string|max:100|',
                'kode_prodi' => 'required|string|max:100|min:2|unique:prodi'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            ProdiModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data program studi berhasil disimpan'
            ]);
        }
        return redirect('/');
    }


    

    public function show(String $id)
    {
        $prodi = ProdiModel::find($id);

        return view('prodi.show', ['prodi' => $prodi]);
    }

    public function edit(String $id)
    {
        $prodi = ProdiModel::find($id);

        return view('prodi.edit', ['prodi' => $prodi]);
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama_prodi' => 'required|string|max:255',
                'kode_prodi' => 'required|string|max:255'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $prodi = ProdiModel::find($id);
            if ($prodi) {
                $prodi->update($request->all());
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
        $prodi = ProdiModel::find($id);

        return view('prodi.confirm', ['prodi' => $prodi]);
    }

    // public function delete(Request $request, $id)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $prodi = ProdiModel::find($id);
    //         if ($prodi) {
    //             $prodi->delete();
    //             return response()->json([
    //                 'status'    => true,
    //                 'message'   => 'Data berhasil dihapus'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status'    => false,
    //                 'message'   => 'Data tidak ditemukan'
    //             ]);
    //         }
    //     }
    //     return redirect('/');
    // }

    public function delete(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        try {
            // Cari data berdasarkan ID
            $prodi = ProdiModel::find($id);
            if ($prodi) {
                // Hapus data
                $prodi->delete();

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
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangkap kesalahan constraint database (misalnya, kunci asing)
            return response()->json([
                'status'    => false,
                'message'   => 'Data tidak dapat dihapus karena terkait dengan data lain.',
                'error'     => $e->getMessage() // Opsional: hapus pada produksi
            ]);
        } catch (\Exception $e) {
            // Tangkap kesalahan umum lainnya
            return response()->json([
                'status'    => false,
                'message'   => 'Terjadi kesalahan saat menghapus data.',
                'error'     => $e->getMessage() // Opsional: hapus pada produksi
            ]);
        }
    }

    // Jika permintaan bukan AJAX, arahkan kembali ke halaman utama
    return redirect('/');
}


    public function export_pdf()
    {
        $prodi = ProdiModel::select('nama_prodi')
            ->orderBy('nama_prodi')
            ->get();

        $pdf = Pdf::loadView('prodi.export_pdf', ['prodi' => $prodi]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);

        return $pdf->stream('Data_Prodi_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
