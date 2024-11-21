@extends('layouts.template')

@section('title')
    | pelatihan
@endsection

@section('content')
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
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
                        <th>Nama Vendor</th>
                        <th>Jenis Pelatihan</th>
                        <th>Periode</th>
                        <th>Nama Pelatihan</th>
                        <th>No pelatihan</th>
                        <th>Lokasi</th>
                        <th>Level Pelatihan</th>
                        <th>Tanggal</th>
                        <th>Tag Bidang Minat</th>
                        <th>Tag Mata Kuliah</th>
                        <th>Nama Peserta</th>
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
    </style>
@endpush
@push('js')
    <script>
        var datapelatihan;
        $(document).ready(function() {
            var columns = [
                { data: "DT_RowIndex", className: "text-center", width: "4%", orderable: false, searchable: false },
                { data: "vendor_pelatihan.nama", className: "", width: "9%", orderable: false, searchable: true },
                { data: "jenis_pelatihan.nama_jenis_pelatihan", className: "", width: "9%", orderable: false, searchable: true },
                { data: "periode.tahun_periode", className: "", width: "6%", orderable: false, searchable: false },
                { data: "nama_pelatihan", className: "", width: "9%", orderable: true, searchable: true },
                { data: "no_pelatihan", className: "", width: "6%", orderable: false, searchable: true },
                { data: "lokasi", className: "", width: "6%", orderable: false, searchable: true },
                { data: "level_pelatihan", className: "", width: "6%", orderable: false, searchable: true },
                { data: "tanggal", className: "", width: "8%", orderable: false, searchable: false },
                { data: "bidang_minat", render: function(data) { return data ? data : '-'; }, className: "", width: "10%", orderable: false, searchable: false },
                { data: "mata_kuliah", render: function(data) { return data ? data : '-'; }, className: "", width: "10%", orderable: false, searchable: false },
                { data: "peserta_pelatihan", render: function(data) { return data ? data : '-'; }, className: "", width: "10%", orderable: false, searchable: false }
            ];

            datapelatihan = $('#table_pelatihan').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('pimpinanpelatihandosen/list') }}",
                    dataType: "json",
                    type: "POST",
                },
                columns: columns
            });
        });
    </script>
@endpush
