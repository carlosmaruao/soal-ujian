@extends('layouts.login-app', ['title'=>'LSPR - Login'])
@section('style')
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
<link href="{{ asset('dist/css/adminlte.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 py-5">
            <div class="card-header font-weight-bold text-center">
                <h1>Login Member</h1>
            </div>

            <div class="card shadow-lg rounded-lg">
                <div class="text-center py-3"><img class="logo-1" src="{{asset('/images/logo.png')}}" alt=""></div>
                <hr>
                <div class="card-body login-card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="nim" class="col-form-label">NIM</label>
                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="off" autofocus>

                            @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-form-label">Password</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="off">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>Data tidak valid</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group row mb-0 py-3">
                            <div class="container">
                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-danger">
                                        {{ __('Login') }}
                                    </button> 
                                        <a href="{{ __('register') }}" class="text-secondary">Registered</a> 
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
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // $("#password").keyup(function() {
        //     var that = this,
        //         value = $(this).val();
        //     var email = document.getElementById('email');
        //     email.value = value + '@gmail.com';

        // });
    });
</script>
@endsection