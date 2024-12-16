@empty($bidangMinat)
    <div id="modal-bidang-minat" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/bidangminat') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/bidangminat/' . $bidangMinat->id_bidang_minat . '/update') }}" method="POST" id="form-edit-bidang-minat">
    @csrf
    @method('PUT')
    <div id="modal-bidang-minat" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Bidang Minat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Bidang Minat</label>
                    <input value="{{ $bidangMinat->kode_bidang_minat }}" type="text" name="kode_bidang_minat" id="kode_bidang_minat" class="form-control" required>
                    <small id="error-kode_bidang_minat" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Bidang Minat</label>
                    <input value="{{ $bidangMinat->nama_bidang_minat }}" type="text" name="nama_bidang_minat" id="nama_bidang_minat" class="form-control" required>
                    <small id="error-nama_bidang_minat" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn" style="color: #EF5428; background-color: white; border-color: #EF5428;">Batal</button>
                <button type="submit" class="btn" style="color: white; background-color: #EF5428; border-color: #EF5428;">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-edit-bidang-minat").validate({
            rules: {
                kode_bidang_minat: {
                    required: true,
                    minlength: 2,
                },
                nama_bidang_minat: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
            },
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
                            dataBidangMinat.ajax.reload(); // Reload DataTables for Bidang Minat
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
