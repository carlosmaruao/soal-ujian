@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="col-8 mt-3">
            <div class="card">
                <div class="card-body">  
                    <form method="post" action="{{ route('admin.users.update', $user) }}"  >
                        @method('patch')
                        @csrf  
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">Nama </label>
                                    <input type="password" name="name"  
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                    @enderror 
                                </div>  
                            </div> 
                        </div>   
                        @csrf  
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="roles">Role </label>
                                    <div>
                                        @foreach ($roles as $role)
                                            <div class="form-check form-check-inline" id="roles">
                                                <input class="form-check-input" type="checkbox" name="roles[]" id="{{$role->id}}" value="{{ $role->id }}"
                                                @if ($user->roles->pluck('id')->contains($role->id))
                                                checked 
                                                @endif>
                                                <label class="form-check-label" for="{{$role->id}}">{{$role->name}}</label>
                                            </div>
                                        @endforeach  
                                    </div>
                                </div>  
                            </div> 
                        </div>   
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-danger">Cancel </a>
                    </form>
                </div>
            </div>
        </div> 
    </div>
@endsection