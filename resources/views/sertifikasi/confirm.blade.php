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
                <a href="{{ url('/sertifikasi') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/sertifikasi/' . $sertifikasi->id_sertifikasi . '/delete') }}" method="POST" id="form-delete">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Data Sertifikasi</h5>
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
                            <th class="text-right col-3">No Sertifikasi</th>
                            <td class="col-9">
                                @php
                                    $currentUser = Auth::user();
                                    $userNoSertifikasi =
                                        $sertifikasi->detail_peserta_sertifikasi
                                            ->filter(function ($peserta) use ($currentUser) {
                                                return $peserta->user_id == $currentUser->user_id;
                                            })
                                            ->pluck('pivot.no_sertifikasi')
                                            ->implode('- ') ?? ''; // Berikan default kosong
                                @endphp
                                {{ $userNoSertifikasi }}
                            </td>
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
                        <tr>
                            <th class="text-right col-3">Bidang Minat</th>
                            <td class="col-9">
                                {{ $sertifikasi->bidang_minat_sertifikasi->pluck('nama_bidang_minat')->implode(', ') }}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Mata Kuliah</th>
                            <td class="col-9">
                                {{ $sertifikasi->mata_kuliah_sertifikasi->pluck('nama_matakuliah')->implode(', ') }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Bukti Sertifikasi</th>
                            <td class="col-9">
                                @php
                                    // Mendapatkan user yang sedang login
                                    $currentUser = Auth::user();

                                    // Filter detail_peserta_sertifikasi milik user yang login
                                    $userDetail = $sertifikasi->detail_peserta_sertifikasi
                                        ->where('user_id', $currentUser->user_id)
                                        ->first();
                                @endphp
                                @if ($userDetail && $userDetail->pivot->bukti_sertifikasi)
                                    {{-- Jika user memiliki bukti sertifikasi --}}
                                    @php
                                        // Ambil nama file tanpa path
                                        $fullFileName = basename($userDetail->pivot->bukti_sertifikasi);

                                        // Hilangkan tanggal di depan nama file
                                        $cleanFileName = preg_replace('/^\d{10}_/', '', $fullFileName);
                                    @endphp

                                    <a href="{{ url('storage/bukti_sertifikasi/' . $userDetail->pivot->bukti_sertifikasi) }}"
                                        target="_blank" download>
                                        {{ $cleanFileName }}
                                    </a>
                                @else
                                    <span class="text-danger">Tidak ada bukti sertifikasi</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn"
                    style="color: #EF5428; background-color: white; border-color: #EF5428;">Batal</button>
                <button type="submit"
                    class="btn"style="color: white; background-color: #EF5428; border-color: #EF5428;">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-delete").validate({
                rules: {},
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                dataSertifikasi.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
