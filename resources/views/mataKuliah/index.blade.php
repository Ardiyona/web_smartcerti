@extends('layouts.template')

@section('title')| Mata Kuliah @endsection

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
            <button onclick="modalAction(`{{ url('/matakuliah/create') }}`)" class="btn btn-success" 
            style="background-color: #EF5428; border-color: #EF5428;">  <i class="fas fa-plus"></i> Tambah</button>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_mata_kuliah">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Mata Kuliah</th>
                        <th>Nama Mata Kuliah</th>
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

        var dataMataKuliah;
        $(document).ready(function() {
            dataMataKuliah = $('#table_mata_kuliah').DataTable({
                // serverSide: true, jika ingin menggunakan server side processing
                serverSide: true,
                ajax: {
                    "url": "{{ url('matakuliah/list') }}",
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
                        data: "kode_matakuliah",
                        ClassName: "",
                        // orderable: true, jika ingin kolom ini bisa diurutkan
                        orderable: true,
                        // searchable: true, jika ingin kolom ini bisa dicari
                        searchable: true
                    },{
                        data: "nama_matakuliah",
                        ClassName: "",
                        width: "25%",
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