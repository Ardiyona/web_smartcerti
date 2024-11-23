<link rel="stylesheet" href="{{ asset('css/style.css') }}">


<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
     
   

     
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      
      <!-- Profile dan Dropdown Logout -->
<li class="nav-item dropdown">
    <a class="nav-link" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        @if(Auth::user()->avatar && file_exists(public_path('storage/photos/' . Auth::user()->avatar)))
            <img src="{{ asset('storage/photos/' . Auth::user()->avatar) }}" class="rounded-circle" style="width: 40px; height: 40px;" alt="User Avatar">
        @else
            <img src="{{ asset('img/default-profile.png') }}" class="rounded-circle" style="width: 40px; height: 40px;" alt="Default User Avatar">
        @endif
        <span class="ml-2">{{ Auth::user()->username }}</span>
    </a>

   <!-- Dropdown Menu -->
  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" style="width: 280px; padding: 20px;">
      <div class="dropdown-item text-center">
          @if(Auth::user()->avatar && file_exists(public_path('storage/photos/' . Auth::user()->avatar)))
              <img src="{{ asset('storage/photos/' . Auth::user()->avatar) }}" class="img-fluid rounded-circle" style="width: 80px; height: 80px;" alt="User Avatar">
          @else
              <img src="{{ asset('img/default-profile.png') }}" class="img-fluid rounded-circle" style="width: 80px; height: 80px;" alt="Default User Avatar">
          @endif
          <p class="text-muted" style="margin-bottom: 5px;">Login sebagai {{ Auth::user()->role }}</p>
          <h5 class="mt-1">{{ Auth::user()->username }}</h5>
      </div>

      <!-- Tombol Profile -->
      <a class="dropdown-item text-center btn profile-btn my-2" href="{{ url('profile') }}" style="width: 100%; background-color: #f8f9fa; color: #343a40; border: 1px solid #343a40;">Profile</a>

      <!-- Tombol Logout -->
      <a class="dropdown-item text-center btn logout-btn my-2" href="{{ url('logout') }}" style="width: 100%;">Logout</a>
  </div>

</li>
</ul>
</nav>
  <!-- /.navbar -->