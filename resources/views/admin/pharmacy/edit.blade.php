@extends('layouts.backend')

@section('title','Edit Pharmacy Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/user')}}">Pharmacy</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
            <h5 class="page-title">Edit Pharmacy Details</h5>
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
                                <input type="file" class="form-control" name="profile_image" id="profile_image" accept="image/*" onchange="return fileValidation('profile_image')">
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
                                        <dt class="col-sm-5"><label>HCP Subtype</label></dt>
                                        <dd class="col-sm-7"> 
                                            <select id="subcategory_id"  type="text" required class="form-control" name="subcategory_id" >
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}"  {{ !empty($data->subcategory_id) && $category->id == $data->subcategory_id ? 'selected' : '' }}>{{$category->name}}</option>
                                                @endforeach
                                            </select>
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>First Name</label></dt>
                                        <dd class="col-sm-7"> 
                                           <input type="text" required class="form-control" name="first_name" value="{{$data->first_name}}"> 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Last Name</label></dt>
                                        <dd class="col-sm-7"> 
                                           <input type="text"  class="form-control" name="last_name" value="{{$data->last_name}}"> 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Email</label></dt>
                                        <dd class="col-sm-7"> 
                                          <input type="text" required class="form-control" name="email" value="{{$data->email}}"> 
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
                                        <dt class="col-sm-5"><label>Registration Number</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text" required class="form-control" name="userDetails[registration_no]" value="{{$data->userDetails->registration_no}}">                                           
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Registration Council</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text" required class="form-control" name="userDetails[registration_council]" value="{{$data->userDetails->registration_council}}">
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Registration Year</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="number" required class="form-control" name="userDetails[registration_year]" value="{{$data->userDetails->registration_year}}">
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Pharmacy Name</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text" required class="form-control" name="userDetails[clinic_name]" value="{{$data->userDetails->clinic_name}}">
                                        </dd>
                                    </div>                                    
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Pharmacy Country</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text"  class="form-control" name="userDetails[clinic_country]" value="{{$data->userDetails->clinic_country}}">
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Pharmacy State</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text"  class="form-control" name="userDetails[clinic_state]" value="{{$data->userDetails->clinic_state}}">
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Pharmacy City</label></dt>
                                        <dd class="col-sm-7"> 
                                             <input type="text"  class="form-control" name="userDetails[clinic_city]" value="{{$data->userDetails->clinic_city}}">
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Pharmacy Locality</label></dt>
                                        <dd class="col-sm-7"> 
                                             <input type="text"  class="form-control" name="userDetails[clinic_locality]" value="{{$data->userDetails->clinic_locality}}">
                                        </dd>
                                    </div>                            
                                    <div class="row">
                                        <dt class="col-sm-5"><label>About Us</label></dt>
                                        <dd class="col-sm-7"> 
                                            <textarea required class="form-control" name="userDetails[about_us]" >{{$data->userDetails->about_us}}</textarea>
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Country</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text" required class="form-control" name="userDetails[country]" value="{{$data->userDetails->country}}">
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>City</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text"  class="form-control" name="userDetails[city]" value="{{$data->userDetails->city}}">
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Address</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text"  class="form-control" name="userDetails[address]" value="{{$data->userDetails->address}}">
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Delivery Charge</label></dt>
                                        <dd class="col-sm-7"> 
                                             <input type="text" required class="form-control" name="userDetails[delivery_charge]" value="{{$data->userDetails->delivery_charge}}">
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Qualification Certificate</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="file" class="form-control" name="userDetails[qualification_certificate]" id="qualification_certificate" accept="image/*" onchange="return fileValidation('qualification_certificate')">
                                            <div id="qualification_certificatePreview">
                                            @if(!empty($data->userDetails->qualification_certificate))
                                                <img src="{{$data->userDetails->qualification_certificate}}" width="100px" height="100px">
                                            @endif 
                                            </div>
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Practicing Licence</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="file" class="form-control" name="userDetails[practicing_licence]" id="practicing_licence" accept="image/*" onchange="return fileValidation('practicing_licence')">
                                            <div id="practicing_licencePreview">
                                            @if(!empty($data->userDetails->practicing_licence))
                                                <img src="{{$data->userDetails->practicing_licence}}" width="100px" height="100px">
                                            @endif 
                                            </div>
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