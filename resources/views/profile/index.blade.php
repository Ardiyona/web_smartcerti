@extends('layouts.template')

@section('title', '| Profile')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<!-- Tambahkan Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
   .nav-tabs .nav-link {
    background-color: #ffffff; /* Latar belakang putih */
    color: #375E97 !important; /* Teks biru dengan prioritas tinggi */
}

.nav-tabs .nav-link.active {
    background-color: #375E97; /* Warna latar belakang biru pada tab yang aktif */
    color: #ffffff !important; /* Teks putih pada tab yang aktif */
}

.nav-tabs .nav-link:hover {
    background-color: #f0f0f0; /* Warna latar belakang saat hover */
    color: #375E97 !important; /* Teks biru saat hover */
}

</style>

<div class="row">
    <!-- Card untuk Ganti Foto -->
    <div class="col-md-4">
        <div class="card border-0 rounded-lg">
            <div class="card-body text-center">
                @if($user->avatar)
                    <img src="{{ asset('storage/photos/' . $user->avatar) }}" 
                         class="img-thumbnail rounded-circle shadow-sm mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <img src="{{ asset('img/profile.png') }}" 
                         class="img-thumbnail rounded-circle shadow-sm mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                @endif

                <!-- Form untuk upload avatar -->
                <form method="POST" action="{{ url('profile/update', $user->user_id) }}" enctype="multipart/form-data">
                    @csrf {!! method_field('PUT') !!}
                    <div class="form-group mb-3">
                        <input id="avatar" type="file" class="form-control" name="avatar">
                    </div>
                    <div class="form-group row mb-3" style="display:none">
                            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>
                            <div class="col-md-8">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" required>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-3" style="display: none">
                            <label for="nama" class="col-md-4 col-form-label text-md-end">{{ __('Nama') }}</label>
                            <div class="col-md-8">
                                <input id="nama" type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" autocomplete="nama">
                                @error('nama_lengkap')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                    <button type="submit" class="btn" style="color: white; background-color: #375E97;">
                        {{ __('Ganti Foto Profil') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Nav Tab untuk Update Profile dan Ganti Password -->
    <div class="col-md-8">
        <div class="card border-0 rounded-lg">
            <div class="card-body">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs" id="profileTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" 
                                type="button" role="tab" aria-controls="profile" aria-selected="true">
                            {{ __('Update Profile') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" 
                                type="button" role="tab" aria-controls="password" aria-selected="false">
                            {{ __('Ganti Password') }}
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-4" id="profileTabContent">
                    <!-- Tab untuk Update Profile -->
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form method="POST" action="{{ url('profile/update', $user->user_id) }}">
                            @csrf {!! method_field('PUT') !!}
                            <!-- Input Username -->
                            <div class="form-group row mb-3">
                            <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>
                            <div class="col-md-8">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ $user->username }}" required autocomplete="username">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                            <!-- Input Nama Lengkap -->
                            <div class="form-group row mb-3">
                            <label for="nama" class="col-md-4 col-form-label text-md-end">{{ __('Nama') }}</label>
                            <div class="col-md-8">
                                <input id="nama" type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" autocomplete="nama">
                                @error('nama_lengkap')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                            <!-- Input Email -->
                            <div class="form-group row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                          
                            <!-- Input Nomor Telepon -->
                            <div class="form-group row mb-3">
                                <label for="no_telp" class="col-md-4 col-form-label text-md-end">{{ __('Nomor Telepon') }}</label>
                                <div class="col-md-8">
                                    <input id="no_telp" type="no_telp" class="form-control" name="no_telp" value="{{ $user->no_telp }}">
                                @error('no_telp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                            <!-- Bidang Minat -->
                            <div class="form-group row mb-3">
                                <label for="bidang_minat" class="col-md-4 col-form-label text-md-end">{{ __('Bidang Minat') }}</label>
                                <div class="col-md-8">
                                    <input id="bidang_minat" type="text" class="form-control" 
                                           value="{{ $user->detail_daftar_user_bidang_minat->isEmpty() 
                                                     ? __('Tidak ada bidang minat yang terdaftar.') 
                                                     : implode(', ', $user->detail_daftar_user_bidang_minat->pluck('nama_bidang_minat')->toArray()) }}" 
                                           readonly>
                                </div>
                            </div>
                            <!-- Mata Kuliah -->
                            <div class="form-group row mb-3">
                                <label for="mata_kuliah" class="col-md-4 col-form-label text-md-end">{{ __('Mata Kuliah') }}</label>
                                <div class="col-md-8">
                                    <input id="mata_kuliah" type="text" class="form-control" 
                                           value="{{ $user->detail_daftar_user_matakuliah->isEmpty() 
                                                     ? __('Tidak ada mata kuliah yang terdaftar.') 
                                                     : implode(', ', $user->detail_daftar_user_matakuliah->pluck('nama_matakuliah')->toArray()) }}" 
                                           readonly>
                                </div>
                            </div>
                            <!-- Tombol Update Profile -->
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn" style="color: white; background-color: #375E97;">
                                        {{ __('Update Profile') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tab untuk Ganti Password -->
                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                    <!-- Password Update -->
                    <form method="POST" action="{{ url('profile/update', $user->user_id) }}">
                        @csrf {!! method_field('PUT') !!}
                        <div class="form-group row mb-3">
                            <label for="old_password" class="col-md-4 col-form-label text-md-end">{{ __('Password Lama') }}</label>
                            <div class="col-md-8">
                                <input id="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" autocomplete="old-password">
                                @error('old_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password Baru') }}</label>
                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="password_confirmation" class="col-md-4 col-form-label text-md-end">{{ __('Konfirmasi Password') }}</label>
                            <div class="col-md-8">
                                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn" style="color: white; background-color: #375E97;">
                                    {{ __('Update Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>                                        
@endsection
