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
                <a href="{{ url('/pelatihan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data Pelatihan</h5>
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
                        <th class="text-right col-3">Lokasi</th>
                        <td class="col-9">{{ $pelatihan->lokasi }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Tanggal</th>
                        <td class="col-9">{{ $pelatihan->tanggal }}</td>
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
                        <th class="text-right col-3">Bukti Pelatihan</th>
                        <td class="col-9">
                            @php
                                // Mendapatkan user yang sedang login
                                $currentUser = Auth::user();

                                // Filter detail_peserta_pelatihan milik user yang login
                                $userDetail = $pelatihan->detail_peserta_pelatihan
                                    ->where('user_id', $currentUser->user_id)
                                    ->first();
                            @endphp
                            @if ($userDetail && $userDetail->pivot->bukti_pelatihan)
                                {{-- Jika user memiliki bukti pelatihan --}}
                                @php
                                    // Ambil nama file tanpa path
                                    $fullFileName = basename($userDetail->pivot->bukti_pelatihan);

                                    // Hilangkan tanggal di depan nama file
                                    $cleanFileName = preg_replace('/^\d{10}_/', '', $fullFileName);
                                @endphp

                                <a href="{{ url('storage/bukti_pelatihan/' . $userDetail->pivot->bukti_pelatihan) }}"
                                    target="_blank" download>
                                    {{ $cleanFileName }}
                                </a>
                            @else
                                <span class="text-danger">Tidak ada bukti pelatihan</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Kembali</button>
            </div>
        </div>
    </div>
@endempty
