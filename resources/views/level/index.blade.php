@extends('layouts.template')

@section('title')
    | Level
@endsection

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>

        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }} </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }} </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-1 control-label col-form-label">Filter: </label>
                        <div class="col-3">
                            <select class="form-control" id="id_level" name="id_level" required>
                                <option value="">- Semua -</option>
                                @foreach ($level as $item)
                                    <option value="{{ $item->id_level }}">{{ $item->nama_level }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table responsive table-bordered table-striped table-hover table-sm" id="table_user">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode Level</th>
                        <th>Nama Level</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false"
        data-width="75%"></div>
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

        var dataLevel;
        $(document).ready(function() {
            dataLevel = $('#table_user').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ url('level/list') }}",
                    type: "POST",
                    data: function(d) {
                        d.id_level = $('#id_level').val(); // Filter berdasarkan pilihan dropdown
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "kode_level",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "nama_level",
                        orderable: true,
                        searchable: true
                    }

                ]
            });

            // Reload DataTable saat filter berubah
            $('#id_level').on('change', function() {
                dataLevel.ajax.reload();
            });
        });
    </script>
@endpush
