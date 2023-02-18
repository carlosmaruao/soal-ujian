@extends('layouts.app')
@section('style')
    
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container box-content">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-login shadow-sm rounded">
                <div class="card-header font-weight-bold">RESET PASSWORD</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        

                        <div class="form-group ">
                            <label for="email" class="col-form-label">{{ __('E-Mail Address') }}</label>
 
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror 
                        </div>

                        <div class="form-group row py-4">
                             
                            <div class="col-12">
                                <button type="submit" class="btn btn-purple btn ">
                                    {{ __('Send Password Reset') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
