@extends('layouts.backend')

@section('title','View Appointment Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch/appointment')}}">Appointment</a></li>
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
       
                    <form method="POST"  id="appointment_form" name="appointment_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 header-title">Appointment Details</h4>
                                <div class="card-detail-list">
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
                                                {{Helper::getDateFormate($data->appointment_date)}}
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Appointment Time</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->appointment_time))
                                                {{Helper::getTimeFormate($data->appointment_time)}}
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Type of Appointment</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(isset($data->appointment_type))
                                                {{$data->appointment_type_name}}
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
                                        <dt class="col-sm-5"><label>Appointment Created Date</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(isset($data->created_at))
                                                {{Helper::getDateTimeFormate($data->created_at)}}
                                            @endif 
                                        </dd>
                                    </div>                            
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Status</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(isset($data->status))
                                                {{$data->status_name}}
                                            @endif 
                                        </dd>
                                    </div>
                                    @if(!empty($data->start_datetime))
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Appointment Started Date Time</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->start_datetime))
                                                {{Helper::getDateTimeFormate($data->start_datetime)}}
                                            @endif 
                                        </dd>
                                    </div>
                                    @endif
                                    @if(!empty($data->completed_datetime))
                                        <div class="row">
                                            <dt class="col-sm-5"><label>Appointment Completed Date Time</label></dt>
                                            <dd class="col-sm-7"> 
                                                @if(isset($data->completed_datetime))
                                                    {{Helper::getDateTimeFormate($data->completed_datetime)}}
                                                @endif 
                                            </dd>
                                        </div>
                                        @if($data->user->category_id != '8' || $data->user->category_id != '9' || $data->user->category_id != '10')
                                            <div class="row">
                                                <dt class="col-sm-5"><label>Appointment Charge</label></dt>
                                                <dd class="col-sm-7"> 
                                                    @if(isset($data->appointment_price))
                                                        {{$currency_symbol.$data->appointment_price}}
                                                    @endif 
                                                </dd>
                                            </div> 
                                            @if(!empty($data->voucher_code_id) && !empty($data->voucherDetails))
                                            <div class="row">
                                                <dt class="col-sm-5"><label>Voucher Amount </label></dt>
                                                <dd class="col-sm-7"> 
                                                    @if(isset($data->voucher_amount))
                                                        {{$currency_symbol.$data->voucher_amount}}
                                                    @endif 
                                                </dd>
                                            </div> 
                                            @endif
                                        @else
                                            @if($data->appointment_type == '1')
                                                <div class="row">
                                                    <dt class="col-sm-5"><label>Home Visit Charge</label></dt>
                                                    <dd class="col-sm-7"> 
                                                        @if(isset($data->home_visit_fees))
                                                            {{$currency_symbol.$data->home_visit_fees}}
                                                        @endif 
                                                    </dd>
                                                </div>  
                                                <div class="row">
                                                    <dt class="col-sm-5"><label>Service Charges</label></dt>
                                                    <dd class="col-sm-7"> 
                                                        @if(isset($data->hcp_fees))
                                                            {{$currency_symbol.$data->hcp_fees}}
                                                        @endif 
                                                    </dd>
                                                </div>  
                                                <div class="row">
                                                    <dt class="col-sm-5"><label>Total Appointment Charge</label></dt>
                                                    <dd class="col-sm-7"> 
                                                        @if(isset($data->appointment_price))
                                                            {{$currency_symbol.$data->appointment_price}}
                                                        @endif 
                                                    </dd>
                                                </div>  
                                            @else
                                                <div class="row">
                                                    <dt class="col-sm-5"><label>Appointment Charge</label></dt>
                                                    <dd class="col-sm-7"> 
                                                        @if(isset($data->appointment_price))
                                                            {{$currency_symbol.$data->appointment_price}}
                                                        @endif 
                                                    </dd>
                                                </div>
                                            @endif
                                        @endif
                                    @else
                                        @if(!empty($data->getTransaction) && !empty($data->getTransaction->id))
                                        <div class="row">
                                            <dt class="col-sm-5"><label>Appointment Locked Amount</label></dt>
                                            <dd class="col-sm-7"> 
                                                {{$currency_symbol.$data->getTransaction->amount}}
                                            </dd>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        
                        @if(!empty($data->appointmentServices) && count($data->appointmentServices) > 0)
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 header-title">Services Details</h4>
                                <div class="card-detail-list">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Service Name</th>
                                                    <th>Service Amount</th>
                                                    <!-- <th>Report</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data->appointmentServices as $service) 
                                                <tr>
                                                    <td>{{$service->userService->service->service_name}}</td>
                                                    <td>{{$currency_symbol.$service->service_price}}</td>
                                                    <!-- <td> </td> -->
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!empty($data->cancelUser))
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title"> Cancel Details</h4>
                                <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-5"><label>User Name</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(isset($data->cancelUser))
                                                {{$data->cancelUser->user_name}}
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Cancel Date Time</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(isset($data->cancel_date))
                                                {{Helper::getDateTimeFormate($data->cancel_date)}}
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
                            </div>
                        </div>
                        @endif

                        @if(!empty($data->client))
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 header-title">Customer Details</h4>
                                <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-5"><label>User Name</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(isset($data->client->first_name))
                                                {{$data->client->user_name}}
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
                                                {{$data->client->mobile_no_country_code}}
                                            @endif 
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!empty($data->user))
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 header-title"> Healthcare Provider Details</h4>
                                <div class="card-detail-list">
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
                                                {{$data->user->user_name}}
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
                                                {{$data->user->mobile_no_country_code}}
                                            @endif 
                                        </dd>
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
    var appointment_url = "{{url('/donotezzycaretouch/appointment')}}";
    var data_obj = {};
    var data_status = '';  
    var data_user_id = '';
    var data_urgent = '';
</script>
<script src="{{ asset('js/admin/appointment.js') }}" ></script>
@endsection