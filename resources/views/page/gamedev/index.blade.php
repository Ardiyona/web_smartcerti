<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Landing Page</title>
  <style>
    /* Reset default styles */
    body, html {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Arial', sans-serif;
      height: 100%;
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

    /* Landing page */


    /* Content container styling */
    .content {
            max-width: 1500px;
            margin: auto;
            margin-top: 60px;
            text-align: center;
            padding: 20px;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 40px;
            color: #EF5428;
        }
    /* User Cards */
    .user-cards {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }

    .user-card {
  width: calc(20% - 20px);
  border: 1px solid #ddd;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  background: white;
  transition: transform 0.3s ease;
  text-align: center;
  padding: 20px;
}

.user-card img.avatar {
  width: 150px; /* Ukuran avatar */
  height: 150px;
  border-radius: 50%; /* Membuat gambar menjadi bulat */
  object-fit: cover; /* Agar gambar tetap terpotong dengan proporsional */
  margin-bottom: 10px;
}


    .user-card:hover {
      transform: translateY(-5px);
    }

    .user-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .card-content {
      padding: 15px;
    }

    .card-content h3 {
      margin: 10px 0;
      font-size: 1.2rem;
      color: #375E97;
      font-size: 1.5rem;
    }

    .user-card p {
  color: #375E97; /* Warna abu */
  font-size: 1.1rem; /* Ukuran font */
  text-align: justify;
  margin-bottom: 4px; /* Tambahkan jarak bawah */
}

.card-content ul {
  list-style: none;
  padding: 0;
  margin: 5px 0; /* Tambahkan jarak atas dan bawah */
  color: #6E6969;
  font-size: 1.1rem; /* Ukuran font */
  text-align: justify;
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
    </header>

    <!-- Landing Page -->
    <div class="landing-page">
      <div class="content">
        <h1>Daftar Dosen dengan Bidang Minat Game Development<br>Jurusan Teknologi Informasi</h1>

        <!-- User Cards -->
        <div class="user-cards">
          @if($user->isEmpty())
            <p>No user data found.</p>
          @else
            @foreach($user as $user)
              <div class="user-card">
              <img src="{{ $user->avatar ? asset('storage/photos/' . $user->avatar) : asset('assets/user.png') }}" alt="Avatar {{ $user->nama_lengkap }}" class="avatar">
                <div class="card-content">
                  <h3>{{ $user->nama_lengkap }}</h3>
                  <p>Bidang Minat:</p>
                  <ul>
                    @foreach($user->detail_daftar_user_bidang_minat as $bidangMinat)
                      <li>{{ $bidangMinat->nama_bidang_minat }}</li>
                    @endforeach
                  </ul>
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
    </div>

    <!-- Footer -->
    <footer>
      <p>&copy; 2024 SMARTCERTI. KELOMPOK 6 All rights reserved.</p>
    </footer>
  </div>
</body>
</html>
