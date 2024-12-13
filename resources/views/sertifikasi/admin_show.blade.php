@empty($sertifikasi)
    <div id="modal-master" class="modal-dialog modal-xl" role="document">
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
    <form action="{{ url('/sertifikasi/' . $sertifikasi->id_sertifikasi . '/admin_show_update') }}" method="POST"
        id="form-edit" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Bukti Sertifikasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-borderless table-striped">
                        <tr>
                            <th class="text-right col-3">Nama Sertifikasi</th>
                            <td>{{ $sertifikasi->nama_sertifikasi }}</td>
                            <th> </th>
                            <td> </td>
                        </tr>
                        @if ($sertifikasi->status_sertifikasi != 'menunggu')
                            @if ($sertifikasi && $sertifikasi->detail_peserta_sertifikasi->count())
                                @foreach ($sertifikasi->detail_peserta_sertifikasi as $peserta)
                                    <tr>
                                        <th class="text-right">Nama Peserta</th>
                                        <td>{{ $peserta->nama_lengkap }}</td>
                                        <th class="text-right">Bukti Sertifikasi</th>
                                        <td>
                                            <input type="file" id="bukti_sertifikasi"
                                                name="bukti_sertifikasi[{{ $peserta->user_id }}]" class="form-control">
                                            <small id="error-id_bukti_sertifikasi"
                                                class="error-text form-text text-danger"></small>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada peserta terkait.</td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td colspan="4" class="text-center">Rekomendasi sertifikasi belum diterima atau ditolak.
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn"
                    style="color: #EF5428; background-color: white; border-color: #EF5428;">Batal</button>
                <button type="submit"
                    class="btn"style="color: white; background-color: #EF5428; border-color: #EF5428;">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    'bukti_sertifikasi[]': {
                        required: true,
                        extension: "pdf"
                    },
                },
                submitHandler: function(form) {
                    var formData = new FormData(document.getElementById('form-edit'));
                    console.log('Files in FormData:', formData.get(
                        'bukti_sertifikasi[]')); // Jika menggunakan array
                    console.log('All FormData:', [...formData.entries()]);
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        processData: false, // Matikan proses data
                        contentType: false, // Matikan header content-type agar sesuai dengan FormData
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
