@extends('layouts.backend')

@section('title','View Laboratory Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch/laboratories/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch/laboratories/user')}}">Laboratories</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
            <h5 class="page-title">View Laboratory Details</h5>
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
                                                {{$data->mobile_no_country_code}} 
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
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Date of Joining</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->created_at))
                                                {{Helper::getDateTimeFormate($data->created_at)}}
                                            @endif 
                                        </dd>
                                    </div>
                                    @if(!empty($data->approved_date))
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Date of Approval</label></dt>
                                        <dd class="col-sm-7"> 
                                            {{Helper::getDateTimeFormate($data->approved_date)}}
                                        </dd>
                                    </div>
                                    @endif 
                                    @if(!empty($data->profile_completed_progress))
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Profile Progress</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if($data->profile_completed_progress == '100')
                                                <div class="badge badge-success">{{$data->profile_completed_progress}}%</div>                                            
                                            @else
                                                <div class="badge badge-danger">{{$data->profile_completed_progress}}%</div>
                                            @endif
                                        </dd>
                                    </div>
                                    @endif 
                                    @if(!empty($data->profile_required_fields) && count($data->profile_required_fields) > 0)
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Required Filed Pending</label></dt>
                                        <dd class="col-sm-7"> 
                                            {{implode(", ",$data->profile_required_fields)}}
                                        </dd>
                                    </div>
                                    @endif 
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($data->userDetails)) 
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">User Extra Details</h4>
                                <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Laboratory Name</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->clinic_name))
                                               {{$data->userDetails->clinic_name}}
                                            @endif 
                                        </dd>
                                    </div>          
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Laboratory Country</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->clinic_country))
                                                {{$data->userDetails->clinic_country}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Laboratory State</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->clinic_state))
                                                {{$data->userDetails->clinic_state}} 
                                            @endif 
                                        </dd>
                                    </div>                          
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Laboratory City</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->clinic_city))
                                                {{$data->userDetails->clinic_city}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Laboratory Locality</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->clinic_locality))
                                                {{$data->userDetails->clinic_locality}} 
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
                                        <dt class="col-sm-5"><label>DOB</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->dob))
                                                {{Helper::getDateFormate($data->userDetails->dob)}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Home Visit Charge</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->home_consultation_charge))
                                                {{$currency_symbol.$data->userDetails->home_consultation_charge}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Qualification Certificate</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->qualification_certificate))
                                                @php ($qualification_certificate = json_decode($data->userDetails->qualification_certificate))
                                                @if(count($qualification_certificate) > 0)  
                                                @foreach($qualification_certificate as $val)  
                                                    @php ($ext = pathinfo($val, PATHINFO_EXTENSION))
                                                    @if($ext == 'pdf')
                                                    <div class="col-sm-3">
                                                        <a download href="{{$val}}">
                                                            <img  width="100px" height="100px" src="{{ asset('admin/images/pdf_image.png') }}">
                                                        </a>
                                                    </div>
                                                    @else
                                                    <div class="col-sm-3">
                                                        <a download href="{{$val}}">
                                                            <img src="{{$val}}" width="100px" download height="100px">
                                                        </a>
                                                    </div>
                                                    @endif
                                                @endforeach
                                                @endif 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Practicing Licence</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->practicing_licence))
                                                @php ($ext = pathinfo($data->userDetails->practicing_licence, PATHINFO_EXTENSION))
                                                @if($ext == 'pdf')
                                                <div class="row col-sm-3">
                                                    <a download href="{{$data->userDetails->practicing_licence}}">
                                                        <img  width="100px" height="100px" src="{{ asset('admin/images/pdf_image.png') }}">
                                                    </a>
                                                </div>
                                                @else
                                                <div class="row col-sm-3">
                                                    <a download href="{{$data->userDetails->practicing_licence}}">
                                                        <img src="{{$data->userDetails->practicing_licence}}" width="100px" download height="100px">
                                                    </a>
                                                </div>
                                                @endif
                                            @endif 
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(count($data->userAvailableTime) > 0)
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">Available Time Details
                                            @if(!empty($data->userDetails->availability))
                                               <span class="badge badge-info">Available</span>
                                            @else
                                                <span class="badge badge-danger">Not Available</span>
                                            @endif 
                                </h4>
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
                                                    <td>{{ Helper::getUserTimezoneConvertFormate($user_avalibale_time->start_time, $data->user_timezone)}}</td>
                                                    <td>{{ Helper::getUserTimezoneConvertFormate($user_avalibale_time->end_time, $data->user_timezone)}}</td>
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
                                                    <th>End Year</th>
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data->userBankAccount as $user_bank_account) 
                                                <tr>
                                                    <td>{{$user_bank_account->bank_name}}</td>
                                                    <td>{{$user_bank_account->bank_branch_name}}</td>
                                                    <td>{{$user_bank_account->account_number}}</td>
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
    var user_url = "{{url('/donotezzycaretouch/user')}}";
     var data_obj = {};
</script>
<script src="{{ asset('js/admin/provider.js') }}" ></script>
@endsection