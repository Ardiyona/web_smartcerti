@empty($user)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID</th>
                        <td class="col-9">{{ $user->user_id }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Level</th>
                        <td class="col-9">{{ $user->level->nama_level }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Username</th>
                        <td class="col-9">{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Lengkap</th>
                        <td class="col-9">{{ $user->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">No Telepon</th>
                        <td class="col-9">{{ $user->no_telp }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Email</th>
                        <td class="col-9">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenis Kelamin</th>
                        <td class="col-9">{{ $user->jenis_kelamin }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Password </th>
                        <td class="col-9">********</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Bidang Minat</th>
                        <td class="col-9">
                            {{ $user->detail_daftar_user_bidang_minat->pluck('nama_bidang_minat')->implode(', ') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Mata Kuliah</th>
                        <td class="col-9">
                            {{ $user->detail_daftar_user_matakuliah->pluck('nama_matakuliah')->implode(', ') }}</td>
                    </tr>
                    </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Kembali</button>
            </div>
        </div>
    </div>
@endempty
