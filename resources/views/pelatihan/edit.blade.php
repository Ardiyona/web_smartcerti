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
                                <option {{ $l->id_vendor_pelatihan == $pelatihan->id_vendor_pelatihan ? 'selected' : '' }}
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
                                <option {{ $l->id_jenis_pelatihan == $pelatihan->id_jenis_pelatihan ? 'selected' : '' }}
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
                        <select value ="{{ $pelatihan->level_pelatihan }}" name="level_pelatihan" id="level_pelatihan"
                            class="form-control" required>
                            <option value="Nasional">Nasional</option>
                            <option value="Internasional">Internasional</option>
                        </select>
                        <small id="error-level_pelatihan" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Lokasi pelatihan -->
                    <div class="form-group">
                        <label>Lokasi</label>
                        <input value ="{{ $pelatihan->lokasi }}" type="text" name="lokasi" id="lokasi"
                            class="form-control" required>
                        <small id="error-lokasi" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Tanggal -->
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input value ="{{ $pelatihan->tanggal }}" type="date" name="tanggal" id="tanggal"
                            class="form-control" required>
                        <small id="error-tanggal" class="error-text form-text text-danger"></small>
                    </div>

                    @if (Auth::user()->id_level != 1)
                        <!-- Bukti Pelatihan -->
                        <div class="form-group">
                            <label>Bukti Pelatihan</label>

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
                                <small class="form-text">
                                    File saat ini:
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
                                </small>
                            @endif

                            {{-- Input File hanya untuk user yang sedang login --}}
                            <input type="file" name="bukti_pelatihan" id="bukti_pelatihan" class="form-control">
                            <small class="form-text text-muted">Abaikan jika tidak ingin mengubah file bukti
                                pelatihan</small>
                            <small id="error-bukti_pelatihan" class="error-text form-text text-danger"></small>
                        </div>
                    @endif

                    <!-- Kuota Peserta -->
                    <div class="form-group">
                        <label>Kuota Peserta</label>
                        <input value ="{{ $pelatihan->kuota_peserta }}" type="number" name="kuota_peserta"
                            id="kuota_peserta" class="form-control" readonly>
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
                            <label for="user_id">
                                Nama Peserta
                            </label>
                            <select multiple="multiple" name="user_id[]" id="user_id"
                                class="js-example-basic-multiple js-states form-control form-control">
                                @foreach ($user as $l)
                                    <option
                                        {{ $pelatihan->detail_peserta_pelatihan->contains($l->user_id) ? 'selected' : '' }}
                                        value="{{ $l->user_id }}">{{ $l->nama_lengkap }}</option>
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

                    @if ($pelatihan->status_pelatihan == 'terima' && Auth::user()->id_level == 1)
                        <div class="form-group">
                            <label>Surat Tugas</label>
                            @if ($pelatihan->surat_tugas)
                                {{-- Jika pelatihan memiliki surat tugas --}}
                                <small class="form-text">
                                    File saat ini:
                                    @php
                                        // Ambil nama file tanpa path
                                        $fullFileName = basename($pelatihan->surat_tugas);

                                        // Hilangkan tanggal di depan nama file
                                        $cleanFileName = preg_replace('/^\d{10}_/', '', $fullFileName);
                                    @endphp

                                    <a href="{{ url($pelatihan->surat_tugas) }}" target="_blank"
                                        download>
                                        {{ $cleanFileName }}
                                    </a>
                                </small>
                            @endif
                            <input type="file" name="surat_tugas" id="surat_tugas" class="form-control">
                            <small class="form-text text-muted">Abaikan jika tidak ingin mengubah file surat tugas</small>
                            <small id="error-surat_tugas" class="error-text form-text text-danger"></small>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn"  style="color: #EF5428; background-color: white; border-color: #EF5428;">Batal</button>
                    <button type="submit" class="btn" style="color: white; background-color: #EF5428; border-color: #EF5428;">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            var isAdmin = {{ Auth::user()->id_level == 1 ? 'true' : 'false' }};
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
                    'bukti_pelatihan[]': {
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
                    surat_tugas: {
                        required: false,
                    },
                },
                submitHandler: function(form) {
                    var formData = new FormData(document.getElementById('form-edit'));
                    console.log('Files in FormData:', formData.get(
                        'bukti_pelatihan[]')); // Jika menggunakan array
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
            $("#id_matakuliah, #id_bidang_minat, #user_id").select2({
                dropdownAutoWidth: true,
                theme: "classic",
                width: '100%'
            });
        });
    </script>
@endempty
