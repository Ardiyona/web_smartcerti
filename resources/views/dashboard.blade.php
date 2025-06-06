{{-- @extends('layouts.template')

@section('content')

 <!-- Main content -->
 <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <!-- Total Pelatihan -->
          <div class="col-lg-2 col-md-4 col-6 mr-3 mb-4">
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
          <div class="col-lg-2 col-md-4 col-6 mr-3 mb-4">
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
          <div class="col-lg-2 col-md-4 col-6 mr-3 mb-4">
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
          <div class="col-lg-2 col-md-4 col-6 mr-3 mb-4">
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
            <div class="col-lg-2 col-md-4 col-6 mr-3 mb-4">
            <div class="small-box" style="background-color: #ee5428; color: white;">
                    <div class="inner">
                        <h3>{{ $jumlahPelatihan }}</h3> <!-- Tampilkan jumlah total pelatihan -->
                        <p>Total Pelatihan</p> <!-- Deskripsi diperjelas -->
                    </div>
                    <div class="icon">
                        <i class="ion ion-bookmark"></i>
                    </div>
                    <a href="{{ url('semuapelatihandosen') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            @endif
            <!-- ./col -->

            <!-- Total Sertifikasi (Untuk Admin atau Pimpinan) -->
            @if(in_array(Auth::user()->id_level, [1, 2]))
            <div class="col-lg-2 col-md-4 col-6 mr-3 mb-4">
            <div class="small-box" style="background-color: #101e42; color: white;">
                    <div class="inner">
                        <h3>{{ $jumlahSertifikasi }}</h3> <!-- Tampilkan jumlah total sertifikasi -->
                        <p>Total Sertifikasi</p> <!-- Deskripsi diperjelas -->
                    </div>
                    <div class="icon">
                        <i class="ion ion-document-text"></i>
                    </div>
                    <a href="{{ url('semuasertifikasidosen') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
 
            <!-- Total User Sertifikasi (Untuk Admin atau Pimpinan) -->
            <div class="col-lg-2 col-md-4 col-6 mr-3 mb-4">
            <div class="small-box" style="background-color: #FF4747; color: white;">
                  <div class="inner">
                      <h3>{{ $jumlahPengguna }}</h3> <!-- Tampilkan jumlah total sertifikasi -->
                      <p>Total Pengguna</p> <!-- Deskripsi diperjelas -->
                  </div>
                  <div class="icon">
                      <i class="ion ion-person"></i>
                  </div>
                  <a href="{{ url('semuadosen') }}" class="small-box-footer">
                      More info <i class="fas fa-arrow-circle-right"></i>
                  </a>
              </div>
          </div> 

          <!--matakuliah -->
          <div class="col-lg-2 col-md-4 col-6 mr-3 mb-4">
          <div class="small-box" style="background-color: #488A99; color: white;">
                <div class="inner">
                    <h3>{{ $jumlahmatakuliah }}</h3> <!-- Tampilkan jumlah total sertifikasi -->
                    <p>Total Matakuliah</p> <!-- Deskripsi diperjelas -->
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <a href="{{ url('jumlahmatakuliah') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div> 

          <!--bidangminat -->
          <div class="col-lg-2 col-md-4 col-6 mr-3 mb-4">
          <div class="small-box" style="background-color: #949494; color: white;">
                <div class="inner">
                    <h3>{{ $jumlahbidangminat }}</h3> <!-- Tampilkan jumlah total sertifikasi -->
                    <p>Total Bidang Minat</p> <!-- Deskripsi diperjelas -->
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
                <a href="{{ url('jumlahbidangminat') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div> 

            @endif
            <!-- ./col -->

            <!-- Pelatihan User (Untuk User) -->
            @if(in_array(Auth::user()->id_level, [2, 3])) <!-- Level 2 untuk User -->
            <div class="col-lg-2 col-md-4 col-6 mr-3 mb-4">
            <div class="small-box" style="background-color: #fcb418; color: white;">
                    <div class="inner">
                        <h3>{{ $jumlahPelatihanUser }}</h3> <!-- Tampilkan jumlah pelatihan untuk user -->
                        <p>Pelatihan Anda</p> <!-- Deskripsi untuk user -->
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-stalker"></i>
                    </div>
                    <a href="{{ url('pelatihanuser') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            @endif
            <!-- ./col -->

            <!-- Sertifikasi User (Untuk User) -->
            @if(in_array(Auth::user()->id_level, [2, 3]))
            <div class="col-lg-2 col-md-4 col-6 mr-3 mb-4">
            <div class="small-box" style="background-color: #375E97; color: white;">
                    <div class="inner">
                        <h3>{{ $jumlahSertifikasiUser }}</h3> <!-- Tampilkan jumlah sertifikasi untuk user -->
                        <p>Sertifikasi Anda</p> <!-- Deskripsi untuk user -->
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-stalker"></i>
                    </div>
                    <a href="{{ url('sertifikasiuser') }}" class="small-box-footer">
                        More info <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            @endif
            <!-- ./col -->

            <!-- BAR CHART -->
            <div class="container-fluid">
            <div class="row">

            <div class="col-md-5">
            @if(in_array(Auth::user()->id_level, [1, 2]))
            <div class="card card-pink" style="margin-bottom: 0;">
                <div class="card-header" style="padding-bottom: 0.5rem;">
                    <h3 class="card-title">Jumlah Pelatihan dan Sertifikasi Tiap Periode</h3>
                    
                </div>
                <div class="card-body" style="padding-top: 0.5rem;">
                    <div class="chart">
                        <canvas id="barChart" style="min-height: 250px; height: 150px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
            </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const chartData = @json($chartData);
                const ctx = document.getElementById('barChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: chartData,
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
             @endif
            <!-- /.card -->
        </div>
        <!-- /.row -->
    </div>
</section>
<!-- /.content -->
@endsection
