{{-- @extends('layouts.template')

@section('content')

 <!-- Main content -->
 <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <!-- Total Pelatihan -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ $jumlahPelatihan }}</h3> <!-- Tampilkan jumlah total pelatihan -->

                <p>Total Pelatihan </p> <!-- Deskripsi diperjelas -->
              </div>
              <div class="icon">
                <i class="ion ion-bookmark"></i>
              </div>
              <a href="{{ url('pimpinanpelatihandosen') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->

          <!-- Total Sertifikasi -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ $jumlahSertifikasi }}</h3> <!-- Tampilkan jumlah total sertifikasi -->

                <p>Total Sertifikasi</p> <!-- Deskripsi diperjelas -->
              </div>
              <div class="icon">
                <i class="ion ion-document-text"></i>
              </div>
              <a href="{{ url('pimpinansertifikasidosen') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->

          <!-- Pelatihan User -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ $jumlahPelatihanUser }}</h3> <!-- Tampilkan jumlah pelatihan untuk user -->

                <p>Pelatihan Anda</p> <!-- Deskripsi untuk user -->
              </div>
              <div class="icon">
                <i class="ion ion-person-stalker"></i>
              </div>
              <a href="{{ url('pimpinanpelatihandosen') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->

          <!-- Sertifikasi User -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ $jumlahSertifikasiUser }}</h3> <!-- Tampilkan jumlah sertifikasi untuk user -->

                <p>Sertifikasi Anda</p> <!-- Deskripsi untuk user -->
              </div>
              <div class="icon">
                <i class="ion ion-person-stalker"></i>
              </div>
              <a href="{{ url('pimpinansertifikasidosen') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
      </div>
    </section>
    <!-- /.content -->
@endsection --}}

@extends('layouts.template')

@section('content')

 <!-- Main content -->
 <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          
          <!-- Total Pelatihan (Untuk Admin atau Pimpinan) -->
          @if(in_array(Auth::user()->id_level, [1, 3])) <!-- Level 1 untuk Admin -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ $jumlahPelatihan }}</h3> <!-- Tampilkan jumlah total pelatihan -->

                <p>Total Pelatihan</p> <!-- Deskripsi diperjelas -->
              </div>
              <div class="icon">
                <i class="ion ion-bookmark"></i>
              </div>
              <a href="{{ url('pimpinanpelatihandosen') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          @endif
          <!-- ./col -->

          <!-- Total Sertifikasi (Untuk Admin atau Pimpinan) -->
          @if(in_array(Auth::user()->id_level, [1, 3]))
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ $jumlahSertifikasi }}</h3> <!-- Tampilkan jumlah total sertifikasi -->

                <p>Total Sertifikasi</p> <!-- Deskripsi diperjelas -->
              </div>
              <div class="icon">
                <i class="ion ion-document-text"></i>
              </div>
              <a href="{{ url('pimpinansertifikasidosen') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          @endif
          <!-- ./col -->

          <!-- Pelatihan User (Untuk User) -->
          @if(Auth::user()->id_level == 2) <!-- Level 2 untuk User -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ $jumlahPelatihanUser }}</h3> <!-- Tampilkan jumlah pelatihan untuk user -->

                <p>Pelatihan Anda</p> <!-- Deskripsi untuk user -->
              </div>
              <div class="icon">
                <i class="ion ion-person-stalker"></i>
              </div>
              <a href="{{ url('pimpinanpelatihandosen') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          @endif
          <!-- ./col -->

          <!-- Sertifikasi User (Untuk User) -->
          @if(Auth::user()->id_level == 2)
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ $jumlahSertifikasiUser }}</h3> <!-- Tampilkan jumlah sertifikasi untuk user -->

                <p>Sertifikasi Anda</p> <!-- Deskripsi untuk user -->
              </div>
              <div class="icon">
                <i class="ion ion-person-stalker"></i>
              </div>
              <a href="{{ url('pimpinansertifikasidosen') }}" class="small-box-footer">
                More info <i class="fas fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          @endif
          <!-- ./col -->

        </div>
        <!-- /.row -->
      </div>
    </section>
    <!-- /.content -->
@endsection
