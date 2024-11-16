<link rel="stylesheet" href="{{ asset('css/style.css') }}">

 <!-- Brand Logo -->
 <a href="{{ url('/') }}" class="brand-link">
 <img src="{{ asset('assets/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 1; background-color: white;">
      <span class="brand-text font-weight-bold text-light">SMARTCERTI</span>
    </a>

  <!-- Sidebar Search Form -->
<div class="form-inline mt-2 mx-3"> <!-- Tambahkan mx-3 untuk margin horizontal -->
  <div class="input-group" data-widget="sidebar-search">
    <input class="form-control form-control-sidebar" type="search" 
    placeholder="Search" aria-label="Search">
    <div class="input-group-append"> 
      <button class="btn btn-sidebar">
        <i class="fas fa-search fa-fw"></i>
      </button>
    </div>
  </div>
</div>

<div class="sidebar">
  <!-- Sidebar Menu -->
  <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" 
    role="menu" data-accordion="false">
      <li class="nav-item">
        <a href="{{ url('/') }}" class="nav-link {{ ($activeMenu == 'dashboard')? 
        'active': '' }} ">
          <i class="nav-icon fas fa-tachometer-alt"></i> 
          <p>Dashboard</p>
        </a>
      </li>

      
      <li class="nav-header">Data Pengguna</li>
      <li class="nav-item">
        <a href="{{ url('/level') }}" class="nav-link {{ ($activeMenu == 'level')? 
        'active': ''}}">
          <i class="nav-icon fas fa-layer-group"></i>
          <p>Level User</p>
        </a> 
      </li>
      <li class="nav-item">
        <a href="{{ url('/user') }}" class="nav-link {{ ($activeMenu == 'user')?
        'active': ''}}">
          <i class="nav-icon far fa-user"></i>
          <p>Data User</p>
        </a> 
      </li>
      
      <li class="nav-header">Manage Pelatihan dan Sertifikasi</li> 
      <li class="nav-item">
        <a href="{{ url('/pelatihan') }}" class="nav-link {{ ($activeMenu == 
        'pelatihan') ? 'active': ''}}">
          <i class="nav-icon far fa-bookmark"></i>
          <p>Pelatihan</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/sertifikasi') }}" class="nav-link {{ ($activeMenu == 
        'sertifikasi') ? 'active': ''}}">
          <i class="nav-icon far fa-bookmark"></i>
          <p>Sertifikasi</p>
        </a>
      </li>

      <li class="nav-header">Manage Vendor</li>      
      <li class="nav-item">
        <a href="{{ url('/vendorpelatihan') }}" class="nav-link {{ ($activeMenu == 'vendorpelatihan')? 
        'active': ''}}">
          <i class="nav-icon fas fa-building"></i>
          <p>Vendor Pelatihan</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/vendorsertifikasi') }}" class="nav-link {{ ($activeMenu == 
        'vendorsertifikasi') ? 'active': ''}}">
        <i class="nav-icon fas fa-building"></i>
          <p>Vendor Sertifikasi</p>
        </a>
      </li>

      <li class="nav-header">Manage Jenis</li>      
      <li class="nav-item">
        <a href="{{ url('/jenispelatihan') }}" class="nav-link {{ ($activeMenu == 'jenispelatihan')? 
        'active': ''}}">
          <i class="nav-icon fas fa-list-alt"></i>
          <p>Jenis Pelatihan</p>
        </a>
      </li>

      <li class="nav-item">
        <a href="{{ url('/jenissertifikasi') }}" class="nav-link {{ ($activeMenu == 
        'jenissertifikasi') ? 'active': ''}}">
          <i class="nav-icon fas fa-list-alt"></i>
          <p>Jenis Sertifikasi</p>
        </a>
      </li>

      <li class="nav-header">Manage Mata Kuliah</li> 
      <li class="nav-item">
        <a href="{{ url('/matakuliah') }}" class="nav-link {{ ($activeMenu == 
        'matakuliah') ? 'active': ''}}">
          <i class="nav-icon fas fa-book"></i>
          <p>Mata Kuliah</p>
        </a>
      </li>
      
      <li class="nav-header">Manage Bidang Minat</li> 
      <li class="nav-item">
        <a href="{{ url('/bidangminat') }}" class="nav-link {{ ($activeMenu == 
        'bidangminat') ? 'active': ''}}">
          <i class="nav-icon fas fa-atom"></i>
          <p>Bidang Minat</p>
        </a>
        </li>

        <li class="nav-header">Manage Periode</li> 
      <li class="nav-item">
        <a href="{{ url('/periode') }}" class="nav-link {{ ($activeMenu == 
        'periode') ? 'active': ''}}">
          <i class="nav-icon fas fa-calendar-alt"></i>
          <p>Periode</p>
        </a>
        </li>
    
    <!-- Dosen -->
    <li class="nav-header">Dosen</li> 
    <li class="nav-item">
        <a href="{{ url('/dosenpelatihan') }}" class="nav-link {{ ($activeMenu == 
        'dosenpelatihan') ? 'active': ''}}">
          <i class="nav-icon far fa-bookmark"></i>
          <p>Pelatihan</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/dosensertifikasi') }}" class="nav-link {{ ($activeMenu == 
        'dosensertifikasi') ? 'active': ''}}">
          <i class="nav-icon far fa-bookmark"></i>
          <p>Sertifikasi</p>
        </a>

    <!-- Pimpinan -->
    <li class="nav-header">Pimpinan</li> 
    <li class="nav-item">
        <a href="{{ url('/pimpinanpelatihan') }}" class="nav-link {{ ($activeMenu == 
        'pimpinanpelatihan') ? 'active': ''}}">
          <i class="nav-icon far fa-bookmark"></i>
          <p>Pelatihan</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/pimpinansertifikasi') }}" class="nav-link {{ ($activeMenu == 
        'pimpinansertifikasi') ? 'active': ''}}">
          <i class="nav-icon far fa-bookmark"></i>
          <p>Sertifikasi</p>
        </a>
      </li>

    </ul>
  </nav>   
</div>


