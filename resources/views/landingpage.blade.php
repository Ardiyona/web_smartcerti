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
    }

    /* General page styles */
    .container {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* Header styling */
    header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 20px;
      background: linear-gradient(to right, #ffffff, #EF5428); /* Gradasi dari oranye ke putih */
      color: white;
    }

    .logo-group {
      display: flex;
      align-items: center;
    }

    .logo-polinema {
      width: 70px; /* Ukuran khusus untuk logo POLINEMA */
      height: 70px;
    }

    .logo-jti {
      width: 65px; /* Ukuran khusus untuk logo JTI */
      height: 65px;
    }

    .logo-smartcerti {
      width: 68px; /* Ukuran khusus untuk logo SMARTCERTI */
      height: 68px;
    }

    .header-title {
  font-size: 2.5rem; /* Ukuran teks header */
  font-weight: bold; /* Menjadikan teks bold */
  color: #EF5428; /* Warna teks */
  font-family: 'Arial', sans-serif; /* Pastikan menggunakan font yang mendukung bold */
}

/* Fullscreen landing page */
.landing-page {
  display: flex;
  justify-content: center;
  align-items: center;
  flex: 1;
  background-image: url("{{ asset('assets/Poltek.jpg') }}"); /* Ganti dengan path gambar Anda */
  background-size: cover;  /* Agar gambar menutupi seluruh area */
  background-position: center;
  background-repeat: no-repeat;
  position: relative;
  opacity: 0.8; /* Mengatur transparansi pada background secara keseluruhan */
}

    /* Landing page content styling */
    .content {
      max-width: 600px;
      padding: 20px;
      text-align: center;
      color: white; /* Warna teks putih agar kontras dengan gambar latar belakang */
    }

    h1 {
      font-size: 3rem;
      margin-bottom: 20px;
    }

    p {
      font-size: 1.2rem;
      margin-bottom: 30px;
    }

    /* Login button styling */
    .login-button {
      background-color: #ffffff;
      color: #375E97;
      font-size: 1.2rem;
      font-weight: bold;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-button:hover {
      background-color: #f0f0f0;
    }

    .login-button a {
      text-decoration: none;
      color: inherit;
    }

   /* Footer styling */
footer {
    background-color: #EF5428; /* Warna footer sama dengan header */
    color: white;
    text-align: center;
    padding: 10px 20px; /* Menambahkan padding untuk memberi ruang di dalam footer */
    margin-top: auto;
    display: flex;
    justify-content: center; /* Menyusun teks secara horizontal */
    align-items: center; /* Menyusun teks secara vertikal */
    height: 30px; /* Tentukan tinggi footer sesuai keinginan */
}

  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <header>
      <div class="logo-group">
        <img src="{{ asset('assets/POLINEMA.png') }}" class="logo-polinema">
        <img src="{{ asset('assets/JTI.png') }}" class="logo-jti">
        <img src="{{ asset('assets/logo.png') }}" class="logo-smartcerti">
        <div class="header-title">SMARTCERTI</div>
      </div>
      <button class="login-button">
          <a href="{{ url('/login') }}" class="login-link">Login</a>
        </button>
      
    </header>

    <!-- Landing Page -->
    <div class="landing-page">
      <div class="content">
        <h1>Welcome to SMARTCERTI</h1>
        <p>Manage your certifications and training with ease.</p>
       
      </div>
    </div>

    <!-- Footer -->
    <footer>
      <p>&copy; 2024 SMARTCERTI. KELOMPOK 6 All rights reserved.</p>
    </footer>
  </div>
</body>
</html>
