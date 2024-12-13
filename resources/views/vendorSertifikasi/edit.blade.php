@empty($vendorsertifikasi)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
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
            <a href="{{ url('/vendorsertifikasi') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/vendorsertifikasi/' . $vendorsertifikasi->id_vendor_sertifikasi.'/update') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data Vendor Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Vendor Sertifikasi</label>
                    <input value="{{ $vendorsertifikasi->nama }}" type="text" name="nama"
                        id="nama" class="form-control" required>
                    <small id="error-nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <input value="{{ $vendorsertifikasi->alamat }}" type="text" name="alamat" id="alamat"
                        class="form-control" required>
                    <small id="error-alamat" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kota</label>
                    <input value="{{ $vendorsertifikasi->kota }}" type="text" name="kota" id="kota"
                        class="form-control" required>
                    <small id="error-kota" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input value="{{ $vendorsertifikasi->no_telp }}" type="text" name="no_telp" id="no_telp"
                        class="form-control" required>
                    <small id="error-no_telp" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Website</label>
                    <input value="{{ $vendorsertifikasi->alamat_web }}" type="text" name="alamat_web" id="alamat_web"
                        class="form-control" required>
                    <small id="error-alamat_web" class="error-text form-text text-danger"></small>
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
        $("#form-edit").validate({
            rules: {
                nama: {
                    required: true,
                    maxlength: 100
                },
                alamat: {
                    required: true,
                    maxlength: 100
                },
                kota: {
                    required: true,
                    maxlength: 100
                },
                no_telp: {
                    required: true,
                    maxlength: 20
                },
                alamat_web: {
                    required: true,
                    maxlength: 255
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
                            dataVendorSertifikasi.ajax.reload();
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