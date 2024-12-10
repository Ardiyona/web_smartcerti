<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page - Bidang Minat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General page styles */
        body, html {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
            height: 100%;
            background-image: url('{{ asset("assets/wallpaper.jpg") }}');
        }


        .container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            
        }

        /* Header styling */
        header {
          display: flex;
      align-items: center;
      padding: 10px 20px;
      background: linear-gradient(to right, #ffffff, #EF5428);
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1000;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .logo-group {
      display: flex;
      align-items: center;
      flex-wrap: wrap; /* Allow logos to wrap on smaller screens */
    }

    .logo-polinema, .logo-jti, .logo-smartcerti {
      width: 45px;
      height: 45px;
      margin-right: 10px;
    }

    .header-title {
      font-size: 1.5rem;
      font-weight: bold;
      color: #EF5428;
      font-family: 'Arial', sans-serif;
      margin-left: 8px;
    }

        .login-button {
            background-color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-right: 40px;
        }

        .login-button a {
            color: #EF5428;
            text-decoration: none;
            font-weight: bold;
        }

        .login-button:hover {
            background-color: #EF5428;
        }

        .login-button a:hover {
            color: white;
        }

        /* Landing page styles */
        .content {
            max-width: 1200px;
            margin: auto;
            margin-top: 100px;
            text-align: center;
            padding: 20px;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 60px;
            color: #EF5428;
        }

        .bidang-options {
            display: flex;
            justify-content: center; /* Box sejajar di tengah */
            gap: 25px; /* Jarak horizontal antar box */
            margin-bottom: 35px; /* Jarak antar baris */
        }

        .option {
    display: block; /* Membuat seluruh elemen klikable */
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    text-decoration: none;
    padding: 20px;
    width: 250px; /* Lebar box */
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    color: inherit; /* Warna teks mengikuti desain */
    transition: background 0.3s, transform 0.3s;
    
}
.option .button {
    display: inline-block; /* Agar tombol sesuai konten */
    background: #445B99; /* Warna latar tombol */
    color: #fff; /* Warna teks tombol */
    text-decoration: none; /* Hilangkan garis bawah */
    padding: 10px 20px; /* Padding untuk tombol */
    border-radius: 5px; /* Sudut membulat tombol */
    font-size: 14px; /* Ukuran font tombol */
    margin-top: 10px; /* Jarak antara tombol dan elemen sebelumnya */
    transition: background 0.3s;
}

.option .button:hover {
    background: #1452a6; /* Warna saat tombol dihover */
    text-decoration: none; /* Pastikan tetap tanpa garis bawah */
}

.option:hover {
    background: #f9f9f9;
    transform: scale(1.05); /* Efek hover */
}

.option img {
    width: 100px;
    height: 100px;
    margin-bottom: 10px;
    object-fit: contain;
}


        .option .title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #375E97;
        }

        /* Footer styling */
        footer {
            background-color: #EF5428;
            color: white;
            text-align: center;
            padding: 10px 20px;
            margin-top: auto;
        }
    </style>




</head>
<body>
    <div class="container">
        <!-- Header -->
        <header>
            <div class="logo-group">
                <img src="{{ asset('assets/POLINEMA.png') }}" class="logo-polinema" alt="Logo Polinema">
                <img src="{{ asset('assets/JTI.png') }}" class="logo-jti" alt="Logo JTI">
                <img src="{{ asset('assets/logo.png') }}" class="logo-smartcerti" alt="Logo SmartCerti">
                <div class="header-title">SMARTCERTI</div>
            </div>
            <!-- Login Button -->
            <button class="login-button">
                <a href="{{ url('/login') }}" class="login-link">Login</a>
            </button>
        </header>

        <!-- Landing Page -->
        <div class="content">
            <h1>Sertifikasi dan Pelatihan Dosen Jurusan Teknologi Informasi Politeknik Negeri Malang</h1>
            <div class="bidang-options">
                <div class="option">
                    <img src="{{ asset('assets/database.png') }}" alt="Database">
                    <div class="title">Database</div>
                    <a href="{{ route('database.index') }}" class="button">Lihat Selengkapnya</a>

                </div>
                <div class="option">
                    <img src="{{ asset('assets/Gamedev.png') }}" alt="Game Development">
                    <div class="title">Game Development</div>
                    <a href="{{ route('gamedev.index') }}" class="button">Lihat Selengkapnya</a>
                </div>
                <div class="option">
                    <img src="{{ asset('assets/IOT.png') }}" alt="IOT">
                    <div class="title">Internet of Things</div>
                    <a href="{{ route('iot.index') }}" class="button">Lihat Selengkapnya</a>
                </div>
                <div class="option">
                    <img src="{{ asset('assets/AR.png') }}" alt="AR">
                    <div class="title">Augmented Reality</div>
                    <a href="{{ route('ar.index') }}" class="button">Lihat Selengkapnya</a>
                </div>
            </div>
            <div class="bidang-options">
                <div class="option">
                    <img src="{{ asset('assets/Machine_Learning.png') }}" alt="Machine Learning">
                    <div class="title">Machine Learning</div>
                    <a href="{{ route('machinelearning.index') }}" class="button">Lihat Selengkapnya</a>
                </div>
                <div class="option">
                    <img src="{{ asset('assets/BI.png') }}" alt="Business Intelligence">
                    <div class="title">Business Intelligence</div>
                    <a href="{{ route('bi.index') }}" class="button">Lihat Selengkapnya</a>
                </div>
                <div class="option">
                    <img src="{{ asset('assets/network.png') }}" alt="Enterprise Resource Planning">
                    <div class="title">Computer Network</div>
                    <a href="{{ route('network.index') }}" class="button">Lihat Selengkapnya</a>
                </div>
                <div class="option">
                    <img src="{{ asset('assets/Big_Data.png') }}" alt="Big Data">
                    <div class="title">Big Data</div>
                    <a href="{{ route('bigdata.index') }}" class="button">Lihat Selengkapnya</a>
                </div>
            </div>
        </div> 
    </div>
    <!-- Footer -->
    <footer>
            <p>&copy; 2024 SMARTCERTI. KELOMPOK 6 All rights reserved.</p>
        </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Landing Page</title>
  <style>
    /* Reset default styles */
   /* Reset default styles */
body, html {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Arial', sans-serif;
  height: 100%; /* Ensure body takes full height */
}

/* General page styles */
.container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  height: 100%;
}

/* Header styling */
header {
  display: flex;
  align-items: center;
  padding: 10px 20px;
  background: linear-gradient(to right, #ffffff, #EF5428);
  color: white;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 1000;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  justify-content: space-between;
  flex-wrap: wrap; /* Allow header to wrap on smaller screens */
}

/* Logo group styling */
.logo-group {
  display: flex;
  align-items: center;
  flex-wrap: wrap; /* Allow logos to wrap on smaller screens */
}

.logo-polinema, .logo-jti, .logo-smartcerti {
  width: 45px;
  height: 45px;
  margin-right: 10px; /* Space between logos */
}

.header-title {
  font-size: 2rem;
  font-weight: bold;
  color: #EF5428;
  font-family: 'Arial', sans-serif;
  margin-left: 8px;
}

/* Button styling */
.login-button {
  background-color: white;
  border: none;
  padding: 8px 15px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
  margin-right: 40px;
}

.login-button a {
  color: #EF5428;
  text-decoration: none;
  display: block;
  font-weight: bold;
}

.login-button:hover {
  background-color: #EF5428; /* Change background on hover */
}

.login-button a:hover {
  color: white; /* Change text color to white on hover */
}

/* Fullscreen landing page */
.landing-page {
  display: flex;
  justify-content: center;
  align-items: center;
  flex: 1;
  background-color: #DDDDDD;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  position: relative;
  opacity: 0.8;
  margin-top: 0px;
  padding: 20px; /* Add padding to prevent content from touching edges */
  width: 100%; /* Ensure landing page takes full width */
}

/* Content container styling */
.content {
  max-width: 1200px;
  width: 100%; /* Make content width responsive */
  padding: 20px;
  text-align: center;
  color: #375E97;
}

h1 {
  font-size: 2rem;
  margin-top: 40px;
  margin-bottom: 20px;
  color: #EF5428 !important;
}

/* Card styling */
.user-cards {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: center;
}

.user-card {
  width: calc(16.5% - 20px);
  border: 1px solid #ddd;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  background: white;
  margin: 10px 0;
}

.user-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.user-card .card-content {
  padding: 15px;
}

.user-card h3 {
  margin: 10px 0;
  color: #375E97;
  font-weight: bold;
  font-size: 1rem;
}

/* Styling untuk Bidang Minat dan Mata Kuliah */
.user-card p {
  color: #ACACAC; /* Warna abu */
  font-size: 1rem; /* Ukuran font standar */
}

/* Styling untuk list Bidang Minat dan Mata Kuliah */
.user-card ul li {
  color: #ACACAC; /* Warna abu untuk setiap item dalam list */
  font-size: 0.9rem; /* Ukuran font sedikit lebih kecil */
}

.user-card ul {
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 0.9rem;
  text-align: left;
}

/* Footer styling */
footer {
  background-color: #EF5428;
  color: white;
  text-align: center;
  padding: 10px 20px;
  margin-top: auto;
}


  </style>
</head>
<body>
  <div class="container"> -->
    <!-- Header -->
 <!--   <header>
      <div class="logo-group">
        <img src="{{ asset('assets/POLINEMA.png') }}" class="logo-polinema" alt="Logo Polinema">
        <img src="{{ asset('assets/JTI.png') }}" class="logo-jti" alt="Logo JTI">
        <img src="{{ asset('assets/logo.png') }}" class="logo-smartcerti" alt="Logo SmartCerti">
        <div class="header-title">SMARTCERTI</div>
      </div> -->
      <!-- Login Button -->
<!--      <button class="login-button">
        <a href="{{ url('/login') }}" class="login-link">Login</a>
      </button>
    </header> -->

    <!-- Landing Page -->
<!--    <div class="landing-page">
      <div class="content">
        <h1>Sertifikasi dan Pelatihan Dosen Jurusan Teknologi Informasi</h1>
        
-->
        <!-- User Cards -->
<!--        <div class="user-cards">
          @if($user->isEmpty())
            <p>No user data found.</p>
          @else
            @foreach($user as $user)
              <div class="user-card"> -->
                <!-- Menampilkan Avatar 
                <img src="{{ $user->avatar ? asset('storage/photos/' . $user->avatar) : asset('default-avatar.jpg') }}" alt="Avatar {{ $user->nama_lengkap }}" class="user-avatar">

                <div class="card-content">
                   Menampilkan Nama Pengguna 
                  <h3>{{ $user->nama_lengkap }}</h3>

                   Menampilkan Bidang Minat 
                  <p>Bidang Minat:</p>
                  <ul>
                    @foreach($user->detail_daftar_user_bidang_minat as $bidangMinat)
                      <li>{{ $bidangMinat->nama_bidang_minat }}</li>
                    @endforeach
                  </ul>

                   Menampilkan Mata Kuliah 
                  <p>Mata Kuliah:</p>
                  <ul>
                    @foreach($user->detail_daftar_user_matakuliah as $mataKuliah)
                      <li>{{ $mataKuliah->nama_matakuliah }}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div> -->

    <!-- Footer -->
    <!-- <footer>
      <p>&copy; 2024 SMARTCERTI. KELOMPOK 6 All rights reserved.</p>
    </footer>
  </div>
</body>
</html>  -->
