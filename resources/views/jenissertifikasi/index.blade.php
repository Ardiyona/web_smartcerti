@extends('layouts.template')

@section('title')
    | Jenis Sertifikasi
@endsection

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction(`{{ url('/jenissertifikasi/import') }}`)" class="btn btn-info"
                    style="background-color: #EF5428; border-color: #EF5428;"> <i class="fas fa-file-import"></i>
                    Import</button>
                <button onclick="modalAction(`{{ url('/jenissertifikasi/create') }}`)" class="btn btn-success"
                    style="background-color: #EF5428; border-color: #EF5428;"> <i class="fas fa-plus"></i> Tambah</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <table class="table responsive table-bordered table-striped table-hover table-sm" id="table_jenis_sertifikasi">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Jenis Sertifikasi</th>
                        <th>Nama Jenis Sertifikasi</th>
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

        var dataJenisSertifikasi;
        $(document).ready(function() {
            dataJenisSertifikasi = $('#table_jenis_sertifikasi').DataTable({
                serverSide: true,
                responsive: true,
                ajax: {
                    "url": "{{ url('jenissertifikasi/list') }}",
                    "dataType": "json",
                    "type": "POST",
                },
                columns: [{
                    data: "DT_RowIndex",
                    className: "text-center",
                    width: "5%",
                    orderable: false,
                    searchable: false
                }, {
                    data: "kode_jenis_sertifikasi",
                    className: "",
                    orderable: true,
                    searchable: true
                }, {
                    data: "nama_jenis_sertifikasi",
                    className: "",
                    width: "25%",
                    orderable: true,
                    searchable: true
                }, {
                    data: "aksi",
                    className: "",
                    width: "25%",
                    orderable: false,
                    searchable: false
                }]
            });
        });
    </script>
@endpush
