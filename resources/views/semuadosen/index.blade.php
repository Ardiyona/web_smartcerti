
@extends('layouts.template')
@section('title')
    | Daftar Pelatihan dan Sertifikasi
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Jumlah Pelatihan</th>
                        <th>Jumlah Sertifikasi</th>
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
        $(document).ready(function() {
            var columns = [
                { data: "DT_RowIndex", className: "text-center", width: "4%", orderable: false, searchable: false },
                { data: "nama_lengkap", className: "", width: "40%", orderable: true, searchable: true },
                { data: "jumlah_pelatihan", className: "text-center", width: "28%", orderable: true, searchable: false },
                { data: "jumlah_sertifikasi", className: "text-center", width: "28%", orderable: true, searchable: false }
            ];
            
            $('#table_user').DataTable({
                serverSide: true,
                processing: true,
                ajax: {
                    url: "{{ url('semuadosen/list') }}", // Ganti dengan route yang benar
                    type: "GET"
                },
                columns: columns
            });
        });
    </script>
@endpush
