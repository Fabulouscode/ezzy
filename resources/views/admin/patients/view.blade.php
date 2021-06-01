@extends('layouts.backend')

@section('title','View Patient Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/customer/patient')}}">Patients</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
            <h5 class="page-title">View Patient Details</h5>
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
                                        <dt class="col-sm-5"><label>First Name</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->first_name))
                                                {{$data->first_name}}
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Last Name</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->last_name))
                                                {{$data->last_name}}
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
                                </div>
                            </div>
                        </div>
                        
                        @if(!empty($data->userDetails)) 
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">User Extra Details</h4>
                                <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Date of Birth</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->dob))
                                                {{Helper::getDateFormate($data->userDetails->dob)}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Blood Group</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->blood_group))
                                                {{$data->userDetails->blood_group}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Marital Status</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(isset($data->userDetails->marital_status))
                                                {{ ($data->userDetails->marital_status == '1') ? 'Married' : 'Single'}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Height</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->height))
                                                {{$data->userDetails->height}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Weight</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->weight))
                                                {{$data->userDetails->weight}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Emergency Contact</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->emergency_contact))
                                                {{$data->userDetails->emergency_contact}} 
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Allergies</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->userDetails->allergies))
                                                {{$data->userDetails->allergies}} 
                                            @endif 
                                        </dd>
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

                        @if(count($data->userLocation) > 0)
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">Location Details</h4>
                                <div class="card-detail-list">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>User Name</th>
                                                    <th>Mobile No.</th>
                                                    <th>Email</th>
                                                    <th>Address</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data->userLocation as $user_location) 
                                                <tr>
                                                    <td>{{$user_location->name}}</td>
                                                    <td>{{$user_location->mobile_no}}</td>
                                                    <td>{{$user_location->email}}</td>
                                                    <td>{{$user_location->address}}</td>
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