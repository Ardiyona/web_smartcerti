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
    <form action="{{ url('/pelatihan/' . $pelatihan->id_pelatihan . '/update') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Pelatihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Vendor</label>
                        <select name="id_vendor_pelatihan" id="id_vendor_pelatihan" class="form-control" required>
                            <option value="">- Pilih Vendor -</option>
                            @foreach ($vendorpelatihan as $l)
                                <option
                                    {{ $l->id_vendor_pelatihan == $pelatihan->id_vendor_pelatihan ? 'selected' : '' }}
                                    value="{{ $l->id_vendor_pelatihan }}">{{ $l->nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_vendor_pelatihan" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Jenis Bidang</label>
                        <select name="id_jenis_pelatihan" id="id_jenis_pelatihan" class="form-control" required>
                            <option value="">- Pilih Jenis Bidang -</option>
                            @foreach ($jenispelatihan as $l)
                                <option
                                    {{ $l->id_jenis_pelatihan == $pelatihan->id_jenis_pelatihan ? 'selected' : '' }}
                                    value="{{ $l->id_jenis_pelatihan }}">{{ $l->nama_jenis_pelatihan }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_jenis_pelatihan" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Tahun Periode</label>
                        <select name="id_periode" id="id_periode" class="form-control" required>
                            <option value="">- Pilih Tahun Periode -</option>
                            @foreach ($periode as $l)
                                <option {{ $l->id_periode == $pelatihan->id_periode ? 'selected' : '' }}
                                    value="{{ $l->id_periode }}">{{ $l->tahun_periode }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_periode" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Nama pelatihan -->
                    <div class="form-group">
                        <label>Nama pelatihan</label>
                        <input value ="{{ $pelatihan->nama_pelatihan }}" type="text" name="nama_pelatihan"
                            id="nama_pelatihan" class="form-control" required>
                        <small id="error-nama_pelatihan" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Jenis -->
                    <div class="form-group">
                        <label>Level Pelatihan</label>
                        <select value ="{{ $pelatihan->level_pelatihan }}" name="level_pelatihan" id="level_pelatihan" class="form-control"
                            required>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                        <small id="error-level_pelatihan" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Lokasi pelatihan -->
                    <div class="form-group">
                        <label>Lokasi</label>
                        <input value ="{{ $pelatihan->lokasi }}" type="text" name="lokasi"
                            id="lokasi" class="form-control" required>
                        <small id="error-lokasi" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Tanggal -->
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input value ="{{ $pelatihan->tanggal }}" type="date" name="tanggal" id="tanggal"
                            class="form-control" required>
                        <small id="error-tanggal" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Bukti pelatihan -->
                    <div class="form-group">
                        <label>Bukti pelatihan</label>
                        <input value ="{{ $pelatihan->bukti_pelatihan }}" type="file" name="bukti_pelatihan"
                            id="bukti_pelatihan" class="form-control" required>
                        <small class="form-text text-muted">Abaikan jika tidak ingin ubah file bukti pelatihan</small>
                        <small id="error-bukti_pelatihan" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Kuota Peserta -->
                    <div class="form-group">
                        <label>Kuota Peserta</label>
                        <input value ="{{ $pelatihan->kuota_peserta }}" type="number" name="kuota_peserta"
                            id="kuota_peserta" class="form-control" required>
                        <small id="error-kuota_peserta" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Biaya -->
                    <div class="form-group">
                        <label>Biaya</label>
                        <input value ="{{ $pelatihan->biaya }}" type="number" name="biaya" id="biaya"
                            class="form-control" required>
                        <small id="error-biaya" class="error-text form-text text-danger"></small>
                    </div>


                    @if (Auth::user()->id_level == 1)
                        <div class="form-group">
                            <label>Nama Peserta</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">- Pilih Peserta pelatihan -</option>
                                @foreach ($user as $l)
                                    <option {{ $pelatihan->detail_peserta_pelatihan->contains($l->user_id) ? 'selected' : '' }} value="{{ $l->user_id }}">{{ $l->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            <small id="error-user_id" class="error-text form-text text-danger"></small>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="id_bidang_minat">
                            Tag Bidang Minat
                        </label>
                        <select multiple="multiple" name="id_bidang_minat[]" id="id_bidang_minat"
                            class="js-example-basic-multiple js-states form-control form-control">
                            @foreach ($bidangMinat as $item)
                                <option
                                    {{ $pelatihan->bidang_minat_pelatihan->contains($item->id_bidang_minat) ? 'selected' : '' }}
                                    value="{{ $item->id_bidang_minat }}">{{ $item->nama_bidang_minat }}
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
                                <option
                                    {{ $pelatihan->mata_kuliah_pelatihan->contains($item->id_matakuliah) ? 'selected' : '' }}
                                    value="{{ $item->id_matakuliah }}">{{ $item->nama_matakuliah }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_matakuliah" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    id_vendor_pelatihan: {
                        required: true,
                        number: true
                    },
                    id_jenis_pelatihan: {
                        required: true,
                        number: true
                    },
                    id_periode: {
                        required: true,
                        number: true
                    },
                    nama_pelatihan: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    level_pelatihan: {
                        required: true,
                    },
                    lokasi: {
                        required: true,
                    },
                    tanggal: {
                        required: true,
                    },
                    bukti_pelatihan: {
                        required: false,
                        extension: "pdf"
                    },
                    kuota_peserta: {
                        required: true,
                        number: true
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
                                dataPelatihan.ajax.reload();
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
            $("#id_matakuliah, #id_bidang_minat").select2({
                dropdownAutoWidth: true,
                theme: "classic"
            });
        });
    </script>
@endempty
