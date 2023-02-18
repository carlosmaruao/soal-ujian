@extends('layouts.app', ['title'=>'FESMI - MEMBER']) 

@section('style')
<link href="{{ asset('css/card.css') }}" rel="stylesheet">
<link href="{{ asset('css/custom-content.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container tampil-berita">
    <div class="row">
        <div class="col-md-4 mb-5">
            @include('member.menu_member') 
        </div>
        <div class="col-md-8 p-1">
            @include('member.card.card')
        </div>
    </div>
</div>
@endsection