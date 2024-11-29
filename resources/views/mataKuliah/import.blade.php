<form action="{{ url('/matakuliah/import_ajax') }}" method="POST" id="form-import-matakuliah" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Data Mata Kuliah</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template</label>
                    <a href="{{ asset('template_mata_kuliah.xlsx') }}" class="btn" style="color: white; background-color: #375E97; border-color: #375E97;">
                        <i class="fa fa-file-excel"></i> Download</a>
                    <small id="error-template" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Pilih File</label>
                    <input type="file" name="file_mata_kuliah" id="file_mata_kuliah" class="form-control" required>
                    <small id="error-file_mata_kuliah" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn" style="color: #EF5428; background-color: white; border-color: #EF5428;">Batal</button>
                <button type="submit" class="btn" style="color: white; background-color: #EF5428; border-color: #EF5428;"> Kirim</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-import-matakuliah").validate({
            rules: {
                file_mata_kuliah: {
                    required: true,
                    extension: "xlsx"
                },
            },
            submitHandler: function(form) {
                var formData = new FormData(form); // Jadikan form ke FormData untuk menghandle file
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData, // Data yang dikirim berupa FormData
                    processData: false, // Setting processData dan contentType ke false
                    contentType: false,
                    success: function(response) {
                        if (response.status) { // Jika sukses
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            tableMataKuliah.ajax.reload(); // Reload datatable mata kuliah
                        } else { // Jika error
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
