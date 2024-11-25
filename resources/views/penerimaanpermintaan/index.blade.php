@extends('layouts.template')

@section('title')
    | Penerimaan Permintaan
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_penerimaan_permintaan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Vendor</th>
                        <th>Jenis Bidang</th>
                        <th>Periode</th>
                        <th>Nama Program</th>
                        <th>Kategori</th>
                        <th>Jenis/Level</th>
                        <th>Tanggal</th>
                        <th>Peserta</th>
                        <th>Biaya</th>
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
    </style>
@endpush
@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
        var dataSertifikasi;
        $(document).ready(function() {
            dataSertifikasi = $('#table_penerimaan_permintaan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('penerimaanpermintaan/list') }}",
                    type: "POST",
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
                        data: "vendor_sertifikasi.nama",
                        className: "",
                        width: "9%",
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: null,
                        className: "",
                        width: "6%",
                        orderable: false,
                        searchable: true,
                        render: function(data, type, row) {
                            if (row.kategori === 'sertifikasi') {
                                return row.jenis_sertifikasi.nama_jenis_sertifikasi;
                            } else if (row.kategori === 'pelatihan') {
                                return row.jenis_sertifikasi.nama_jenis_pelatihan;
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        data: "periode.tahun_periode",
                        className: "",
                        width: "6%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nama_program",
                        className: "",
                        width: "9%",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kategori", // Sertifikasi atau Pelatihan
                        render: function(data) {
                            return data ? data : '-';
                        },
                        className: "",
                        width: "8%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "jenis_level",
                        className: "",
                        width: "6%",
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: "tanggal",
                        className: "",
                        width: "8%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "peserta",
                        render: function(data, type, row) {
                            return row.peserta ? row.peserta : '-';
                        },
                        className: "",
                        width: "10%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "biaya",
                        className: "",
                        width: "8%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "aksi",
                        className: "",
                        width: "9%",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
