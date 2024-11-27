@extends('layouts.template')
@section('content')
    
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a href="{{ url('/bidangminat/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF</a>
                <a href="{{ url('/bidangminat/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
                <a href="{{ url('/bidangminat/import_ajax') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Import</a>
                <button onclick="modalAction(`{{ url('/bidangminat/create') }}`)" class="btn btn-success" 
                style="background-color: #EF5428; border-color: #EF5428;">  <i class="fas fa-plus"></i> Tambah</button>
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
                            <select class="form-control" id="nama_bidang_minat" name="nama_bidang_minat" required>
                                <option value="">- Semua -</option>
                                @foreach ($bidang_minat as $item)
                                    <option value="{{ $item->nama_bidang_minat }}">{{ $item->nama_bidang_minat }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Nama Bidang Minat</small>
                        </div>
                        <div class="col-3">
                            <select class="form-control" id="kode_bidang_minat" name="kode_bidang_minat" required>
                                <option value="">- Semua -</option>
                                @foreach ($bidang_minat as $item)
                                    <option value="{{ $item->kode_bidang_minat }}">{{ $item->kode_bidang_minat }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kode Bidang Minat</small>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table-bidang-minat">
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
                ajax: {
                    "url": "{{ url('bidangminat/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.nama_bidang_minat = $('#nama_bidang_minat').val();
                        d.kode_bidang_minat = $('#kode_bidang_minat').val();
                    }
                },
                columns: [
                    {
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
