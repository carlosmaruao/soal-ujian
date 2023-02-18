@extends('layouts.app', ['title'=>'LSPR - edit password'])

@section('style')
<link href="{{ asset('css/custom-content.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 mb-5">
            @include('member.menu_member')
        </div>
        <div class="col-md-8 border p-5 rounded">
            <h5>Ganti Password</h5>
            <p>Biasakan merubah password anda dalam jangka waktu tertentu</p>
            <hr>
            <form method="post" action="{{ route('password.edit') }}">
                @method('patch')
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="old_password">Password Lama </label>
                            <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" id="old_password">
                            @error('old_password')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
                            @error('password')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password </label>
                            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation">
                            @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-lg btn-outline-secondary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection