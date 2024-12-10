@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Program Studi Management</h3>
            <div class="card-tools">
                <a href="{{ url('/prodi/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF</a>
                <button onclick="modalAction(`{{ url('/prodi/create') }}`)" class="btn btn-success"
                    style="background-color: #EF5428; border-color: #EF5428;"> <i class="fas fa-plus"></i> Tambah</button>
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
                        <label class="col-1 control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <input type="text" class="form-control" id="filter_nama_prodi" placeholder="Cari Nama Prodi">
                            <small class="form-text text-muted">Nama Program Studi</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table responsive table-bordered table-striped table-hover table-sm" id="table-prodi">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Prodi</th>
                        <th>Nama Prodi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
    <style>
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

        var dataProdi;
        $(document).ready(function() {
            dataProdi = $('#table-prodi').DataTable({
                serverSide: true,
                responsive: true,
                ajax: {
                    "url": "{{ url('prodi/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.nama_prodi = $('#filter_nama_prodi').val();
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex",
                        className: "text-center",
                        width: "4%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "kode_prodi",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "nama_prodi",
                        className: "",
                        orderable: true,
                        searchable: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#filter_nama_prodi').on('keyup', function() {
                dataProdi.ajax.reload();
            });
        });
    </script>
@endpush
