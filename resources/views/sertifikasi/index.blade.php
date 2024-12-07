@extends('layouts.template')

@section('title')
    | Sertifikasi
@endsection

@section('content')
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                @if (Auth::user()->id_level == 1)
                    <button onclick="modalAction(`{{ url('/sertifikasi/create_rekomendasi') }}`)" class="btn btn-success"
                        style="background-color: #EF5428; border-color: #EF5428;"> Tambah Pengajuan</button>
                @endif
                <button onclick="modalAction(`{{ url('/sertifikasi/create') }}`)" class="btn btn-success"
                    style="background-color: #EF5428; border-color: #EF5428;"><i class="fas fa-plus"></i> Tambah</button>
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
                    <label class="col-1 control-label col-form-label">Filter: </label>
                    <div class="col-3">
                        <select class="form-control" id="id_periode" name="id_periode" required>
                            <option value="">- Semua -</option>
                            @foreach ($periode as $item)
                                <option value="{{ $item->id_periode }}">{{ $item->nama_periode }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
            <table class="table responsive table-bordered table-striped table-hover table-sm" id="table_sertifikasi">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Sertifikasi</th>
                        @if (Auth::user()->id_level != 1)
                            <th>Nama Vendor</th>
                        @endif
                        <th>Jenis Bidang</th>
                        <th>Jenis</th>
                        <th>Periode</th>
                        @if (Auth::user()->id_level != 1)
                            <th>Tanggal</th>
                        @endif
                        <th>Masa Berlaku</th>
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
    <div id="myModal" class="modal fade animate shake modal-dialog modal-xl" tabindex="-1" role="dialog"
        data-backdrop="static" data-keyboard="false" data-width="75%"
        style="z-index: 1050; display: none; padding-left: 0px;" aria-modal="true"></div>
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
        var dataSertifikasi;
        $(document).ready(function() {
            // Cek apakah user adalah admin (id_level = 1)
            var isAdmin = {{ Auth::user()->id_level == 1 ? 'true' : 'false' }};

             var columns = [
            {
                data: "DT_RowIndex",
                className: "text-center",
                width: "4%",
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
                data: "jenis_sertifikasi.nama_jenis_sertifikasi",
                className: "",
                width: "9%",
                orderable: false,
                searchable: true,
            },
            {
                data: "jenis",
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
                data: "masa_berlaku",
                render: function(data, type, row) {
                    return row.masa_berlaku ? row.masa_berlaku : '-';
                },
                className: "",
                width: "3%",
                orderable: false,
                searchable: false
            },
            {
                data: "aksi",
                className: "",
                width: "12%",
                orderable: false,
                searchable: false
            }
        ];

        // Tambahkan kolom vendor dan tanggal untuk non-admin
        if (!isAdmin) {
            columns.splice(2, 0, {
                data: "vendor_sertifikasi.nama",
                className: "",
                width: "9%",
                orderable: false,
                searchable: true
            });
            columns.splice(6, 0, {
                data: "tanggal",
                className: "",
                width: "3%",
                orderable: true, // Set true jika ingin sorting berdasarkan tanggal
                searchable: false
            });
        }

        // Tambahkan kolom "Nama Peserta" dan "Status" jika user adalah admin
        if (isAdmin) {
            columns.splice(6, 0, {
                data: "peserta_sertifikasi",
                render: function(data, type, row) {
                    return row.peserta_sertifikasi ? row.peserta_sertifikasi : '-';
                },
                className: "",
                width: "10%",
                orderable: false,
                searchable: false
            });
            columns.splice(7, 0, {
                data: "status_sertifikasi",
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
                width: "3%",
                orderable: false,
                searchable: false
            });
        }

        // Inisialisasi DataTable dengan konfigurasi kolom baru
        dataSertifikasi = $('#table_sertifikasi').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('sertifikasi/list') }}",
                dataType: "json",
                type: "POST",
            },
            columns: columns,
            responsive: true
        });
    });
    </script>
@endpush
