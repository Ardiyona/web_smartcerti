@extends('layouts.template')

@section('title', '| Profile')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .nav-tabs .nav-link {
        background-color: #ffffff;
        color: #375E97 !important;
    }
    .nav-tabs .nav-link.active {
        background-color: #375E97;
        color: #ffffff !important;
    }
    .nav-tabs .nav-link:hover {
        background-color: #f0f0f0;
        color: #375E97 !important;
    }
</style>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 rounded-lg">
            <div class="card-body text-center">
            @if(session('status_foto'))
                    <div class="alert alert-success" style="background-color: #375E97; border-color: #375E97;">
                        {{ session('status_foto') }}
                    </div>
                @endif

                @if($user->avatar)
                    <img src="{{ asset('storage/photos/' . $user->avatar) }}" class="img-thumbnail rounded-circle shadow-sm mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <img src="{{ asset('img/profile.png') }}" class="img-thumbnail rounded-circle shadow-sm mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                @endif

                <form method="POST" action="{{ route('profile.updateAvatar', $user->user_id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input id="avatar" type="file" class="form-control mb-3" name="avatar">
                    <button type="submit" class="btn btn-primary" style="background-color: #375E97; border-color: #375E97;">{{ __('Ganti Foto Profil') }}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 rounded-lg">
            <div class="card-body">
                <ul class="nav nav-tabs" id="profileTab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">{{ __('Update Profile') }}</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">{{ __('Ganti Password') }}</button>
                    </li>
                </ul>

                <div class="tab-content mt-4" id="profileTabContent">
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            
                    @if(session('status_profile'))
                        <div class="alert alert-success" style="background-color: #375E97; border-color: #375E97; text-align: center;">
                            {{ session('status_profile') }}
                        </div>
                    @endif

                        <form method="POST" action="{{ route('profile.updateProfile', $user->user_id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group row mb-3">
                                <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>
                                <div class="col-md-8">
                                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username', $user->username) }}" required autocomplete="username">
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="nama" class="col-md-4 col-form-label text-md-end">{{ __('Nama Lengkap') }}</label>
                                <div class="col-md-8">
                                    <input id="nama" type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" autocomplete="nama">
                                    @error('nama_lengkap')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-3">
                                <label for="no_telp" class="col-md-4 col-form-label text-md-end">{{ __('Nomor Telepon') }}</label>
                                <div class="col-md-8">
                                    <input id="no_telp" type="text" class="form-control @error('no_telp') is-invalid @enderror" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}">
                                    @error('no_telp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="bidang_minat" class="col-md-4 col-form-label text-md-end">{{ __('Bidang Minat') }}</label>
                                <div class="col-md-8">
                                    <input id="bidang_minat" type="text" class="form-control" value="{{ $user->detail_daftar_user_bidang_minat->isEmpty() ? __('Tidak ada bidang minat yang terdaftar.') : implode(', ', $user->detail_daftar_user_bidang_minat->pluck('nama_bidang_minat')->toArray()) }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="mata_kuliah" class="col-md-4 col-form-label text-md-end">{{ __('Mata Kuliah') }}</label>
                                <div class="col-md-8">
                                    <input id="mata_kuliah" type="text" class="form-control" value="{{ $user->detail_daftar_user_matakuliah->isEmpty() ? __('Tidak ada mata kuliah yang terdaftar.') : implode(', ', $user->detail_daftar_user_matakuliah->pluck('nama_matakuliah')->toArray()) }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" style="background-color: #375E97; border-color: #375E97;">{{ __('Update Profile') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                    @if(session('status_password'))
                        <div class="alert alert-success" style="background-color: #375E97; border-color: #375E97; text-align: center;">
                            {{ session('status_password') }}
                        </div>
                    @endif
                    

                        <form method="POST" action="{{ route('profile.updatePassword',$user->user_id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group row mb-3">
                                <label for="old_password" class="col-md-4 col-form-label text-md-end">{{ __('Password Lama') }}</label>
                                <div class="col-md-8">
                                    <input id="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" required autocomplete="current-password">
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
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="password_confirmation" class="col-md-4 col-form-label text-md-end">{{ __('Konfirmasi Password Baru') }}</label>
                                <div class="col-md-8">
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary" style="background-color: #375E97; border-color: #375E97;">
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
