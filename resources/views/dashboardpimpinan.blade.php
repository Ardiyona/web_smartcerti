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
          @if(in_array(Auth::user()->id_level, [1, 2])) <!-- Level 1 untuk Admin -->
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
          @if(in_array(Auth::user()->id_level, [1, 2]))
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
          @if(in_array(Auth::user()->id_level, [2, 3])) <!-- Level 2 untuk User -->
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
          @if(in_array(Auth::user()->id_level, [2, 3]))
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
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Bar Chart</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="chart">
            <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
      </div>
      
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          // Data dari Laravel yang dikirim ke view
          var years = @json($years); // Tahun dari controller
          var pelatihanCounts = @json($pelatihanCounts); // Jumlah pelatihan dari controller
          var sertifikasiCounts = @json($sertifikasiCounts); // Jumlah sertifikasi dari controller
          
          console.log(years); // Debug untuk memastikan data ter-load dengan benar
          
          // Konfigurasi Chart.js
          var barChartCanvas = document.getElementById('barChart').getContext('2d');
          new Chart(barChartCanvas, {
            type: 'bar',
            data: {
              labels: years, // Tahun sebagai label sumbu X
              datasets: [
                {
                  label: 'Pelatihan',
                  backgroundColor: 'rgba(60,141,188,0.9)',
                  borderColor: 'rgba(60,141,188,0.8)',
                  data: pelatihanCounts
                },
                {
                  label: 'Sertifikasi',
                  backgroundColor: 'rgba(210,214,222,1)',
                  borderColor: 'rgba(210,214,222,1)',
                  data: sertifikasiCounts
                }
              ]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              scales: {
                y: {
                  beginAtZero: true // Memastikan skala dimulai dari nol
                }
              }
            }
          });
        });
      </script>
      
    <!-- /.content -->
@endsection
