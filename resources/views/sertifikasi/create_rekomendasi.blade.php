<form action="{{ url('/sertifikasi/store_rekomendasi') }}" method="POST" id="form-tambah" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Pengajuan Sertifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Vendor</label>
                    <select name="id_vendor_sertifikasi" id="id_vendor_sertifikasi" class="form-control" required>
                        <option value="">- Pilih Vendor -</option>
                        @foreach ($vendorSertifikasi as $l)
                            <option value="{{ $l->id_vendor_sertifikasi }}">{{ $l->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_vendor_sertifikasi" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Jenis Bidang</label>
                    <select name="id_jenis_sertifikasi" id="id_jenis_sertifikasi" class="form-control" required>
                        <option value="">- Pilih Jenis Bidang -</option>
                        @foreach ($jenisSertifikasi as $l)
                            <option value="{{ $l->id_jenis_sertifikasi }}">{{ $l->nama_jenis_sertifikasi }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_jenis_sertifikasi" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tahun Periode</label>
                    <select name="id_periode" id="id_periode" class="form-control" required>
                        <option value="">- Pilih Tahun Periode -</option>
                        @foreach ($periode as $l)
                            <option value="{{ $l->id_periode }}">{{ $l->tahun_periode }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_periode" class="error-text form-text text-danger"></small>
                </div>

                <!-- Nama Sertifikasi -->
                <div class="form-group">
                    <label>Nama Sertifikasi</label>
                    <input type="text" name="nama_sertifikasi" id="nama_sertifikasi" class="form-control" required>
                    <small id="error-nama_sertifikasi" class="error-text form-text text-danger"></small>
                </div>

                <!-- Jenis -->
                <div class="form-group">
                    <label>Jenis</label>
                    <select name="jenis" id="jenis" class="form-control" required>
                        <option value="Profesi">Profesi</option>
                        <option value="Keahlian">Keahlian</option>
                    </select>
                    <small id="error-jenis" class="error-text form-text text-danger"></small>
                </div>

                <!-- Tanggal -->
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                    <small id="error-tanggal" class="error-text form-text text-danger"></small>
                </div>

                <!-- Biaya -->
                <div class="form-group">
                    <label>Biaya</label>
                    <input type="number" name="biaya" id="biaya" class="form-control" required>
                    <small id="error-biaya" class="error-text form-text text-danger"></small>
                </div>

                {{-- <div class="form-group">
                    <label>Nama Peserta</label>
                    <select multiple="multiple" name="user_id[]" id="user_id"
                        class="js-example-basic-multiple js-states form-control form-control">
                        <option value="">- Pilih Peserta Sertifikasi -</option>
                        @foreach ($user as $l)
                            <option value="{{ $l->user_id }}">{{ $l->nama_lengkap }}</option>
                        @endforeach
                    </select>
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
                </div> --}}

                <div class="form-group">
                    <label for="id_bidang_minat">
                        Tag Bidang Minat
                    </label>
                    <select multiple="multiple" name="id_bidang_minat[]" id="id_bidang_minat"
                        class="js-example-basic-multiple js-states form-control form-control">
                        @foreach ($bidangMinat as $item)
                            <option value="{{ $item->id_bidang_minat }}">{{ $item->nama_bidang_minat }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_bidang_minat" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="id_matakuliah">
                        Tag Mata Kuliah
                    </label>
                    <select multiple="multiple" name="id_matakuliah[]" id="id_matakuliah"
                        class="js-example-basic-multiple js-states form-control">
                        @foreach ($mataKuliah as $item)
                            <option value="{{ $item->id_matakuliah }}">{{ $item->nama_matakuliah }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_matakuliah" class="error-text form-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label for="user_id">Nama Peserta</label>
                    <select multiple="multiple" name="user_id[]" id="user_id" class="js-example-basic-multiple form-control">
                        <!-- Peserta akan diisi ulang oleh JavaScript -->
                    </select>
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
                </div>
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
        $("#id_bidang_minat, #id_matakuliah, #user_id").select2({
            dropdownAutoWidth: true,
            theme: "classic",
            width: '100%'
        });

        // Event listener untuk bidang minat dan mata kuliah
        $('#id_bidang_minat, #id_matakuliah').on('change', function() {
            const bidangMinat = $('#id_bidang_minat').val();
            const mataKuliah = $('#id_matakuliah').val();

            if (bidangMinat.length > 0 && mataKuliah.length > 0) {
                // Memulai loading peserta
                $('#user_id').prop('disabled', true).html('<option>Loading...</option>');

                // Mengirimkan data ke server via AJAX
                $.ajax({
                    url: "{{ url('/sertifikasi/filter_peserta') }}",
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        bidang_minat: bidangMinat,
                        mata_kuliah: mataKuliah
                    },
                    success: function(response) {
                        if (response.status && response.data.length > 0) {
                            // Isi ulang select peserta
                            const pesertaOptions = response.data.map(peserta =>
                                `<option value="${peserta.user_id}">${peserta.nama_lengkap} (Minat: ${peserta.bidang_minat_count || 0}, Matkul: ${peserta.mata_kuliah_count || 0})</option>`
                            ).join('');

                            $('#user_id').html(pesertaOptions).prop('disabled', false);
                        } else {
                            // Fallback: Tetap tampilkan peserta meskipun tidak ada yang cocok
                            $('#user_id').html('<option>Tidak ada peserta</option>').prop(
                                'disabled', true);
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan.');
                    }
                });
            }
        });
        $("#form-tambah").validate({
            rules: {
                id_vendor_sertifikasi: {
                    required: true,
                    number: true
                },
                id_jenis_sertifikasi: {
                    required: true,
                    number: true
                },
                id_periode: {
                    required: true,
                    number: true
                },
                nama_sertifikasi: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                jenis: {
                    required: true,
                },
                tanggal: {
                    required: true,
                },
                biaya: {
                    required: true,
                    number: true
                },
                id_bidang_minat: {
                    required: true,
                },
                id_matakuliah: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                var formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    contentType: false,
                    processData: false,
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
        $("#id_matakuliah, #id_bidang_minat, #user_id").select2({
            dropdownAutoWidth: true,
            theme: "classic",
            width: '100%'
        });
    });
</script>
