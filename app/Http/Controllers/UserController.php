<?php

namespace App\Http\Controllers;

use App\Models\BidangMinatModel;
use App\Models\LevelModel;
use App\Models\MataKuliahModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list'  => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user';

        $level = LevelModel::all();

        return view('user.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        // Mengambil data user beserta level
        $users = UserModel::select('user_id', 'username', 'nama_lengkap', 'avatar', 'id_level')
            ->with('level');

        // Filter data user berdasarkan id_level jika ada
        if ($request->id_level) {
            $users->where('id_level', $request->id_level);
        }

        // Mengembalikan data dengan DataTables
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('avatar', function ($user) {
                if ($user->avatar) {
                    return 'storage/photos/' . $user->avatar;
                } else {
                    return 'img/profile.png';
                }
            })
            ->addColumn('aksi', function ($user) {
                $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/show') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/edit') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/confirm') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        // Mengambil id_level dan nama_level dari tabel level
        $level = LevelModel::select('id_level', 'nama_level')->get();
        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();
        return view('user.create', [
            'level' => $level,
            'mataKuliah' => $mataKuliah,
            'bidangMinat' => $bidangMinat
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input termasuk avatar
        $this->validate($request, [
            'id_level' => 'required|integer',
            'username' => 'required|max:50|unique:user,username',
            'nama_lengkap' => 'required|max:255',
            'no_telp' => 'required|max:15',
            'email' => 'required|max:255',
            'jenis_kelamin' => 'required',
            'password' => 'nullable|min:5|max:20',
            'avatar'   => 'image|mimes:jpeg,png,jpg|max:2048',

            'id_bidang_minat' => 'required',
            'id_matakuliah' => 'required',
        ]);

        // Simpan data user
        $user = new UserModel();
        $user->id_level = $request->id_level;
        $user->username = $request->username;
        $user->nama_lengkap = $request->nama_lengkap;
        $user->no_telp = $request->no_telp;
        $user->email = $request->email;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->password = Hash::make($request->password);

        $user->detail_daftar_user_bidang_minat()->sync($request->id_bidang_minat);
        $user->detail_daftar_user_matakuliah()->sync($request->id_matakuliah);

        // Simpan file avatar jika ada
        if ($request->hasFile('avatar')) {
            $fileName = $request->file('avatar')->hashName();
            $request->file('avatar')->storeAs('public/photos', $fileName);
            $user->avatar = $fileName;
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Data user berhasil ditambahkan'
        ]);
    }


    public function show(String $id)
    {
        $user = UserModel::with('level', 'detail_daftar_user_bidang_minat', 'detail_daftar_user_matakuliah')->find($id);

        return view('user.show', ['user' => $user]);
    }

    // Menampilkan halaman form edit user
    public function edit(string $id)
    {
        // Mengambil data user berdasarkan ID
        $user = UserModel::find($id);

        // Mengambil data level
        $level = LevelModel::select('id_level', 'nama_level')->get();
        $bidangMinat = BidangMinatModel::select('id_bidang_minat', 'nama_bidang_minat')->get();
        $mataKuliah = MataKuliahModel::select('id_matakuliah', 'nama_matakuliah')->get();

        return view('user.edit', [
            'user' => $user,
            'level' => $level,
            'mataKuliah' => $mataKuliah,
            'bidangMinat' => $bidangMinat
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_level' => 'required|integer',
                'username' => 'required|max:50|unique:user,username,' . $id . ',user_id',
                'nama_lengkap' => 'required|max:255',
                'no_telp' => 'required|max:15',
                'email' => 'required|max:255',
                'jenis_kelamin' => 'required',
                'password' => 'nullable|min:5|max:20',
                'avatar'   => 'image|mimes:jpeg,png,jpg|max:2048',

                'id_bidang_minat' => 'required',
                'id_matakuliah' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $user = UserModel::find($id);

            if ($user) {
                // Hapus password dari request jika tidak diisi
                if (!$request->filled('password')) {
                    $request->request->remove('password');
                }

                // Proses avatar jika ada file yang diunggah
                if ($request->hasFile('avatar')) {
                    $fileName = time() . '.' . $request->file('avatar')->getClientOriginalExtension();
                    $path = $request->file('avatar')->storeAs('images', $fileName);
                    $request['avatar'] = '/storage/' . $path;
                } else {
                    $request->request->remove('avatar');
                }

                // Update data pengguna
                $user->update($request->only('username', 'nama_lengkap', 'no_telp', 'email', 'jenis_kelamin', 'password', 'avatar', 'id_level'));

                $user->detail_daftar_user_bidang_minat()->sync($request->id_bidang_minat);
                $user->detail_daftar_user_matakuliah()->sync($request->id_matakuliah);

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


    // Konfirmasi ajax
    public function confirm(string $id)
    {
        $user = UserModel::find($id);
        return view('user.confirm', ['user' => $user]);
    }

    // Update ajax
    public function delete(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);

            if ($user) {
                $user->detail_daftar_user_bidang_minat()->detach();
                $user->detail_daftar_user_matakuliah()->detach();
                $user->delete();
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
