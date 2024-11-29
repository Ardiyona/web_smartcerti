@extends('layouts.template')

@section('title')| Vendor Sertifikasi @endsection

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
            <button onclick="modalAction(`{{ url('/vendorsertifikasi/import') }}`)" class="btn btn-success" 
            style="background-color: #EF5428; border-color: #EF5428;"><i class="fas fa-file-import"></i> Import</button>
            <button onclick="modalAction(`{{ url('/vendorsertifikasi/create') }}`)" class="btn btn-success" 
            style="background-color: #EF5428; border-color: #EF5428;"><i class="fas fa-plus"></i> Tambah</button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_vendor_sertifikasi">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Kota</th>
                        <th>Nomor Telepon</th>
                        <th>Alamat Website</th>
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
    </style>
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataVendorSertifikasi;
        $(document).ready(function() {
            dataVendorSertifikasi = $('#table_vendor_sertifikasi').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('vendorsertifikasi/list') }}",
                    "dataType" : "json",
                    "type": "POST",
                },
                columns: [
                    {
                        // nomor urut dari laravel datatable addIndexColumn()
                        data: "DT_RowIndex",
                        ClassName: "text-center",
                        width: "5%",
                        orderable: false,
                        searchable: false
                    },{
                        data: "nama",
                        ClassName: "",
                        // orderable: true, jika ingin kolom ini bisa diurutkan
                        orderable: true,
                        // searchable: true, jika ingin kolom ini bisa dicari
                        searchable: true
                    },{
                        data: "alamat",
                        ClassName: "",
                        width: "25%",
                        orderable: true,
                        searchable: true
                    },{
                        data: "kota",
                        ClassName: "",
                        width: "10%",
                        orderable: true,
                        searchable: true
                    },{
                        data: "no_telp",
                        ClassName: "",
                        width: "15%",
                        orderable: true,
                        searchable: true
                    },{
                        data: "alamat_web",
                        ClassName: "",
                        width: "20%",
                        orderable: true,
                        searchable: true
                    },{
                        data: "aksi",
                        ClassName: "",
                        width: "25%",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush