@extends('layouts.template')

@section('title')
    | User
@endsection

@section('content')
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction(`{{ url('/user/import') }}`)" class="btn btn-info"
                    style="background-color: #EF5428; border-color: #EF5428;"> <i class="fas fa-file-import"></i>
                    Import</button>
                <button onclick="modalAction(`{{ url('/user/create') }}`)" class="btn btn-success"
                    style="background-color: #EF5428; border-color: #EF5428;"> <i class="fas fa-plus"> </i> Tambah</button>


            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter: </label>
                        <div class="col-3">
                            <select class="form-control" id="id_level" name="id_level" required>
                                <option value="">- Semua -</option>
                                @foreach ($level as $item)
                                    <option value="{{ $item->id_level }}">{{ $item->nama_level }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table responsive table-bordered table-striped table-hover table-sm" id="table_user">
                <style>
                    table.dataTable td.text-center {
                        vertical-align: middle;
                        /* Memastikan elemen di dalam sel sejajar vertikal */
                        text-align: center;
                        /* Memastikan elemen sejajar horizontal */
                    }

                    table.dataTable img {
                        display: inline-block;
                        /* Pastikan gambar dianggap elemen blok sejajar */
                    }
                </style>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Level Pengguna</th>
                        <th>Avatar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@push('css')
    <style>
        .card.card-outline.card-primary {
            border-color: #375E97 !important;
        }

        .table {
            width: 100% !important;
        }
    </style>
@endpush
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var dataUser;
        $(document).ready(function() {
            dataUser = $('#table_user').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                responsive: true,
                ajax: {
                    "url": "{{ url('user/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.level_id = $('#id_level').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        width: "4%",
                        orderable: false,
                        searchable: false
                    }, {
                        data: "username",
                        className: "",
                        width: "10%",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "nama_lengkap",
                        className: "",
                        width: "30%",
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: "level.nama_level",
                        className: "",
                        width: "15%",
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: "avatar", // Kolom avatar dengan render gambar
                        className: "text-center",
                        width: "15%",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            // Tampilkan avatar jika ada, atau gambar default jika tidak ada
                            if (data) {
                                return `<img src="${data}" class=" rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">`;
                            } else {
                                return `<img src="/img/profile.png" class=" rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">`;
                            }
                        }
                    },
                    {
                        data: "aksi",
                        className: "",
                        width: "14%",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            $('#table-user_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) { // enter key
                    tableUser.search(this.value).draw();
                }
            });
            $('.filter_kategori').change(function() {
                tableUser.draw();
            });
        });
    </script>
@endpush
