@extends('layouts.backend')

@section('title','Edit Patient Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/customer/patient')}}">Patients</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
            <h5 class="page-title">Edit Patient Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" action="{{ url('user') }}"  id="user_form" name="user_form" enctype="multipart/form-data">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <input type="hidden" required class="form-control" name="category_id" value="{{$data->category_id}}"> 
                        <div class="row">
                            <dt class="col-sm-5"><label>Profile Image</label></dt>
                            <dd class="col-sm-7"> 
                                <input type="file" id="profile_image" name="profile_image" class="form-control" accept="image/*" onchange="return fileValidation('profile_image')"> 
                                <div id="profile_imagePreview">
                                    @if(!empty($data->profile_image)) 
                                    <img src="{{$data->profile_image}}"  style="max-width: 100%;height:100px;display:block;">
                                    @endif
                                </div>
                            </dd>
                        </div>
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">User Details</h4>
                                <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-5"><label>First Name</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text" required class="form-control" name="first_name" value="{{$data->first_name}}"> 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Last Name</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text" required class="form-control" name="last_name" value="{{$data->last_name}}"> 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Email</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="email" required class="form-control" name="email" value="{{$data->email}}"> 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Gender</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="radio" id="gender_male" name="gender" required value="0" {{($data->gender == '0') ? 'checked' :''}}><label for="gender_male">Male</label>
                                            <input type="radio" id="gender_female" name="gender" value="1" {{($data->gender == '1') ? 'checked' :''}}><label for="gender_female">Female</label>
                                        </dd>
                                    </div>          
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($data->userDetails)) 
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <input type="hidden" required class="form-control" name="userDetails[id]" value="{{$data->userDetails->id}}"> 
                                <input type="hidden" required class="form-control" name="userDetails[user_id]" value="{{$data->userDetails->user_id}}"> 
                                <h4 class="mt-0 mb-0 header-title">User Extra Details</h4>
                                <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Date of Birth</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="date" required class="form-control" name="userDetails[dob]" value="{{$data->userDetails->dob}}"> 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Blood Group</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text" required class="form-control" name="userDetails[blood_group]" value="{{$data->userDetails->blood_group}}"> 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Marital Status</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="radio"  id="marital_status_married" required name="userDetails[marital_status]" value="1" {{($data->userDetails->marital_status == '1') ? 'checked' :''}}><label for="marital_status_married">Married</label>
                                            <input type="radio" id="marital_status_single" name="userDetails[marital_status]" value="0" {{($data->userDetails->marital_status == '0') ? 'checked' :''}}><label for="marital_status_single">Single</label>
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Height</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text" required class="form-control" name="userDetails[height]" value="{{$data->userDetails->height}}"> 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Weight</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text" required class="form-control" name="userDetails[weight]" value="{{$data->userDetails->weight}}"> 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Emergency Contact</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text" required class="form-control" name="userDetails[emergency_contact]" value="{{$data->userDetails->emergency_contact}}"> 
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                       

                        <div class="row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-info waves-effect m-l-5">
                                    Update
                                </button>
                                <a href="#" onclick="history.go(-1)">
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
    var user_url = "{{url('/user')}}";
     var data_obj = {};
</script>
<script src="{{ asset('js/admin/provider.js') }}" ></script>
@endsection