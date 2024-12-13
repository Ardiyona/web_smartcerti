@extends('layouts.template')
@section('title')
    | Jenis Pelatihan
@endsection

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction(`{{ url('/jenispelatihan/import') }}`)" class="btn btn-info"
                    style="background-color: #EF5428; border-color: #EF5428;"> <i class="fas fa-file-import"></i>
                    Import</button>
                <button onclick="modalAction(`{{ url('/jenispelatihan/create') }}`)" class="btn btn-success"
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
            
            <table class="table responsive table-bordered table-striped table-hover table-sm" id="table-jenis-pelatihan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Jenis Pelatihan</th>
                        <th>Kode Pelatihan</th>
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
        var dataJenisPelatihan;
        $(document).ready(function() {
            dataJenisPelatihan = $('#table-jenis-pelatihan').DataTable({
                serverSide: true,
                responsive: true,
                ajax: {
                    "url": "{{ url('jenispelatihan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.id_jenis_pelatihan = $('#id_jenis_pelatihan').val();
                        d.kode_pelatihan = $('#kode_pelatihan').val();
                    }
                },
                columns: [{
                        data: "id_jenis_pelatihan",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "nama_jenis_pelatihan",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kode_pelatihan",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#id_jenis_pelatihan').on('change', function() {
                dataJenisPelatihan.ajax.reload();
            });
            $('#kode_pelatihan').on('change', function() {
                dataJenisPelatihan.ajax.reload();
            });
        });
    </script>
@endpush
