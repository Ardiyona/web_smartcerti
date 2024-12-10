<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<!-- Brand Logo -->
<a href="{{ url('/') }}" class="brand-link">
    <img src="{{ asset('assets/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
        style="opacity: 1; background-color: white;">
    <span class="brand-text font-weight-bold text-light">SMARTCERTI</span>
</a>

<!-- Sidebar Search Form 
<div class="form-inline mt-2 mx-3">
    <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
            </button>
        </div>
    </div>
</div> -->

<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ url('/dashboard') }}" class="nav-link {{ $activeMenu == 'dashboard' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            @if(in_array(Auth::user()->id_level, [1, 2]))
            <li class="nav-header">Kompetensi Prodi</li>
            <li class="nav-item">
                <a href="{{ url('/kompetensiprodi') }}" class="nav-link {{ $activeMenu == 'kompetensiprodi' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-graduation-cap"></i>
                    <p>Kompetensi Prodi</p>
                </a>
            </li>
            @endif

            @if(in_array(Auth::user()->id_level, [1]))
            <li class="nav-header">Program Studi</li>
            <li class="nav-item">
                <a href="{{ url('/prodi') }}" class="nav-link {{ $activeMenu == 'prodi' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-graduation-cap"></i>
                    <p> Prodi</p>
                </a>
            </li>
            @endif
            
            <!-- Menu Umum untuk Level 1, 2, dan 3 -->
            @if(in_array(Auth::user()->id_level, [1, 2, 3]))
                <li class="nav-header">Mengelola Pelatihan dan Sertifikasi</li>
                <li class="nav-item">
                    <a href="{{ url('/pelatihan') }}" class="nav-link {{ $activeMenu == 'pelatihan' ? 'active' : '' }}">
                        <i class="nav-icon far fa-bookmark"></i>
                        <p>Pelatihan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/sertifikasi') }}" class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }}">
                        <i class="nav-icon far fa-bookmark"></i>
                        <p>Sertifikasi</p>
                    </a>
                </li>
            @endif

            <!-- Untuk Admin -->
            @if(Auth::user()->id_level == 1)
                <li class="nav-header">Data Pengguna</li>
                <li class="nav-item">
                    <a href="{{ url('/level') }}" class="nav-link {{ $activeMenu == 'level' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Level Pengguna</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/user') }}" class="nav-link {{ $activeMenu == 'user' ? 'active' : '' }}">
                        <i class="nav-icon far fa-user"></i>
                        <p>Data Pengguna</p>
                    </a>
                </li>

                <li class="nav-header">Mengelola Vendor</li>
                <li class="nav-item">
                    <a href="{{ url('/vendorpelatihan') }}" class="nav-link {{ $activeMenu == 'vendorpelatihan' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Vendor Pelatihan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/vendorsertifikasi') }}" class="nav-link {{ $activeMenu == 'vendorsertifikasi' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>Vendor Sertifikasi</p>
                    </a>
                </li>

                <li class="nav-header">Mengelola Jenis</li>
                <li class="nav-item">
                    <a href="{{ url('/jenispelatihan') }}" class="nav-link {{ $activeMenu == 'jenispelatihan' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Jenis Pelatihan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/jenissertifikasi') }}" class="nav-link {{ $activeMenu == 'jenissertifikasi' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Jenis Sertifikasi</p>
                    </a>
                </li>

                <li class="nav-header">Mengelola Mata Kuliah</li>
                <li class="nav-item">
                    <a href="{{ url('/matakuliah') }}" class="nav-link {{ $activeMenu == 'matakuliah' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-book"></i>
                        <p>Mata Kuliah</p>
                    </a>
                </li>

                <li class="nav-header">Mengelola Bidang Minat</li>
                <li class="nav-item">
                    <a href="{{ url('/bidangminat') }}" class="nav-link {{ $activeMenu == 'bidangminat' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-atom"></i>
                        <p>Bidang Minat</p>
                    </a>
                </li>

                <li class="nav-header">Mengelola Periode</li>
                <li class="nav-item">
                    <a href="{{ url('/periode') }}" class="nav-link {{ $activeMenu == 'periode' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Periode</p>
                    </a>
                </li>
            @endif

            {{-- <!-- Menu Umum untuk Level 1, 2, dan 3 -->
            @if(in_array(Auth::user()->id_level, [1, 2, 3]))
                <li class="nav-header">Mengelola Pelatihan dan Sertifikasi</li>
                <li class="nav-item">
                    <a href="{{ url('/pelatihan') }}" class="nav-link {{ $activeMenu == 'pelatihan' ? 'active' : '' }}">
                        <i class="nav-icon far fa-bookmark"></i>
                        <p>Pelatihan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/sertifikasi') }}" class="nav-link {{ $activeMenu == 'sertifikasi' ? 'active' : '' }}">
                        <i class="nav-icon far fa-bookmark"></i>
                        <p>Sertifikasi</p>
                    </a>
                </li>
            @endif --}}

            <!-- Untuk Pimpinan -->
            @if(Auth::user()->id_level == 2)
                <li class="nav-header">Management Permintaan</li>
                <li class="nav-item">
                    <a href="{{ url('/penerimaanpermintaan') }}" class="nav-link {{ $activeMenu == 'penerimaanpermintaan' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Penerimaan Permintaan</p>
                    </a>
                </li>
            @endif

        </ul>
    </nav>
</div>
