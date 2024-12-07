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
                @if (Auth::user()->id_level == 1)
                    <button onclick="modalAction(`{{ url('/pelatihan/create_rekomendasi') }}`)" class="btn btn-success"
                        style="background-color: #EF5428; border-color: #EF5428;">Tambah Pengajuan</button>
                @endif
                <button onclick="modalAction(`{{ url('/pelatihan/create') }}`)" class="btn btn-success"
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
            <table class="table responsive table-bordered table-striped table-hover table-sm" id="table_pelatihan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelatihan</th>
                        @if (Auth::user()->id_level != 1)
                            <th>Nama Vendor</th>
                        @endif
                        <th>Jenis Pelatihan</th>
                        <th>Level Pelatihan</th>
                        <th>Lokasi</th>
                        <th>Periode</th>
                        
                        
                        
                        @if (Auth::user()->id_level != 1)
                            <th>Tanggal</th>
                        @endif
                        @if (Auth::user()->id_level == 1)
                            <th>Nama Peserta</th>
                            <th>Status</th>
                        @endif
                        <th>Aksi</th>
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
        var dataPelatihan;
        $(document).ready(function() {
            // Cek apakah user adalah admin (id_level = 1)
            var isAdmin = {{ Auth::user()->id_level == 1 ? 'true' : 'false' }};

            var columns = [{
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
                    data: "lokasi",
                    className: "",
                    width: "6%",
                    orderable: false,
                    searchable: true
                },
                {
                    data: "periode.tahun_periode",
                    className: "",
                    width: "1%",
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
            ];

            // Tambahkan kolom "Nama Peserta" jika user adalah admin
            if (isAdmin) {
                columns.splice(6, 0, {
                    data: "peserta_pelatihan",
                    render: function(data, type, row) {
                        return row.peserta_pelatihan ? row.peserta_pelatihan : '-';
                    },
                    className: "",
                    width: "10%",
                    orderable: false,
                    searchable: false
                });
                columns.splice(7, 0, {
                    data: "status_pelatihan",
                    render: function(data, type, row) {
                        if (data) {
                            let badgeClass;
                            // Tentukan kelas berdasarkan nilai data
                            if (data.toLowerCase() === 'terima') {
                                badgeClass = 'bg-success';
                            } else if (data.toLowerCase() === 'menunggu') {
                                badgeClass = 'bg-warning';
                            } else {
                                badgeClass = 'bg-danger';
                            }
                            return `<span class="badge ${badgeClass}">${data}</span>`;
                        }
                        return '-';
                    },
                    className: "",
                    width: "1%",
                    orderable: false,
                    searchable: false
                });
            }
            if (!isAdmin) {
                columns.splice(6, 0, {
                    data: "tanggal",
                    className: "",
                    width: "3%",
                    orderable: true, // Set true jika ingin sorting berdasarkan tanggal
                    searchable: false
                });
                columns.splice(1, 0, {
                    data: "vendor_pelatihan.nama",
                    className: "",
                    width: "9%",
                    orderable: false,
                    searchable: true
                });
            }

            dataPelatihan = $('#table_pelatihan').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('pelatihan/list') }}",
                    dataType: "json",
                    type: "POST",
                },
                columns: columns,
                responsive: true
            });

        });
    </script>
@endpush
