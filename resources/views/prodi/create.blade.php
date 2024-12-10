<form action="{{ url('/prodi/store') }}" method="POST" id="form-tambah-prodi">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Program Studi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Program Studi</label>
                    <input type="text" name="kode_prodi" id="kode_prodi" class="form-control" required>
                    <small id="error-kode_prodi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Program Studi</label>
                    <input type="text" name="nama_prodi" id="nama_prodi" class="form-control" required>
                    <small id="error-nama_prodi" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn" style="color: #EF5428; background-color: white; border-color: #EF5428;">Batal</button>
                <button type="submit" class="btn" style="color: white; background-color: #EF5428; border-color: #EF5428;">Simpan</button>
            </div>
        </div>
    </div>
</form>

{{-- <script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#form-tambah-prodi").validate({
            rules: {
                nama_prodi: {
                    required: true,
                    maxlength: 255
                },
            },
            messages: {
                nama_prodi: {
                    required: "Nama program studi wajib diisi.",
                    maxlength: "Nama program studi tidak boleh lebih dari 255 karakter."
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#modal-master').modal('hide'); // Menutup modal
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataProdi.ajax.reload(); // Reload data DataTables untuk Prodi
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
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal menyimpan data. Silakan coba lagi.'
                        });
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
</script> --}}

<script>
    $("#form-tambah-prodi").validate({
    rules: {

        nama_prodi: {
            required: true,
            maxlength: 255
        },
        kode_prodi: {
            required: true,
            maxlength: 10 // Sesuaikan panjang maksimal jika diperlukan
        }

    },
    messages: {
        // kode_prodi: {
        //     required: "Kode program studi wajib diisi.",
        //     maxlength: "Kode program studi tidak boleh lebih dari 10 karakter." // Sesuaikan pesan jika perlu
        // },
        // nama_prodi: {
        //     required: "Nama program studi wajib diisi.",
        //     maxlength: "Nama program studi tidak boleh lebih dari 255 karakter."
        // }

    },
    submitHandler: function(form) {
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize(),
            success: function(response) {
                if (response.status) {
                    $('#myModal').modal('hide'); // Menutup modal
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    });
                    dataProdi.ajax.reload(); // Reload data DataTables untuk Prodi
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
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal menyimpan data. Silakan coba lagi.'
                });
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

</script>