@extends('layouts.backend')

@section('title','View Appointment Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/appointment')}}">Appointment</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
            <h5 class="page-title">View Appointment Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" action="{{ url('appointment') }}" id="appointment_form" name="appointment_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Appointment Details</h4>
                            <div class="row">
                                <dt class="col-sm-5"><label>Name</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(!empty($data->name))
                                        {{$data->name}}
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
                                <dt class="col-sm-5"><label>Mobile No</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(!empty($data->mobile_no))
                                        {{$data->mobile_no}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Appointment Date</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(!empty($data->appointment_date))
                                        {{$data->appointment_date}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Appointment Time</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(!empty($data->appointment_time))
                                        {{$data->appointment_time}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Appointment Type</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->appointment_type))
                                        {{array_key_exists($data->appointment_type, $appointment_types) ? $appointment_types[$data->appointment_type]: ''}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Age</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->age))
                                        {{$data->age}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Reason</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->reason))
                                        {{$data->reason}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Appointment Book Date</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->created_at))
                                        {{$data->created_at}}
                                    @endif 
                                </dd>
                            </div>                            
                            <div class="row">
                                <dt class="col-sm-5"><label>Status</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->status))
                                        {{array_key_exists($data->status, $status) ? $status[$data->status]: ''}}
                                    @endif 
                                </dd>
                            </div>
                            @if(isset($data->completed_datetime))
                            <div class="row">
                                <dt class="col-sm-5"><label>Appointment Completed Date Time</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->completed_datetime))
                                        {{$data->completed_datetime}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Appointment Amount</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->appointment_price))
                                        {{$data->appointment_price}}
                                    @endif 
                                </dd>
                            </div>
                            @endif
                        </div>
                        

                        

                        @if(!empty($data->cancelUser))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title"> Cancel Details</h4>
                            <div class="row">
                                <dt class="col-sm-5"><label>User Name</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->cancelUser))
                                        {{$data->cancelUser->first_name .' '. $data->cancelUser->last_name}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Cancel Date Time</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->cancel_date))
                                        {{$data->cancel_date}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Cancel Reason</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->cancel_reason))
                                        {{$data->cancel_reason}}
                                    @endif 
                                </dd>
                            </div>
                        </div>
                        @endif

                        @if(!empty($data->client))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Customer Details</h4>
                            <div class="row">
                                <dt class="col-sm-5"><label>User Name</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->client->first_name))
                                        {{$data->client->first_name .' '. $data->client->last_name}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Email</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->client->email))
                                        {{$data->client->email}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Mobile No.</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->client->mobile_no))
                                        {{$data->client->mobile_no}}
                                    @endif 
                                </dd>
                            </div>
                        </div>
                        @endif

                        @if(!empty($data->user))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title"> Healthcare Provider Details</h4>
                            <div class="row">
                                <dt class="col-sm-5"><label>HCP Type</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->user->categoryParent))
                                        {{$data->user->categoryParent->name}}
                                    @endif 
                                </dd>
                            </div>
                            @if(!empty($data->user->categoryChild))
                            <div class="row">
                                <dt class="col-sm-5"><label>HCP Subtype</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->user->categoryChild))
                                        {{$data->user->categoryChild->name}}
                                    @endif 
                                </dd>
                            </div>
                            @endif
                            <div class="row">
                                <dt class="col-sm-5"><label>User Name</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->user->first_name))
                                        {{$data->user->first_name .' '. $data->user->last_name}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Email</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->user->email))
                                        {{$data->user->email}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Mobile No.</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->user->mobile_no))
                                        {{$data->user->mobile_no}}
                                    @endif 
                                </dd>
                            </div>
                        </div>
                        @endif

                            
                        @if(!empty($data->creditTransaction))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Transaction Details</h4>
                                <div class="row">
                                    <dt class="col-sm-5"><label>Transaction Date</label></dt>
                                    <dd class="col-sm-7"> 
                                        @if(isset($data->creditTransaction->transaction_date))
                                            {{$data->creditTransaction->transaction_date}}
                                        @endif 
                                    </dd>
                                </div>
                                <div class="row">
                                    <dt class="col-sm-5"><label>Amount</label></dt>
                                    <dd class="col-sm-7"> 
                                        @if(isset($data->creditTransaction->amount))
                                            {{$data->creditTransaction->amount}}
                                        @endif 
                                    </dd>
                                </div>
                                <div class="row">
                                    <dt class="col-sm-5"><label>Transaction Type</label></dt>
                                    <dd class="col-sm-7"> 
                                        @if(isset($data->creditTransaction->transaction_type))
                                            {{array_key_exists($data->creditTransaction->transaction_type, $transaction_type) ? $transaction_type[$data->creditTransaction->transaction_type]: ''}}
                                        @endif 
                                    </dd>
                                </div>
                                <div class="row">
                                    <dt class="col-sm-5"><label>Transaction Status</label></dt>
                                    <dd class="col-sm-7"> 
                                        @if(isset($data->creditTransaction->status))
                                            {{array_key_exists($data->creditTransaction->status, $transaction_status) ? $transaction_status[$data->creditTransaction->status]: ''}}
                                        @endif 
                                    </dd>
                                </div>
                            </div>
                        @endif
                       

                       @if(!empty($data->userService))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Services Details</h4>
                                <div class="row">
                                    <dt class="col-sm-5"><label>Service Name</label></dt>
                                    <dd class="col-sm-7"> 
                                        @if(isset($data->userService->service))
                                            {{$data->userService->service->service_name}}
                                        @endif 
                                    </dd>
                                </div>
                                <div class="row">
                                    <dt class="col-sm-5"><label>Service Charge</label></dt>
                                    <dd class="col-sm-7"> 
                                        @if(isset($data->userService->service_charge))
                                            {{$data->userService->service_charge}}
                                        @endif 
                                    </dd>
                                </div>
                                <div class="row">
                                    <dt class="col-sm-5"><label>Service Charge Type</label></dt>
                                    <dd class="col-sm-7"> 
                                        @if(isset($data->userService->service_charge_type))
                                            {{array_key_exists($data->userService->service_charge_type, $service_charge_type) ? $service_charge_type[$data->userService->service_charge_type]: ''}}
                                        @endif 
                                    </dd>
                                </div>
                        </div>
                        @endif

                        @if(!empty($data->appointmentServices) && count($data->appointmentServices) > 0)
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Services Details</h4>
                            @foreach($data->appointmentServices as $service)
                                <div class="row">
                                    <dt class="col-sm-5"><label>Service Name</label></dt>
                                    <dd class="col-sm-7"> 
                                        @if(isset($service->userService->service))
                                            {{$service->userService->service->service_name}}
                                        @endif 
                                    </dd>
                                </div>
                                <div class="row">
                                    <dt class="col-sm-5"><label>Service Charge</label></dt>
                                    <dd class="col-sm-7"> 
                                        @if(isset($service->userService->service_charge))
                                            {{$service->userService->service_charge}}
                                        @endif 
                                    </dd>
                                </div>
                                <div class="row">
                                    <dt class="col-sm-5"><label>Service Report</label></dt>
                                    <dd class="col-sm-7"> 
                                        @if(isset($service->userService))
                                             <a href="#" target="_blank">Click Here</a>
                                        @endif 
                                    </dd>
                                </div>
                            @endforeach
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
    var appointment_url = "{{url('/appointment')}}";
    var data_obj = {};
</script>
<script src="{{ asset('js/admin/appointment.js') }}" ></script>
@endsection