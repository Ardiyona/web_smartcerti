@empty($pelatihan)
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
                <a href="{{ url('/penerimaanpermintaan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Permintaan Pelatihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID</th>
                        <td class="col-9">{{ $pelatihan->id_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Vendor</th>
                        <td class="col-9">{{ $pelatihan->vendor_pelatihan->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenis Bidang</th>
                        <td class="col-9">{{ $pelatihan->jenis_pelatihan->nama_jenis_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tahun Periode</th>
                        <td class="col-9">{{ $pelatihan->periode->tahun_periode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Pelatihan</th>
                        <td class="col-9">{{ $pelatihan->nama_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">level Pelatihan</th>
                        <td class="col-9">{{ $pelatihan->level_pelatihan }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal</th>
                        <td class="col-9">{{ $pelatihan->tanggal }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Lokasi</th>
                        <td class="col-9">{{ $pelatihan->lokasi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kuota Peserta</th>
                        <td class="col-9">{{ $pelatihan->kuota_peserta }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Biaya</th>
                        <td class="col-9">{{ $pelatihan->biaya }}</td>
                    </tr>

                    @if (Auth::user()->id_level == 1)
                        <tr>
                            <th class="text-right col-3">Nama Peserta</th>
                            <td class="col-9">
                                {{ $pelatihan->detail_peserta_pelatihan->pluck('nama_lengkap')->implode(', ') }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th class="text-right col-3">Bidang Minat</th>
                        <td class="col-9">
                            {{ $pelatihan->bidang_minat_pelatihan->pluck('nama_bidang_minat')->implode(', ') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Mata Kuliah</th>
                        <td class="col-9">
                            {{ $pelatihan->mata_kuliah_pelatihan->pluck('nama_matakuliah')->implode(', ') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Peserta</th>
                        <td class="col-9">
                            {{ $pelatihan->detail_peserta_pelatihan->pluck('nama_lengkap')->implode(', ') }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                @if ($pelatihan->status_pelatihan == 'menunggu')
                <form action="{{ route('penerimaanpermintaan.updateStatus', ['id' => $pelatihan->id_pelatihan, 'status' => 'terima']) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Terima</button>
                </form>
            
                <form action="{{ route('penerimaanpermintaan.updateStatus', ['id' => $pelatihan->id_pelatihan, 'status' => 'tolak']) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">Tolak</button>
                </form>
                @endif
            
                <button type="button" data-dismiss="modal" class="btn btn-default">Kembali</button>
            </div>
        </div>
    </div>
@endempty
