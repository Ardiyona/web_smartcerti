@empty($user)
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
                <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/user/' . $user->user_id . '/update') }}" method="POST" id="form-edit"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Level Pengguna</label>
                        <select name="id_level" id="id_level" class="form-control" required>
                            <option value="">- Pilih Level -</option>
                            @foreach ($level as $l)
                                <option {{ $l->id_level == $user->id_level ? 'selected' : '' }} value="{{ $l->id_level }}">
                                    {{ $l->nama_level }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_level" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input value="{{ $user->username }}" type="text" name="username" id="username"
                            class="form-control" required>
                        <small id="error-username" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input value="{{ $user->nama_lengkap }}" type="text" name="nama_lengkap" id="nama_lengkap"
                            class="form-control" required>
                        <small id="error-nama_lengkap" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>No Telepon</label>
                        <input value="{{ $user->no_telp }}" type="text" name="no_telp" id="no_telp"
                            class="form-control" required>
                        <small id="error-no_telp" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input value="{{ $user->email }}" type="text" name="email" id="email"
                            class="form-control" required>
                        <small id="error-email" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>NIP</label>
                        <input value="{{ $user->nip }}" type="text" name="nip" id="nip"
                            class="form-control" required>
                        <small id="error-nip" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select value ="{{ $user->jenis_kelamin }}" name="jenis_kelamin" id="jenis_kelamin" class="form-control"
                            required>
                            <option {{ ($user->jenis_kelamin == 'Laki-Laki')? 'selected' : '' }} value="Laki-Laki" >Laki-Laki</option>
                            <option {{ ($user->jenis_kelamin == 'Perempuan')? 'selected' : '' }} value="Perempuan">Perempuan</option>
                        </select>
                        <small id="error-jenis_kelamin" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input value="" type="password" name="password" id="password" class="form-control">
                        <small class="form-text text-muted">Abaikan jika tidak ingin ubah
                            password</small>
                        <small id="error-password" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="id_bidang_minat">
                            Tag Bidang Minat
                        </label>
                        <select multiple="multiple" name="id_bidang_minat[]" id="id_bidang_minat"
                            class="js-example-basic-multiple js-states form-control form-control">
                            @foreach ($bidangMinat as $item)
                                <option
                                    {{ $user->detail_daftar_user_bidang_minat->contains($item->id_bidang_minat) ? 'selected' : '' }}
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
                                    {{ $user->detail_daftar_user_matakuliah->contains($item->id_matakuliah) ? 'selected' : '' }}
                                    value="{{ $item->id_matakuliah }}">{{ $item->nama_matakuliah }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_matakuliah" class="error-text form-text text-danger"></small>
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
            $("#form-edit").validate({
                rules: {
                    id_level: {
                        required: true,
                        number: true
                    },
                    username: {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    nama_lengkap: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    no_telp: {
                        required: true,
                        minlength: 11,
                        maxlength: 15
                    },
                    email: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    nip: {
                        required: true,
                        minlength: 18,
                        maxlength: 18
                    },
                    jenis_kelamin: {
                        required: true,
                    },
                    password: {
                        minlength: 6,
                        maxlength: 20
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
                                dataUser.ajax.reload();
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
                theme: "classic",
                width: '100%' 
            });
        });
    </script>
@endempty
