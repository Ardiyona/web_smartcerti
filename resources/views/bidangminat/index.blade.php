@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction(`{{ url('/bidangminat/import') }}`)" class="btn btn-info"
                    style="background-color: #EF5428; border-color: #EF5428;"> <i class="fas fa-file-import"></i>
                    Import</button>
                <button onclick="modalAction(`{{ url('/bidangminat/create') }}`)" class="btn btn-success"
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
           
            <table class="table responsive table-bordered table-striped table-hover table-sm" id="table-bidang-minat">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Bidang Minat</th>
                        <th>Kode Bidang Minat</th>
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
        var dataBidangMinat;
        $(document).ready(function() {
            dataBidangMinat = $('#table-bidang-minat').DataTable({
                serverSide: true,
                responsive: true,
                ajax: {
                    "url": "{{ url('bidangminat/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.nama_bidang_minat = $('#nama_bidang_minat').val();
                        d.kode_bidang_minat = $('#kode_bidang_minat').val();
                    }
                },
                columns: [{
                        data: "id_bidang_minat",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "nama_bidang_minat",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kode_bidang_minat",
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

            $('#nama_bidang_minat').on('change', function() {
                dataBidangMinat.ajax.reload();
            });
            $('#kode_bidang_minat').on('change', function() {
                dataBidangMinat.ajax.reload();
            });
        });
    </script>
@endpush
