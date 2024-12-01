@extends('layouts.template')

@section('title')
    | Penerimaan Permintaan
@endsection

@section('content')
    <!-- Tombol Pilihan untuk Kategori -->
    <div class="mb-3">
        <button id="btn_sertifikasi" class="btn btn-primary" onclick="tampilkanTabel('sertifikasi')">Sertifikasi</button>
        <button id="btn_pelatihan" class="btn btn-secondary" onclick="tampilkanTabel('pelatihan')">Pelatihan</button>
    </div>

    <!-- Tabel Sertifikasi -->
    <div class="container-fluid">
        <div class="card card-outline card-primary" id="sertifikasi_card" style="display: none;">
            <div class="card-header">
                <h3 class="card-title">{{ $page->title }} - Sertifikasi</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <table class="table table-bordered table-striped table-hover table-sm" id="table_sertifikasi">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kategori</th>
                            <th>Nama Vendor</th>
                            <th>Jenis Bidang</th>
                            <th>Periode</th>
                            <th>Nama Program</th>
                            <th>Jenis/Level</th>
                            <th>Tanggal</th>
                            <th>Peserta</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Tabel Pelatihan -->
    <div class="card card-outline card-primary" id="pelatihan_card" style="display: none;">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }} - Pelatihan</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover table-sm" id="table_pelatihan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori</th>
                        <th>Nama Vendor</th>
                        <th>Jenis Bidang</th>
                        <th>Periode</th>
                        <th>Nama Program</th>
                        <th>Jenis/Level</th>
                        <th>Tanggal</th>
                        <th>Peserta</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
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

    </style>
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        function tampilkanTabel(kategori) {
            // Sembunyikan kedua tabel terlebih dahulu
            $('#sertifikasi_card').hide();
            $('#pelatihan_card').hide();

            // Sembunyikan tombol aktif sebelumnya
            $('#btn_sertifikasi').removeClass('btn-primary').addClass('btn-secondary');
            $('#btn_pelatihan').removeClass('btn-primary').addClass('btn-secondary');

            // Tampilkan tabel sesuai kategori yang dipilih
            if (kategori === 'sertifikasi') {
                $('#sertifikasi_card').show();
                $('#btn_sertifikasi').removeClass('btn-secondary').addClass('btn-primary');
            } else if (kategori === 'pelatihan') {
                $('#pelatihan_card').show();
                $('#btn_pelatihan').removeClass('btn-secondary').addClass('btn-primary');
            }
        }

        $(document).ready(function() {
            // Inisialisasi data untuk Sertifikasi
            var dataSertifikasi = $('#table_sertifikasi').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('penerimaanpermintaan/listSertifikasi') }}",
                    type: "POST",
                    data: function(d) {
                        d.kategori = 'sertifikasi'; // Menampilkan Sertifikasi
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        width: "4%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "kategori",
                        render: function(data) {
                            return data ? data : '-';
                        },
                        className: "",
                        width: "8%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "vendor_sertifikasi.nama",
                        className: "",
                        width: "6%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "jenis_sertifikasi.nama_jenis_sertifikasi",
                        className: "",
                        width: "6%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "periode.tahun_periode",
                        className: "",
                        width: "6%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nama_sertifikasi",
                        className: "",
                        width: "9%",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "jenis",
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
                        data: "status_sertifikasi",
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

            // Inisialisasi data untuk Pelatihan
            var dataPelatihan = $('#table_pelatihan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('penerimaanpermintaan/listPelatihan') }}",
                    type: "POST",
                    data: function(d) {
                        d.kategori = 'pelatihan'; // Menampilkan Pelatihan
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        width: "4%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "kategori",
                        render: function(data) {
                            return data ? data : '-';
                        },
                        className: "",
                        width: "8%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "vendor_pelatihan.nama",
                        className: "",
                        width: "6%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "jenis_pelatihan.nama_jenis_pelatihan",
                        className: "",
                        width: "6%",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "periode.tahun_periode",
                        className: "",
                        width: "6%",
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
                        data: "level_pelatihan",
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
                        data: "status_pelatihan",
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
            // Menampilkan tabel Sertifikasi secara default
            tampilkanTabel('sertifikasi');
        });
    </script>
@endpush
