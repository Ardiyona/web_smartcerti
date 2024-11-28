@extends('layouts.template')

@section('title')
    | Pelatihan
@endsection

@section('content')
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_pelatihan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelatihan</th>
                        <th>Nama Vendor</th>
                        <th>Jenis Bidang</th>
                        <th>Level</th>
                        <th>Periode</th>
                        <th>Tanggal</th>
                        <th>Tag Bidang Minat</th>
                        <th>Tag Mata Kuliah</th>
                        <th>Nama Peserta</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake modal-dialog modal-xl" tabindex="-1" role="dialog"
        data-backdrop="static" data-keyboard="false" data-width="75%"
        style="z-index: 1050; display: none; padding-left: 0px;" aria-modal="true"></div>
@endsection

@push('css')
    <style>
        .card.card-outline.card-primary {
            border-color: #375E97 !important;
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
        
        $(document).ready(function() {
            var columns = [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    width: "4%",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "nama_pelatihan",
                    className: "",
                    width: "9%",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "vendor_pelatihan.nama",
                    className: "",
                    width: "9%",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "jenis_pelatihan.nama_jenis_pelatihan",
                    className: "",
                    width: "9%",
                    orderable: false,
                    searchable: true,
                },
                {
                    data: "level_pelatihan",
                    className: "",
                    width: "6%",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "periode.tahun_periode",
                    className: "",
                    width: "6%",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "tanggal",
                    className: "",
                    width: "8%",
                    orderable: true,
                    searchable: false
                },
                {
                    data: "bidang_minat",
                    render: function(data, type, row) {
                        return row.bidang_minat ? row.bidang_minat : '-';
                    },
                    className: "",
                    width: "10%",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "mata_kuliah",
                    render: function(data, type, row) {
                        return row.mata_kuliah ? row.mata_kuliah : '-';
                    },
                    className: "",
                    width: "10%",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "peserta_pelatihan",
                    render: function(data, type, row) {
                        return row.peserta_pelatihan ? row.peserta_pelatihan : '-';
                    },
                    className: "",
                    width: "10%",
                    orderable: false,
                    searchable: false
                },
                
            ];

            dataPelatihan = $('#table_pelatihan').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('pelatihanuser/list') }}",
                    dataType: "json",
                    type: "POST",
                },
                columns: columns
            });
        });
    </script>
@endpush
