@empty($sertifikasi)
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
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Permintaan Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">ID</th>
                        <td class="col-9">{{ $sertifikasi->id_sertifikasi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Vendor</th>
                        <td class="col-9">{{ $sertifikasi->vendor_sertifikasi->nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenis Bidang</th>
                        <td class="col-9">{{ $sertifikasi->jenis_sertifikasi->nama_jenis_sertifikasi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tahun Periode</th>
                        <td class="col-9">{{ $sertifikasi->periode->tahun_periode }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama Sertifikasi</th>
                        <td class="col-9">{{ $sertifikasi->nama_sertifikasi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenis Sertifikasi</th>
                        <td class="col-9">{{ $sertifikasi->jenis }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal</th>
                        <td class="col-9">{{ $sertifikasi->tanggal }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Masa Berlaku</th>
                        <td class="col-9">{{ $sertifikasi->masa_berlaku }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Kuota Peserta</th>
                        <td class="col-9">{{ $sertifikasi->kuota_peserta }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Biaya</th>
                        <td class="col-9">{{ $sertifikasi->biaya }}</td>
                    </tr>

                    @if (Auth::user()->id_level == 1)
                        <tr>
                            <th class="text-right col-3">Nama Peserta</th>
                            <td class="col-9">
                                {{ $sertifikasi->detail_peserta_sertifikasi->pluck('nama_lengkap')->implode(', ') }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th class="text-right col-3">Bidang Minat</th>
                        <td class="col-9">
                            {{ $sertifikasi->bidang_minat_sertifikasi->pluck('nama_bidang_minat')->implode(', ') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Mata Kuliah</th>
                        <td class="col-9">
                            {{ $sertifikasi->mata_kuliah_sertifikasi->pluck('nama_matakuliah')->implode(', ') }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Peserta</th>
                        <td class="col-9">
                            {{ $sertifikasi->detail_peserta_sertifikasi->pluck('nama_lengkap')->implode(', ') }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                @if ($sertifikasi->status_sertifikasi == 'menunggu')
                <form action="{{ route('penerimaanpermintaan.updateStatus', ['id' => $sertifikasi->id_sertifikasi, 'status' => 'terima']) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-success">Terima</button>
                </form>
            
                <form action="{{ route('penerimaanpermintaan.updateStatus', ['id' => $sertifikasi->id_sertifikasi, 'status' => 'tolak']) }}" method="POST" style="display: inline;">
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
