{{-- <form action="{{ url('/kompetensiprodi/store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kompetensi Prodi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Prodi</label>
                    <input value="" type="text" name="prodi" id="prodi" class="form-control" required>
                    <small id="error-prodi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Bidang Terkait</label>
                    <input value="" type="text" name="bidang_terkait" id="bidang_terkait" class="form-control" required>
                    <small id="error-bidang_terkait" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn" style="color: #EF5428; background-color: white; border-color: #EF5428;">Batal</button>
                <button type="submit" class="btn"style="color: white; background-color: #EF5428; border-color: #EF5428;">Simpan</button>
            </div>
        </div>
    </div>
</form> --}}

<form action="{{ url('/kompetensiprodi/store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Kompetensi Prodi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Prodi</label>
                    <select name="id_prodi" id="id_prodi" class="form-control" required>
                        <option value="">- Pilih Program Studi -</option>
                        @foreach ($prodiList as $prodi)
                            <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_prodi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Bidang Terkait</label>
                    <input value="" type="text" name="bidang_terkait" id="bidang_terkait" class="form-control" required>
                    <small id="error-bidang_terkait" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn" style="color: #EF5428; background-color: white; border-color: #EF5428;">Batal</button>
                <button type="submit" class="btn"style="color: white; background-color: #EF5428; border-color: #EF5428;">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
$("#form-tambah").validate({
    rules: {
        id_prodi: {
            required: true
        },
        bidang_terkait: {
            required: true,
            maxlength: 50
        }
    },
    submitHandler: function(form) {
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize(),
            success: function(response) {
                if (response.status) {
                    $('#myModal').modal('hide'); // Pastikan ini sesuai dengan ID modal
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message
                    });
                    dataKompetensiProdi.ajax.reload(); // Reload data DataTables jenis pelatihan
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

</script>


