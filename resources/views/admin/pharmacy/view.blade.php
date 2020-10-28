@extends('layouts.backend')

@section('title','View Pharmacy Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/users')}}">Pharmacy</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
            <h5 class="page-title">View Pharmacy Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" action="{{ url('user') }}" id="user_form" name="user_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Category</label>
                                <select disabled id="category_id"  type="text" class="form-control" name="category_id" >
                                    <option value="">Select Parent Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}"  {{ !empty($data->category_id) && $category->id == $data->category_id ? 'selected' : '' }}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group  col-md-4">
                                <label>Subcategory</label>
                                <select disabled id="subcategory_id"  type="text" class="form-control" name="subcategory_id" >
                                    <option value="">Select Subcategory</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}"  {{ !empty($data->subcategory_id) && $category->id == $data->subcategory_id ? 'selected' : '' }}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group  col-md-4">
                                <label>Wallet Balance</label>
                                <input disabled type="text"  class="form-control" name="wallet_balance" value="{{$data->wallet_balance}}" />
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>First Name</label>
                                <input disabled type="text"  class="form-control" name="first_name" value="{{$data->first_name}}" />
                            </div>
                            <div class="form-group col-md-4">
                                <label>Last Name</label>
                                <input disabled  type="text"  class="form-control" name="last_name" value="{{$data->last_name}}" />
                            </div>
                            <div class="form-group col-md-4">
                                <label>Ezzy Card</label>
                                <input disabled type="text"  class="form-control" name="ezzycare_card" value="{{$data->ezzycare_card}}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Email</label>
                                <input disabled type="text"  class="form-control @error('email') form-control-danger @enderror" name="email" value="{{$data->email}}" />
                            </div>
                            <div class="form-group col-md-4">
                                <label>Mobile No</label>
                                <input disabled type="text" class="form-control" name="mobile_no" value="{{$data->mobile_no}}" />
                            </div>
                            <div class="form-group col-md-4">
                                <label>Gender</label>
                                <div class="custom-control custom-radio">
                                    <input disabled id="gender" type="radio" class="@error('gender') form-control-danger @enderror" name="gender" value="0" {{isset($data->gender) && $data->gender == '0' ? 'checked' : '' }} /> <label class="mr-5">Male</labele>
                                    <input disabled id="gender" type="radio" class="@error('gender') form-control-danger @enderror" name="gender" value="1" {{isset($data->gender) && $data->gender == '1' ? 'checked' : '' }} /> <label class="mr-5">Female</labele>
                                </div>
                            </div>
                        </div>

                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Device Details</h4>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Device Type</label>
                                    <input disabled type="text" class="form-control" name="device_type" value="{{($data->device_type == '0') ? 'Android' : (($data->device_type == '1') ? 'IOS' :  '') }}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Device Token</label>
                                    <input disabled type="text"  class="form-control" name="device_token" value="{{$data->device_token}}" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Social Details</h4>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Social Type</label>
                                    <input disabled type="text" class="form-control" name="device_type" value="{{$data->device_type == '0' ? 'Facebook' : $data->device_type == '1' ? 'Google' : $data->device_type == '2' ? 'Apple' :  '' }}" />
                                </div>
                                @if($data->device_type == '0')
                                <div class="form-group col-md-4">
                                    <label>Facebook ID</label>
                                    <input disabled type="text"  class="form-control" name="facebook_id" value="{{$data->facebook_id}}" />
                                </div>
                                @elseif($data->device_type == '1')
                                <div class="form-group col-md-4">
                                    <label>Google ID</label>
                                    <input disabled type="text"  class="form-control" name="google_id" value="{{$data->google_id}}" />
                                </div>
                                @elseif($data->device_type == '2')
                                <div class="form-group col-md-4">
                                    <label>Apple ID</label>
                                    <input disabled type="text"  class="form-control" name="apple_id" value="{{$data->apple_id}}" />
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if(!empty($data->userDetails)) 
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">User Extra Details</h4>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Name</label>
                                    <input disabled type="text" class="form-control" name="name" value="" />
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(count($data->userAvailableTime) > 0)
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Available Time Details</h4>
                            @foreach($data->userAvailableTime as $user_avalibale_time) 
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Day</label>
                                        <input disabled type="text" class="form-control" name="day" value="{{array_key_exists($user_avalibale_time->day, $days) ? $days[$user_avalibale_time->day]: ''}}" />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Appointment Type</label>
                                        <input disabled type="text" class="form-control" name="appointment_type" value="{{array_key_exists($user_avalibale_time->appointment_type, $appointment_types) ? $appointment_types[$user_avalibale_time->appointment_type]: ''}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Start Time</label>
                                        <input disabled type="text" class="form-control" name="start_time" value="{{$user_avalibale_time->start_time}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>End Time</label>
                                        <input disabled type="text" class="form-control" name="end_time" value="{{$user_avalibale_time->end_time}}" />
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endif
                       
                        @if(count($data->userEduction) > 0)
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Education Details</h4>
                            @foreach($data->userEduction as $user_eduction) 
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>College Name</label>
                                        <input disabled type="text" class="form-control" name="college_name" value="{{$user_eduction->college_name}}" />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Degree Name</label>
                                        <input disabled type="text" class="form-control" name="degree_name" value="{{$user_eduction->degree_name}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Start Year</label>
                                        <input disabled type="text" class="form-control" name="college_name" value="{{$user_eduction->start_year}}" />
                                    </div>
                                    @if($user_eduction->currently_work == '1')
                                    <div class="form-group col-md-2">
                                        <label>Current</label>
                                        <input disabled type="text" class="form-control" name="end_year" value="Current Pursuing" />
                                    </div>
                                    @else
                                    <div class="form-group col-md-2">
                                        <label>End Year</label>
                                        <input disabled type="text" class="form-control" name="end_year" value="{{$user_eduction->end_year}}" />
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @endif

                        @if(count($data->userExperiance) > 0) 
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Experiance Details</h4>
                            @foreach($data->userExperiance as $user_experiance) 
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Name</label>
                                        <input disabled type="text" class="form-control" name="name" value="{{$user_experiance->college_name}}" />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Descritption</label>
                                        <input disabled type="text" class="form-control" name="descritption" value="{{$user_experiance->descritption}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Start Year</label>
                                        <input disabled type="text" class="form-control" name="college_name" value="{{$user_experiance->start_year}}" />
                                    </div>
                                    @if($user_experiance->currently_work == '1')
                                    <div class="form-group col-md-2">
                                        <label>Current</label>
                                        <input disabled type="text" class="form-control" name="end_year" value="Currently Working" />
                                    </div>
                                    @else
                                    <div class="form-group col-md-2">
                                        <label>End Year</label>
                                        <input disabled type="text" class="form-control" name="end_year" value="{{$user_experiance->end_year}}" />
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        @endif

                        @if(count($data->userBankAccount) > 0) 
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Bank Details</h4>
                            @foreach($data->userBankAccount as $user_bank_account) 
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Bank Name</label>
                                        <input disabled type="text" class="form-control" name="bank_name" value="{{$user_bank_account->bank_name}}" />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Branch Name</label>
                                        <input disabled type="text" class="form-control" name="bank_branch_name" value="{{$user_bank_account->bank_branch_name}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Account No.</label>
                                        <input disabled type="text" class="form-control" name="account_number" value="{{$user_bank_account->account_number}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>IFSC Code</label>
                                        <input disabled type="text" class="form-control" name="ifsc_code" value="{{$user_bank_account->ifsc_code}}" />
                                    </div>
                                </div>
                            @endforeach
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
</script>
<script src="{{ asset('js/admin/user.js') }}" ></script>
@endsection