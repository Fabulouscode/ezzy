@extends('layouts.backend')

@section('title',array_key_exists($provider, $provider_names) ? 'View '.$provider_names[$provider].' Details': 'View Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/user')}}">{{array_key_exists($provider, $provider_names) ? $provider_names[$provider]: ''}}</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
            <h5 class="page-title">array_key_exists($provider, $provider_names) ? 'View '.$provider_names[$provider].' Details': 'View Details'</h5>
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
                                        <dt class="col-sm-5"><label>Mobil No.</label></dt>
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
                                            {{$data->wallet_balance}} 
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
                                        <dt class="col-sm-5"><label>Pharmacy Name</label></dt>
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
                                        <dt class="col-sm-5"><label>Pharmacy Name</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->clinic_name))
                                                {{$data->userDetails->clinic_name}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Pharmacy City</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->clinic_city))
                                                {{$data->userDetails->clinic_city}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Pharmacy Locality</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->clinic_locality))
                                                {{$data->userDetails->clinic_locality}} 
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
                                        <dt class="col-sm-5"><label>Delivery Charge</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->delivery_charge))
                                                {{$data->userDetails->delivery_charge}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Regstration Certificate</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->regstration_certificate))
                                                <img src="{{$data->userDetails->regstration_certificate}}" width="100px" height="100px">
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Pharmacist Certificate</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->pharmacist_certificate))
                                                <img src="{{$data->userDetails->pharmacist_certificate}}" width="100px" height="100px">
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
                                <h4 class="mt-0 mb-0 header-title">Available Time Details</h4>
                                <div class="card-detail-list">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Day</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data->userAvailableTime as $user_avalibale_time) 
                                                <tr>
                                                    <td>{{array_key_exists($user_avalibale_time->day, $days) ? $days[$user_avalibale_time->day]: ''}}</td>
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
                                <a  href="#" onclick="history.go(-1)">
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