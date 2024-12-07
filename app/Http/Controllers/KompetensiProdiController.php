<?php

namespace App\Http\Controllers;

use App\Models\KompetensiProdiModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class KompetensiProdiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Kompetensi Prodi',
            'list' => ['Home', 'Kompetensi Prodi']
        ];

        $page = (object)[
            'title' => 'Daftar Kompetensi Prodi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kompetensiprodi';

        $kompetensi = KompetensiProdiModel::all();

        return view('kompetensiprodi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kompetensi' => $kompetensi,
        ]);
    }

    public function list()
    {
        $kompetensi = KompetensiProdiModel::select('id_kompetensi', 'prodi', 'bidang_terkait');

        return DataTables::of($kompetensi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kompetensi) {
                $btn = '<button onclick="modalAction(\'' . url('/kompetensiprodi/' . $kompetensi->id_kompetensi . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kompetensiprodi/' . $kompetensi->id_kompetensi . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kompetensiprodi/' . $kompetensi->id_kompetensi . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        return view('kompetensiprodi.create');
    }

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'prodi' => 'required|string|max:255',
                'bidang_terkait' => 'required|string|max:255'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            KompetensiProdiModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data kompetensi prodi berhasil disimpan'
            ]);
        }
        return redirect('/kompetensiprodi');
    }

//     public function store(Request $request)
// {
//     $rules = [
//         'prodi' => 'required|string|max:255',
//         'bidang_terkait' => 'required|string|max:255',
//     ];

//     $validator = Validator::make($request->all(), $rules);

//     if ($validator->fails()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Validasi gagal.',
//             'msgField' => $validator->errors(),
//         ]);
//     }

//     KompetensiProdiModel::create([
//         'prodi' => $request->prodi,
//         'bidang_terkait' => $request->bidang_terkait,
//     ]);

//     return response()->json([
//         'status' => true,
//         'message' => 'Data Kompetensi Prodi berhasil disimpan.',
//     ]);
// }



    public function show(String $id)
    {
        $kompetensi = KompetensiProdiModel::find($id);

        return view('kompetensiprodi.show', ['kompetensi' => $kompetensi]);
    }

    public function edit(String $id)
    {
        $kompetensi = KompetensiProdiModel::find($id);

        return view('kompetensiprodi.edit', ['kompetensi' => $kompetensi]);
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'prodi' => 'required|string|max:255',
                'bidang_terkait' => 'required|string|max:255'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $kompetensi = KompetensiProdiModel::find($id);
            if ($kompetensi) {
                $kompetensi->update($request->all());
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
        $kompetensi = KompetensiProdiModel::find($id);

        return view('kompetensiprodi.confirm', ['kompetensi' => $kompetensi]);
    }

    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kompetensi = KompetensiProdiModel::find($id);
            if ($kompetensi) {
                $kompetensi->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
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
}
