@extends('layouts.template')
@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<div class="container my-0">
    <div class="row">
        <!-- Card untuk Informasi Profil -->
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg mb-4">
                <div class="card-header bg-profile text-white text-center">
                    <h4>{{ __('Informasi Profil') }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ url('profile/update', $user->user_id) }}" enctype="multipart/form-data">
                        @csrf
                        
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
                        <!-- Bidang Minat -->
                        <div class="form-group row mb-3">
                            <label for="bidang_minat" class="col-md-4 col-form-label text-md-end">{{ __('Bidang Minat') }}</label>
                            <div class="col-md-8">
                                @if($user->detail_daftar_user_bidang_minat->isEmpty())
                                    <input id="bidang_minat" type="text" class="form-control" value="{{ __('Tidak ada bidang minat yang terdaftar.') }}" readonly>
                                @else
                                    <input id="bidang_minat" type="text" class="form-control" value="{{ implode(', ', $user->detail_daftar_user_bidang_minat->pluck('nama_bidang_minat')->toArray()) }}" readonly>
                                @endif
                            </div>
                        </div>
                        <!-- Mata Kuliah -->
                        <div class="form-group row mb-3">
                            <label for="mata_kuliah" class="col-md-4 col-form-label text-md-end">{{ __('Mata Kuliah') }}</label>
                            <div class="col-md-8">
                                @if($user->detail_daftar_user_matakuliah->isEmpty())
                                    <input id="mata_kuliah" type="text" class="form-control" value="{{ __('Tidak ada mata kuliah yang terdaftar.') }}" readonly>
                                @else
                                    <input id="mata_kuliah" type="text" class="form-control" value="{{ implode(', ', $user->detail_daftar_user_matakuliah->pluck('nama_matakuliah')->toArray()) }}" readonly>
                                @endif
                            </div>
                        </div>
                        
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
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                            <div class="col-md-8">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary px-4 py-2">
                                    {{ __('Update Profile') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Card untuk Ganti Foto -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-profile text-white text-center">
                    <h4>{{ __('Ganti Foto Profil') }}</h4>
                </div>
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ asset('storage/photos/'.$user->avatar) }}" class="img-thumbnail rounded-circle shadow-sm mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <img src="{{ asset('img/profile.png') }}" class="img-thumbnail rounded-circle shadow-sm mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @endif
                    <form method="POST" action="{{ url('profile/update/avatar', $user->user_id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <input id="avatar" type="file" class="form-control" name="avatar">
                        </div>
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            {{ __('Update Foto') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
