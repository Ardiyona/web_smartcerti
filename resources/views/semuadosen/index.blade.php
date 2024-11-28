@extends('layouts.template')
@section('title')
    | Daftar Pengguna
@endsection

@section('content')
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

@push('scripts')
<script>
    $(document).ready(function() {
        var columns = [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'nama_lengkap', name: 'nama_lengkap' },
            { data: 'jumlah_pelatihan', name: 'jumlah_pelatihan' },
            { data: 'jumlah_sertifikasi', name: 'jumlah_sertifikasi' },
        ];

        var dataDosen = $('#table_user').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('semuadosen/list') }}", // Ensure the correct route is used
                dataType: "json",
                type: "POST",
            },
            columns: columns
        });
    });
</script>
@endpush
