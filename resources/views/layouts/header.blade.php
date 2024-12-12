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



        @if (Auth::user()->id_level != 1)
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span
                        class="badge badge-warning navbar-badge">{{ Auth()->user()->unreadNotifications->count() }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">Notifications</span>
                    @if (Auth()->user()->unreadNotifications->count() > 0)
                        @foreach (Auth()->user()->unreadNotifications as $notification)
                            <div class="dropdown-divider"></div>
                            <a href="{{ url($notification->data['url']) }}" class="dropdown-item">
                                <p class="dropdown-item-title font-weight-bold">
                                    {{ $notification->data['title'] }}
                                    <span
                                        class="float-right text-muted text-sm font-weight-normal">{{ $notification->created_at->diffForHumans() }}</span>
                                </p>
                                <p class="notification-text">{{ $notification->data['massages'] }}</p>
                            </a>
                        @endforeach
                    @else
                        <div class="dropdown-item">No Notifications</div>
                    @endif
                    <div class="dropdown-divider"></div>
                </div>
            </li>
        @endif


        <!-- Profile dan Dropdown Logout -->
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                @if (Auth::user()->avatar && file_exists(public_path('storage/photos/' . Auth::user()->avatar)))
                    <img src="{{ asset('storage/photos/' . Auth::user()->avatar) }}" class="rounded-circle profile-img">
                @else
                    <img src="{{ asset('assets/user.png') }}" class="rounded-circle profile-img">
                @endif
                <span class="ml-2">{{ Auth::user()->nama_lengkap }}</span>
            </a>

            <!-- Dropdown Menu -->
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown"
                style="width: 280px; padding: 20px;">
                <div class="dropdown-item text-center">
                    @if (Auth::user()->avatar)
                        <img src="{{ asset('storage/photos/' . Auth::user()->avatar) }}"
                            class="img-fluid rounded-circle" style="width: 80px; height: 80px;">
                    @else
                        <img src="{{ asset('assets/user.png') }}" class="img-fluid rounded-circle"
                            style="width: 80px; height: 80px;">
                    @endif
                    <p class="text-muted" style="margin-bottom: 1px;">Login sebagai {{ Auth::user()->role }}</p>
                    <h5 class="mt-1">{{ Auth::user()->level->nama_level }}</h5>
                </div>
                <!-- Tombol Profile -->
                <a class="dropdown-item text-center btn profile-btn my-2" href="{{ url('profile') }}">Profile</a>

                <!-- Tombol Logout -->
                <a class="dropdown-item text-center btn logout-btn my-2" href="{{ url('logout') }}">Logout</a>
            </div>
        </li>

        </div>

        </li>
    </ul>
</nav>
<!-- /.navbar -->
