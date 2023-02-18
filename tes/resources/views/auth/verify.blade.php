@extends('layouts.app')
@section('style')
    
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
@endsection

@section('content') 
<div class="container box-content">
    <div class="row">
        <div class="col-md-12"> 
            <div class="card box-email-verify"> 
                <div class="card-body"> 
                    <div class="alert alert-info mb-4">
                        <p>Saat ini anda belum melakukan Verifikasi Email, kami sudah mengirim kan link verifikasi ke email anda.
                        </p>Atau anda bisa <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                           @csrf
                           <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Verifikasi Ulang ') }}</button>.
                       </form>  
                    </div>    
                    <h5 class="card-title">Hallo {{auth()->user()->name}}</h5>
                    <p class="card-text"> 
                        Terima Kasih sudah melakukan registrasi di fesmi.id
                    </p>
                    <p>Selamat Datang di Dashboard Keanggotaan <strong>FEDERASI SERIKAT MUSISI INDONESIA / SERIKAT MUSISI INDONESIA </strong>
                    </p>
                </div>
            </div> 
        </div> 
    </div>
     {{--<div class="row justify-content-center">sd
         <div class="col-12 col-lg-4">
            <div class="list-group shadow mb-3 p-4 rounded-lg"> 
                <p>Terima kasih sudah ikut bergabung dan melakukan registrasi </p> 
            </div>
            <ul class="list-group shadow rounded-lg"> 
                <li class="list-group-item">Dapibus ac facilisis in</li>
                <li class="list-group-item">Morbi leo risus</li>
                <li class="list-group-item">Porta ac consectetur ac</li>
                <li class="list-group-item">Vestibulum at eros</li>
            </ul>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},
                    
                </div>
            </div> 
        </div>  
    </div>--}}
</div>
@endsection
