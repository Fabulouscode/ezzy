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

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Name</label>
                                <input disabled  type="text"  class="form-control" name="name" value="{{$data->name}}" />
                            </div>
                            <div class="form-group col-md-4">
                                <label>Email</label>
                                <input disabled type="text"  class="form-control" name="email" value="{{$data->email}}" />
                            </div>                            
                            <div class="form-group col-md-4">
                                <label>Mobile No</label>
                                <input disabled type="text" class="form-control" name="mobile_no" value="{{$data->mobile_no}}" />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Appointment Date</label>
                                <input disabled  type="text"  class="form-control" name="appointment_date" value="{{$data->appointment_date}}" />
                            </div>
                            <div class="form-group col-md-4">
                                <label>Appointment Time</label>
                                <input disabled type="text"  class="form-control" name="appointment_time" value="{{$data->appointment_time}}" />
                            </div>                            
                            <div class="form-group col-md-4">
                                <label>Status</label>
                                <input disabled  type="text"  class="form-control" name="status" value="{{array_key_exists($data->status, $status) ? $status[$data->status]: ''}}" />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Appointment Type</label>
                                <input disabled  type="text"  class="form-control" name="appointment_type" value="{{array_key_exists($data->appointment_type, $appointment_types) ? $appointment_types[$data->appointment_type]: ''}}" />
                            </div>
                            <div class="form-group col-md-4">
                                <label>Age</label>
                                <input disabled type="text"  class="form-control" name="age" value="{{$data->age}}" />
                            </div>                            
                            <div class="form-group col-md-4">
                                <label>Reason</label>
                                <textarea disabled class="form-control" name="reason"  >{{$data->reason}}</textarea>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Appointment Book Date</label>
                                <input disabled  type="text"  class="form-control" name="completed_datetime" value="{{$data->created_at}}" />
                            </div>
                            @if(!empty($data->completed_datetime))
                            <div class="form-group col-md-4">
                                <label>Completed Date</label>
                                <input disabled  type="text"  class="form-control" name="completed_datetime" value="{{$data->completed_datetime}}" />
                            </div>
                            @endif
                            @if(!empty($data->appointment_price))
                            <div class="form-group col-md-4">
                                <label>Total Amount</label>
                                <input disabled type="text"  class="form-control" name="appointment_price" value="{{$data->appointment_price}}" />
                            </div>   
                            @endif     
                        </div>
                        

                        @if(!empty($data->cancelUser))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title"> Cancel Details</h4>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input disabled type="text"  class="form-control" name="cancel_date" value="{{$data->cancelUser->email}}" />
                                </div>                            
                                <div class="form-group col-md-4">
                                    <label>Cancel Date Time</label>
                                    <input disabled type="text"  class="form-control" name="cancel_date" value="{{$data->cancel_date}}" />
                                </div>                            
                                <div class="form-group col-md-4">
                                    <label>Cancel Reason</label>
                                    <textarea disabled class="form-control" name="cancel_reason"  >{{$data->cancel_reason}}</textarea>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!empty($data->user))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title"> {!! Helper::getCategoryName($data->user->category_id) !!} Details</h4>
                            <div class="row">
                                @if(!empty($data->user->category_id))
                                <div class="form-group col-md-4">
                                    <label>HCP Type</label>
                                    <select disabled id="category_id"  type="text" class="form-control" name="category_id" >
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}"  {{ !empty($data->user->category_id) && $category->id == $data->user->category_id ? 'selected' : '' }}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                @if(!empty($data->user->subcategory_id))
                                <div class="form-group col-md-4">
                                    <label>HCP Subtype</label>
                                    <select disabled id="subcategory_id"  type="text" class="form-control" name="subcategory_id" >
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}"  {{ !empty($data->user->subcategory_id) && $category->id == $data->user->subcategory_id ? 'selected' : '' }}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Name</label>
                                    <input disabled type="text" class="form-control" name="first_name" value="{{$data->user->first_name .' '. $data->user->last_name}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input disabled type="text" class="form-control" name="email" value="{{$data->user->email}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Mobile No.</label>
                                    <input disabled type="text" class="form-control" name="mobile_no" value="{{$data->user->mobile_no}}" />
                                </div>
                            </div>
                             @if(!empty($data->debitTransaction))
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Transaction Date</label>
                                        <input disabled type="text" class="form-control" name="email" value="{{$data->debitTransaction->transaction_date}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Amount</label>
                                        <input disabled type="text" class="form-control" name="amount" value="{{$data->debitTransaction->amount}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Payment Mode</label>
                                        <input disabled type="text" class="form-control" name="mode_of_payment" value="{{$data->debitTransaction->mode_of_payment == '0'? 'Debit' : 'Credit'}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Transaction Type</label> 
                                        <input disabled type="text" class="form-control" name="transaction_type" value="{{array_key_exists($data->debitTransaction->transaction_type, $transaction_type) ? $transaction_type[$data->debitTransaction->transaction_type]: ''}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Status</label>
                                        <input disabled type="text" class="form-control" name="status" value="{{array_key_exists($data->debitTransaction->status, $transaction_status) ? $transaction_status[$data->debitTransaction->status]: ''}}" />
                                    </div>
                                </div>
                             @endif
                        </div>
                        @endif

                       @if(!empty($data->client))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Customer Details</h4>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Name</label>
                                    <input disabled type="text" class="form-control" name="first_name" value="{{$data->client->first_name .' '. $data->client->last_name}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input disabled type="text" class="form-control" name="email" value="{{$data->client->email}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Mobile No.</label>
                                    <input disabled type="text" class="form-control" name="mobile_no" value="{{$data->client->mobile_no}}" />
                                </div>
                            </div>
                            @if(!empty($data->creditTransaction))
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Transaction Date</label>
                                        <input disabled type="text" class="form-control" name="email" value="{{$data->creditTransaction->transaction_date}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Amount</label>
                                        <input disabled type="text" class="form-control" name="amount" value="{{$data->creditTransaction->amount}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Payment Mode</label>
                                        <input disabled type="text" class="form-control" name="mode_of_payment" value="{{$data->creditTransaction->mode_of_payment == '0'? 'Debit' : 'Credit'}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Transaction Type</label>
                                        <input disabled type="text" class="form-control" name="transaction_type" value="{{array_key_exists($data->creditTransaction->transaction_type, $transaction_type) ? $transaction_type[$data->creditTransaction->transaction_type]: ''}}" />
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>Status</label>
                                        <input disabled type="text" class="form-control" name="status" value="{{array_key_exists($data->creditTransaction->status, $transaction_status) ? $transaction_status[$data->creditTransaction->status]: ''}}" />
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endif

                       @if(!empty($data->userService))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Services Details</h4>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Service Name</label>
                                    <input disabled type="text" class="form-control" name="service_name" value="{{$data->userService->service->service_name}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Service Price</label>
                                    <input disabled type="text" class="form-control" name="service_name" value="{{$data->userService->service_charge}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Service Charge Type</label>
                                    <input disabled type="text" class="form-control" name="service_charge_type" value="{{array_key_exists($data->userService->service_charge_type, $service_charge_type) ? $service_charge_type[$data->userService->service_charge_type]: ''}}" />
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!empty($data->appointmentServices) && count($data->appointmentServices) > 0)
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Services Details</h4>
                            @foreach($data->appointmentServices as $service)
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Service Name</label>
                                        <input disabled type="text" class="form-control" name="service_name" value="{{$service->userService->service->service_name}}" />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Service Price</label>
                                        <input disabled type="text" class="form-control" name="service_name" value="{{$service->userService->service_charge}}" />
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Service Report : - &nbsp;</label>
                                        <a href="#" target="_blank">Click Here</a>
                                    </div>
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