{{-- 


@extends('layouts.template')
@section('title')
    | Kompetensi Prodi
@endsection

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modalAction(`{{ url('/kompetensiprodi/create') }}`)" class="btn btn-success"
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
                            <select class="form-control" id="prodi_filter" name="prodi_filter" required>
                                <option value="">- Semua -</option>
                                @foreach ($kompetensi as $prodi)
                                    <option value="{{ $prodi->prodi }}">{{ $prodi->prodi }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Filter berdasarkan Prodi</small>
                        </div>
                        <div class="col-3">
                            <select class="form-control" id="bidang_filter" name="bidang_filter" required>
                                <option value="">- Semua -</option>
                                @foreach ($kompetensi as $prodi)
                                    <option value="{{ $prodi->bidang_terkait }}">{{ $prodi->bidang_terkait }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Filter berdasarkan Bidang Terkait</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table responsive table-bordered table-striped table-hover table-sm" id="table-kompetensi-prodi">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Prodi</th>
                        <th>Bidang Terkait</th>
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

        var dataKompetensiProdi;
        $(document).ready(function() {
            dataKompetensiProdi = $('#table-kompetensi-prodi').DataTable({
                serverSide: true,
                responsive: true,
                ajax: {
                    "url": "{{ url('kompetensiprodi/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.prodi_filter = $('#prodi_filter').val();
                        d.bidang_filter = $('#bidang_filter').val();
                    }
                },
                columns: [{
                        data: "id_kompetensi",
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "prodi",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "bidang_terkait",
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

            // $('#prodi_filter').on('change', function() {
            //     dataKompetensiProdi.ajax.reload();
            // });
            // $('#bidang_filter').on('change', function() {
            //     dataKompetensiProdi.ajax.reload();
            // });
        });
    </script>
@endpush --}}

@extends('layouts.template')
@section('title')
    | Kompetensi Prodi
@endsection

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Manajemen Kompetensi Prodi</h3>
            <div class="card-tools">
                @if (Auth::user()->id_level == 1)
                <button onclick="modalAction(`{{ url('/kompetensiprodi/create') }}`)" class="btn btn-success"
                    style="background-color: #EF5428; border-color: #EF5428;">
                    <i class="fas fa-plus"></i> Tambah
                </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            {{-- <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-2 control-label col-form-label">Filter Program Studi:</label>
                        <div class="col-4">
                            <select class="form-control" id="prodi_filter" name="prodi_filter">
                                <option value="">- Semua Program Studi -</option>
                                @foreach ($kompetensi as $prodi)
                                    <option value="{{ $prodi->id_prodi }}">{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="col-2 control-label col-form-label">Filter Bidang Terkait:</label>
                        <div class="col-4">
                            <input type="text" id="bidang_filter" name="bidang_filter" class="form-control"
                                placeholder="Cari bidang terkait">
                        </div>
                    </div>
                </div>
            </div> --}}
            <table class="table responsive table-bordered table-striped table-hover table-sm" id="table-kompetensi-prodi">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Prodi</th>
                        <th>Bidang Terkait</th>
                        @if(Auth::user()->id_level == 1)
                        <th>Aksi</th>
                        @endif
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

        var dataKompetensiProdi;
        $(document).ready(function() {
            dataKompetensiProdi = $('#table-kompetensi-prodi').DataTable({
                serverSide: true,
                responsive: true,
                ajax: {
                    "url": "{{ url('kompetensiprodi/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.prodi_filter = $('#prodi_filter').val();
                        d.bidang_filter = $('#bidang_filter').val();
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
                    { data: "prodi", className: "", orderable: true, searchable: true },
                    { data: "bidang_terkait", className: "", orderable: true, searchable: true },
                    @if(Auth::user()->id_level == 1)
                    { data: "aksi", className: "text-center", orderable: false, searchable: false }
                    @endif
                ]
            });

            $('#prodi_filter').on('change', function() {
                dataKompetensiProdi.ajax.reload();
            });

            $('#bidang_filter').on('input', function() {
                dataKompetensiProdi.ajax.reload();
            });
        });
    </script>
@endpush
