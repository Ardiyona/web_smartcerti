@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($sertifikasi)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table-bordless table-sm">
                    <tr>
                        <th>Nama Sertifikasi</th>
                        <td>: {{ $sertifikasi->nama_sertifikasi }}</td>
                    </tr>
                    <tr>
                        <th>Vendor</th>
                        <td>: {{ $sertifikasi->vendor_sertifikasi->nama }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Bidang</th>
                        <td>: {{ $sertifikasi->jenis_sertifikasi->nama_jenis_sertifikasi }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>: <span id="formatted-date"></span></td>
                    </tr>
                    <tr>
                        <th>Biaya</th>
                        <td>: {{ $sertifikasi->biaya }}</td>
                    </tr>
                </table>
            @endempty
            <a href="{{ url('/dashboard') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection
@push('css')
@endpush
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            moment.locale('id');
            var tanggal = '{{ $sertifikasi->tanggal }}';
            var momentDate = moment(tanggal, 'YYYY-MM-DD');
            var formattedDate = momentDate.format('dddd, DD-MM-YYYY');

            $('#formatted-date').text(formattedDate);
        });
    </script>
@endpush
