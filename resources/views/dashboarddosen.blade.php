@extends('layouts.template')

@section('content')

 <!-- Main content -->
 <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">

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
              <a href="{{ url('pelatihandosen') }}" class="small-box-footer">
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
              <a href="{{ url('sertifikasidosen') }}" class="small-box-footer">
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
@endsection