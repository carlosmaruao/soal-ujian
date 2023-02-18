@extends('layouts.login-app', ['title'=>'LSPR - RegisterP'])
@section('style')
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
<link href="{{ asset('dist/css/adminlte.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 py-5">
            <div class="card-header font-weight-bold text-center">
                <h1> Member Register</h1>
            </div>
            <div class="card card-login shadow-sm rounded">
                <div class="text-center py-3"><img class="logo-1" src="{{asset('/images/logo.png')}}" alt=""></div>
                <hr>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" id="example">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label> 
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="off" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror 
                                </div>
                            </div>
    
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Nama Panggilan</label> 
                                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="off" autofocus>
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror 
                                </div>
                            </div>
                        </div>
                          
                        <div class="form-group">
                            <div class="form-group">
                                <label for="telepon">No Telepon</label> 
                                <input id="telepon" type="number" class="form-control @error('telepon') is-invalid @enderror" name="telepon" value="{{ old('telepon') }}" required autocomplete="telepon" autofocus>
                                @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                            </div>
                        </div> 

                        <div class="form-group">
                            <label for="email">Email</label>
 
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                        </div>
 
                        <div class="row">
                            <label for="pilKelas">Kelas</label>
                            <select class="custom-select @error('nmKelas') is-invalid @enderror" id="pilKelas" name="nmKelas"  data-toggle="tooltip" data-placement="top" title="Wilayah kerja utama adalah wilayah mayoritas tempat anda berprofesi sebagai musisi, bukan domisili/tempat tinggal anda. Asal serikat adalah jika anda sudah terlebih dahulu tergabung dalam serikat yg bergabung dengan FESMI.">  
                                <option disabled selected>Pilih Kelas</option> 
                                <option value="3" @if (old('nmKelas') == '3') selected="selected" @endif>3</option>
                                <option value="4" @if (old('nmKelas') == '4') selected="selected" @endif>Sudah Lulus</option>
                                <option value="5" @if (old('nmKelas') == '5') selected="selected" @endif>Sedang Bekerja</option>
                            </select>
                            @error('nmKelas')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div> 

                        <div class="row mt-5">
                            <div class="container">
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('Register') }}
                                    </button> 
                                    <a href="{{ __('login') }}" class="text-secondary">I already registered</a> 
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scriptBottom') 
@endsection
 
