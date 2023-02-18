@extends('layouts.app', ['title'=>'LSPR - MEMBER'])

@section('style')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    @if (session('status')) 
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if (isset($errors) && $errors->any())
        <div class="alert alert-success">
            @foreach ($errors->all() as $item) 
               <li> {{$item}} </li>
            @endforeach  
        </div>
    @endif
    @if (session()->has('failures'))
    @foreach (session()->get('failures') as $item)
    <li>{{$item->row()}}</li>
    <li>{{$item->attribute()}}</li>
    <li>
        @foreach ($item->errors() as $zz)
    <li>{{$zz}}</li>
    @endforeach
    </li>
    <li>{{$item->values()[$item->attribute()]}}</li>
    @endforeach
    @endif
    <form method="POST" action="{{route('admin.post.upload')}}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12 no-border p-3">
                <h3 class="mb-5">Upload Data Mahasiswa</h3>
                <div class="form-group"> 
                    <input type="file" class="form-control-file" name="file" id="fileUpload">
                </div>
                <hr>
                <button type="submit" class="btn btn-lg btn-outline-secondary mt-3">Upload Data</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scriptBottom')
<script>
</script>
@endsection