@extends('layouts.backend')

@section('title','View Health Care Provider Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/healthcare/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/healthcare/user')}}">Health Care Providers</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
            <h5 class="page-title">View Health Care Provider Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST"  id="user_form" name="user_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <dt class="col-sm-5"><label>Profile Image</label></dt>
                            <dd class="col-sm-7"> 
                                <img src="{{$data->profile_image}}" style="max-width: 100%;height:100px;display:block;">
                            </dd>
                        </div>
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">User Details</h4>
                                <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-5"><label>HCP Type</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->categoryParent))
                                                {{$data->categoryParent->name}}
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>HCP Subtype</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->categoryChild))
                                                {{$data->categoryChild->name}}
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>User Name</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->first_name))
                                                {{$data->first_name}} {{$data->last_name}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Email</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->email))
                                                {{$data->email}}
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Mobile No.</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->mobile_no))
                                                {{$data->mobile_no}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Gender</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(isset($data->gender))
                                                {{$data->gender == '0' ? 'Male' : 'Female'}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Wallet Balance</label></dt>
                                        <dd class="col-sm-7"> 
                                            {{$currency_symbol.$data->wallet_balance}} 
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($data->userDetails)) 
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">User Extra Details</h4>
                                <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Clinic & Hospital Name</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->clinic_hospital_name))
                                                {{$data->userDetails->clinic_hospital_name}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Registration Number</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->registration_no))
                                                {{$data->userDetails->registration_no}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Registration Council</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->registration_council))
                                                {{$data->userDetails->registration_council}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Registration Year</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->registration_year))
                                                {{$data->userDetails->registration_year}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Clinic Name</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->clinic_name))
                                                {{$data->userDetails->clinic_name}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Clinic City</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->clinic_city))
                                                {{$data->userDetails->clinic_city}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Clinic Locality</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->clinic_locality))
                                                {{$data->userDetails->clinic_locality}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Years of Experience</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->total_experiance_year))
                                                {{$data->userDetails->total_experiance_year}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    
                                    <div class="row">
                                        <dt class="col-sm-5"><label>About Us</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->about_us))
                                                {{$data->userDetails->about_us}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Country</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->country))
                                                {{$data->userDetails->country}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>City</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->city))
                                                {{$data->userDetails->city}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Address</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->address))
                                                {{$data->userDetails->address}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Postalcode</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->pincode))
                                                {{$data->userDetails->pincode}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>DOB</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->dob))
                                                {{Helper::getDateFormate($data->userDetails->dob)}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    @if($data->category_id == '4')
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Normal Fees</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->normal_fees))
                                                {{$currency_symbol.$data->userDetails->normal_fees}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Urgent Fees</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->urgent_fees))
                                                {{$currency_symbol.$data->userDetails->urgent_fees}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    @endif
                                    @if($data->category_id == '5')
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Hour Fees</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->fees_hour))
                                                {{$currency_symbol.$data->userDetails->fees_hour}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Day Fees</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->fees_day))
                                                {{$currency_symbol.$data->userDetails->fees_day}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Home Visit Fees</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->home_visit_fees))
                                                {{$currency_symbol.$data->userDetails->home_visit_fees}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    @if($data->category_id == '4' || $data->category_id == '5')
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Qualification Certificate</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->qualification_certificate))
                                                <img src="{{$data->userDetails->qualification_certificate}}" width="100px" height="100px">
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Practicing Licence</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->practicing_licence))
                                                <img src="{{$data->userDetails->practicing_licence}}" width="100px" height="100px">
                                            @endif 
                                        </dd>
                                    </div>
                                    @endif
                                    @if($data->category_id == '4')
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Health Facility Certificate</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->health_facility_certificate))
                                                <img src="{{$data->userDetails->health_facility_certificate}}" width="100px" height="100px">
                                            @endif 
                                        </dd>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(count($data->userAvailableTime) > 0)
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">Available Time Details</h4>
                                <div class="card-detail-list">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Day</th>
                                                    <th>Appointment Type</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data->userAvailableTime as $user_avalibale_time) 
                                                <tr>
                                                    <td>{{$user_avalibale_time->day_name}}</td>
                                                    <td>{{$user_avalibale_time->appointment_type_name}}</td>
                                                    <td>{{$user_avalibale_time->start_time}}</td>
                                                    <td>{{$user_avalibale_time->end_time}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                      
                        @if(count($data->userExperiance) > 0)
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">Experiance Details</h4>
                                <div class="card-detail-list">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Descritption</th>
                                                    <th>Start Year</th>
                                                    <th>End Year</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data->userExperiance as $user_experiance) 
                                                <tr>
                                                    <td>{{$user_experiance->name}}</td>
                                                    <td>{{$user_experiance->descritption}}</td>
                                                    <td>{{$user_experiance->start_year}}</td>
                                                    <td>{{$user_experiance->currently_work == '1' ? 'Currently Working' : $user_experiance->end_year}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(count($data->userEduction) > 0)
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">Education Details</h4>
                                <div class="card-detail-list">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>College Name</th>
                                                    <th>Degree Name</th>
                                                    <th>Start Year</th>
                                                    <th>End Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data->userEduction as $user_eduction) 
                                                <tr>
                                                    <td>{{$user_eduction->college_name}}</td>
                                                    <td>{{$user_eduction->degree_name}}</td>
                                                    <td>{{$user_eduction->start_year}}</td>
                                                    <td>{{$user_eduction->currently_work == '1'? 'Current Pursuing':$user_eduction->end_year}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                       
                        @if(count($data->userBankAccount) > 0)
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">Bank Account Details</h4>
                                <div class="card-detail-list">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Bank Name</th>
                                                    <th>Bank Branch_Name</th>
                                                    <th>Account Number</th>
                                                    <th>IFSC Code</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data->userBankAccount as $user_bank_account) 
                                                <tr>
                                                    <td>{{$user_bank_account->bank_name}}</td>
                                                    <td>{{$user_bank_account->bank_branch_name}}</td>
                                                    <td>{{$user_bank_account->account_number}}</td>
                                                    <td>{{$user_bank_account->ifsc_code}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="form-group col-md-12">
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