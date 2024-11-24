@extends('layouts.template')
@section('content')
<div class="container my-0">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>{{ __('Profile') }}</h4>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            @if($user->avatar)
                                <img src="{{ asset('storage/photos/'.$user->avatar) }}" class="img-thumbnail rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <img src="{{ asset('img/profile.png') }}" class="img-thumbnail rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover;">
                            @endif
                            <div class="mt-2">
                                <h5>{{ $user->nama }}</h5>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <form method="POST" action="{{ url('profile/update', $user->user_id) }}" enctype="multipart/form-data">
                                @method('POST')
                                @csrf
                                
                                <div class="form-group row mb-3">
                                    <label for="username" class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>
                                    <div class="col-md-7">
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
                                    <div class="col-md-7">
                                        <input id="nama" type="text" class="form-control @error('nama_lengkap') is-invalid @enderror" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" autocomplete="nama">
                                        @error('nama_lengkap')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label for="old_password" class="col-md-4 col-form-label text-md-end">{{ __('Password Lama') }}</label>
                                    <div class="col-md-7">
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
                                    <div class="col-md-7">
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
                                    <div class="col-md-7">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label for="avatar" class="col-md-4 col-form-label text-md-end">{{ __('Ganti Foto Profil') }}</label>
                                    <div class="col-md-7">
                                        <input id="avatar" type="file" class="form-control" name="avatar">
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
            </div>
        </div>
    </div>
</div>
@endsection