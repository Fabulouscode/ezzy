@extends('layouts.backend')

@if(!empty($data->id))
    @section('title','Edit Admin Details')
@else
    @section('title','Add Admin Details')
@endif

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/category')}}">Admin List</a></li>
                    <li class="breadcrumb-item active">{{!empty($data->id) ? 'Edit' : 'Add' }}</li>
                </ol>
            </div>
            <h5 class="page-title">{{!empty($data->id) ? 'Edit' : 'Add' }} Admin Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" action="{{ url('admin/users') }}" id="user_form" name="user_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Name</label>
                                <input type="text" required placeholder="Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{!empty($data->name) ? $data->name : old('name') }}" autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label>Email</label>
                                <input id="email"  required parsley-type="email" type="email" placeholder="Email"  class="form-control @error('email') is-invalid @enderror" name="email" value="{{!empty($data->email) ? $data->email : old('email') }}" autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Password</label>
                                <input id="password" required data-parsley-minlength="6" placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror" value="{{!empty($data->password) ? '**********' : '' }}"  name="password" autocomplete="password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label>Confirm Password</label>
                                <input id="password-confirm" required data-parsley-equalto="#password" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" value="{{!empty($data->password) ? '**********' : '' }}" autocomplete="new-password">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Role</label>
                                <select id="role_id" required name="role_id" placeholder="Role" class="form-control @error('role_id') is-invalid @enderror">
                                   <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}"  {{ !empty($data->role_id) && $role->id == $data->role_id ? 'selected' : '' }}>{{$role->role_name}}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>                            
                        </div>


                        <div class="row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{ url('admin/users') }}">
                                    <button type="button" class="btn btn-secondary waves-effect m-l-5">
                                        Cancel
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div> <!-- end col -->
    </div>
</div>
@endsection

@section('script')
<script>
    var admin_user_url = "{{url('/admin/users')}}";
</script>
<script src="{{ asset('js/admin/admin_user.js') }}" ></script>
@endsection