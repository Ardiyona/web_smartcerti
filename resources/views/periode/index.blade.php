{{-- @extends('layouts.template')
@section('content')

<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Periode Management</h3>
        <div class="card-tools">
            <a href="{{ url('/periode/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF</a>
            <a href="{{ url('/periode/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
            <button onclick="modalAction(`{{ url('/periode/create') }}`)" class="btn btn-success"
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
                        <select class="form-control" id="tahun_periode" name="tahun_periode">
                            <option value="">- Semua -</option>
                            @foreach ($tahun_periode_list as $tahun)
                                <option value="{{ $tahun->tahun_periode }}">{{ $tahun->tahun_periode }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Tahun Periode</small>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="table-periode">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Berakhir</th>
                    <th>Tahun Periode</th>
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
@endpush
@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
    
    var dataPeriode;
    $(document).ready(function() {
        dataPeriode = $('#table-periode').DataTable({
            serverSide: true,
            ajax: {
                "url": "{{ url('periode/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.tahun_periode = $('#tahun_periode').val();
                }
            },
            columns: [
                { data: "id_periode", className: "text-center", orderable: true, searchable: true },
                { data: "tanggal_mulai", className: "", orderable: true, searchable: true },
                { data: "tanggal_berakhir", className: "", orderable: true, searchable: true },
                { data: "tahun_periode", className: "", orderable: true, searchable: true },
                { data: "aksi", className: "", orderable: false, searchable: false }
            ]
        });

        $('#tahun_periode').on('change', function() {
            dataPeriode.ajax.reload();
        });
    });
</script>
@endpush --}}


@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Periode Management</h3>
            <div class="card-tools">
                <a href="{{ url('/periode/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export
                    PDF</a>
                <a href="{{ url('/periode/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export
                    Excel</a>
                <button onclick="modalAction(`{{ url('/periode/create') }}`)" class="btn btn-success"
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
                            <select class="form-control" id="tahun_periode" name="tahun_periode">
                                <option value="">- Semua -</option>
                                @foreach ($tahun_periode_list as $tahun)
                                    <option value="{{ $tahun->tahun_periode }}">{{ $tahun->tahun_periode }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Tahun Periode</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table responsive table-bordered table-striped table-hover table-sm" id="table-periode">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tahun Periode</th>
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

        var dataPeriode;
        $(document).ready(function() {
            dataPeriode = $('#table-periode').DataTable({
                serverSide: true,
                responsive: true,
                ajax: {
                    "url": "{{ url('periode/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.tahun_periode = $('#tahun_periode').val();
                    }
                },
                columns: [{
                        data: "id_periode",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "tahun_periode",
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

            $('#tahun_periode').on('change', function() {
                dataPeriode.ajax.reload();
            });
        });
    </script>
@endpush
