<?php

namespace App\Http\Controllers;

use App\Models\KompetensiProdiModel;
use App\Models\ProdiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    // public function list()
    // {
    //     $kompetensi = KompetensiProdiModel::select('id_kompetensi', 'prodi', 'bidang_terkait');

    //     return DataTables::of($kompetensi)
    //         ->addIndexColumn()
    //         ->addColumn('aksi', function ($kompetensi) {
    //             $btn = '<button onclick="modalAction(\'' . url('/kompetensiprodi/' . $kompetensi->id_kompetensi . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
    //             $btn .= '<button onclick="modalAction(\'' . url('/kompetensiprodi/' . $kompetensi->id_kompetensi . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
    //             $btn .= '<button onclick="modalAction(\'' . url('/kompetensiprodi/' . $kompetensi->id_kompetensi . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
    //             return $btn;
    //         })
    //         ->rawColumns(['aksi'])
    //         ->make(true);
    // }
    public function list(Request $request)
    {
        
        // Ambil data dari tabel kompetensi_prodi beserta relasi ke tabel prodi
        $kompetensi = KompetensiProdiModel::select('id_kompetensi', 'id_prodi', 'bidang_terkait')->with('prodi'); // Pilih kolom yang relevan
    
        return DataTables::of($kompetensi)
            ->addIndexColumn()
            ->addColumn('prodi', function ($kompetensi) {
                // Ambil nama_prodi dari relasi prodi
                return $kompetensi->prodi->nama_prodi ?? '-';
            })
            
            ->addColumn('aksi', function ($kompetensi) {
                $levelId = Auth::user();
                if ($levelId->id_level == 1) {
                // Tombol aksi (Detail, Edit, Hapus)
                $btn = '<button onclick="modalAction(\'' . url('/kompetensiprodi/' . $kompetensi->id_kompetensi . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                // $btn .= '<button onclick="modalAction(\'' . url('/kompetensiprodi/' . $kompetensi->id_kompetensi . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kompetensiprodi/' . $kompetensi->id_kompetensi . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
                }
            })
            ->rawColumns(['aksi']) // Pastikan kolom aksi dirender dengan HTML
            ->make(true);
    }

    //create drop down
    
    public function create()
    {
        // Ambil data dari tabel 'prodi' untuk dropdown
        $prodiList = ProdiModel::select('id_prodi', 'nama_prodi')->get();
    
        // Tampilkan view 'kompetensiprodi.create' dengan data prodi
        return view('kompetensiprodi.create')->with([
            'prodiList' => $prodiList
        ]);
    }
    

    public function store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi input
            $rules = [
                'id_prodi' => 'required|exists:prodi,id_prodi', // Harus sesuai dengan ID di tabel prodi
                'bidang_terkait' => 'required|string|max:50',
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }
    
            // Simpan data ke tabel 'kompetensi_prodi'
            KompetensiProdiModel::create([
                'id_prodi' => $request->id_prodi,
                'bidang_terkait' => $request->bidang_terkait,
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'Data kompetensi prodi berhasil disimpan',
            ]);
        }
    
        // Jika bukan permintaan AJAX, redirect ke halaman utama
        return redirect('/kompetensiprodi');
    }
    



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
