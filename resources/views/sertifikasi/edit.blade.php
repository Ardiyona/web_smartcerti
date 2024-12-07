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
    <form action="{{ url('/sertifikasi/' . $sertifikasi->id_sertifikasi . '/update') }}" method="POST" id="form-edit"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Sertifikasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Vendor</label>
                        <select name="id_vendor_sertifikasi" id="id_vendor_sertifikasi" class="form-control" required>
                            <option value="">- Pilih Vendor -</option>
                            @foreach ($vendorSertifikasi as $l)
                                <option
                                    {{ $l->id_vendor_sertifikasi == $sertifikasi->id_vendor_sertifikasi ? 'selected' : '' }}
                                    value="{{ $l->id_vendor_sertifikasi }}">{{ $l->nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_vendor_sertifikasi" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Jenis Bidang</label>
                        <select name="id_jenis_sertifikasi" id="id_jenis_sertifikasi" class="form-control" required>
                            <option value="">- Pilih Jenis Bidang -</option>
                            @foreach ($jenisSertifikasi as $l)
                                <option
                                    {{ $l->id_jenis_sertifikasi == $sertifikasi->id_jenis_sertifikasi ? 'selected' : '' }}
                                    value="{{ $l->id_jenis_sertifikasi }}">{{ $l->nama_jenis_sertifikasi }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_jenis_sertifikasi" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Tahun Periode</label>
                        <select name="id_periode" id="id_periode" class="form-control" required>
                            <option value="">- Pilih Tahun Periode -</option>
                            @foreach ($periode as $l)
                                <option {{ $l->id_periode == $sertifikasi->id_periode ? 'selected' : '' }}
                                    value="{{ $l->id_periode }}">{{ $l->tahun_periode }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_periode" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Nama Sertifikasi -->
                    <div class="form-group">
                        <label>Nama Sertifikasi</label>
                        <input value ="{{ $sertifikasi->nama_sertifikasi }}" type="text" name="nama_sertifikasi"
                            id="nama_sertifikasi" class="form-control" required>
                        <small id="error-nama_sertifikasi" class="error-text form-text text-danger"></small>
                    </div>

                    @if (Auth::user()->id_level != 1)
                        <!-- No Sertifikasi -->
                        <div class="form-group">
                            <label>No Sertifikasi</label>

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

                            <input value="{{ $userNoSertifikasi }}" type="text" name="no_sertifikasi"
                                id="no_sertifikasi" class="form-control" required>
                            <small id="error-no_sertifikasi" class="error-text form-text text-danger"></small>
                        </div>
                    @endif


                    <!-- Jenis -->
                    <div class="form-group">
                        <label>Jenis</label>
                        <select value ="{{ $sertifikasi->jenis }}" name="jenis" id="jenis" class="form-control"
                            required>
                            <option value="Profesi">Profesi</option>
                            <option value="Keahlian">Keahlian</option>
                        </select>
                        <small id="error-jenis" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Tanggal -->
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input value ="{{ $sertifikasi->tanggal }}" type="date" name="tanggal" id="tanggal"
                            class="form-control" required>
                        <small id="error-tanggal" class="error-text form-text text-danger"></small>
                    </div>
                    @if (Auth::user()->id_level != 1)
                        <!-- Bukti Sertifikasi -->
                        <div class="form-group">
                            <label>Bukti Sertifikasi</label>

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
                                <small class="form-text">
                                    File saat ini:
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
                                </small>
                            @endif

                            {{-- Input File hanya untuk user yang sedang login --}}
                            <input type="file" name="bukti_sertifikasi" id="bukti_sertifikasi" class="form-control">
                            <small class="form-text text-muted">Abaikan jika tidak ingin mengubah file bukti
                                sertifikasi</small>
                            <small id="error-bukti_sertifikasi" class="error-text form-text text-danger"></small>
                        </div>
                    @endif

                    <!-- Masa Berlaku -->
                    <div class="form-group">
                        <label>Masa Berlaku</label>
                        <input value ="{{ $sertifikasi->masa_berlaku }}" type="date" name="masa_berlaku"
                            id="masa_berlaku" class="form-control" required>
                        <small id="error-masa_berlaku" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Kuota Peserta -->
                    <div class="form-group">
                        <label>Kuota Peserta</label>
                        <input value ="{{ $sertifikasi->kuota_peserta }}" type="number" name="kuota_peserta"
                            id="kuota_peserta" class="form-control" readonly>
                        <small id="error-kuota_peserta" class="error-text form-text text-danger"></small>
                    </div>

                    <!-- Biaya -->
                    <div class="form-group">
                        <label>Biaya</label>
                        <input value ="{{ $sertifikasi->biaya }}" type="number" name="biaya" id="biaya"
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
                                        {{ $sertifikasi->detail_peserta_sertifikasi->contains($l->user_id) ? 'selected' : '' }}
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
                                    {{ $sertifikasi->bidang_minat_sertifikasi->contains($item->id_bidang_minat) ? 'selected' : '' }}
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
                                    {{ $sertifikasi->mata_kuliah_sertifikasi->contains($item->id_matakuliah) ? 'selected' : '' }}
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
            var isAdmin = {{ Auth::user()->id_level == 1 ? 'true' : 'false' }};
            $("#form-edit").validate({
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
                    no_sertifikasi: {
                        required: function() {
                            return !isAdmin;
                        },
                        minlength: 3,
                        maxlength: 255
                    },
                    jenis: {
                        required: true,
                    },
                    tanggal: {
                        required: true,
                    },
                    'bukti_sertifikasi[]': {
                        required: false,
                        extension: "pdf"
                    },
                    masa_berlaku: {
                        required: true,
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
            $("#id_matakuliah, #id_bidang_minat, #user_id").select2({
                dropdownAutoWidth: true,
                theme: "classic",
                width: '100%' 
            });
        });
    </script>
@endempty
