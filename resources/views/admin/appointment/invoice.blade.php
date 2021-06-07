@extends('layouts.backend')

 @section('title','View Appointment Invoice')

@section('content')

<div class="container-fluid">

    <div class="row d-print-none">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/appointment')}}">Appointment List</a></li>
                    <li class="breadcrumb-item active">Invoice</li>
                </ol>
            </div>
            <h5 class="page-title">Invoice</h5>
        </div>
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="invoice-title">
                                <h4 class="float-right font-16">
                                <div>
                                    <strong>Invoice : </strong>{{ !empty($data->id) ? $data->invoice_no_generate : '' }}<br>
                                    <strong>Invoice Date : </strong>{{ !empty($data->completed_datetime) ? Helper::getDateTimeFormate($data->completed_datetime) : '' }}
                                </div>
                                </h4>
                                <h3 class="m-t-0">
                                    <img src="{{ asset('admin/images/logo-1.png') }}" alt="logo" height="40"/>
                                </h3>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <address>
                                        <h5>{{!empty($data->user) && !empty($data->user->categoryParent)? $data->user->categoryParent->name:''}} Details:</h5>
                                        <b>Name: </b>{{!empty($data->user) ? $data->user->user_name :''}}<br>
                                        <b>Email: </b>{{!empty($data->user) ? $data->user->email :''}}<br>
                                        <b>Mobile: </b>{{!empty($data->user) ? $data->user->mobile_no_country_code :''}}<br>
                                    </address>
                                </div>
                                <div class="col-6">
                                    <address>
                                        <h5>Patient Details:</h5>
                                        <b>Name: </b>{{$data->name}}<br>
                                        <b>Email: </b>{{$data->email}}<br>
                                        <b>Mobile: </b>{{$data->mobile_no}}<br>
                                        <b>Address: </b>{{$data->address}}<br>
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 m-t-30">
                                    <b>Completed Date: </b>{{Helper::getDateTimeFormate($data->completed_datetime)}}<br>
                                    @php($urgent = ($data->urgent == '1')? '(Urgent)' : '')
                                    <b>Type of Appointment: </b>{{$data->appointment_type_name}} <br>
                                    <b>Status: </b>{{$data->status_name}}
                                </div>
                                <div class="col-6 m-t-30 text-right">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="panel panel-default">
                                <div class="p-2">
                                    <h3 class="panel-title font-20"><strong>Appointment summary</strong></h3>
                                </div>
                                <div class="">
                                    <div class="table-responsive">
                                        @if(!empty($data->appointmentServices) && count($data->appointmentServices) > 0)
                                             @php($appointment_charge = 0)
                                        @else
                                            @if($data->user->category_id == '4')
                                                @php ($appointment_charge = !empty($data->hcp_fees) ? $data->hcp_fees * $data->start_to_end_time_diff : 0)
                                                @php ($appointment_charge_text = ($data->urgent == '1')? 'Charge (per Minute)' : 'Charge (per Minute)')
                                            @elseif($data->user->category_id == '5' || $data->user->category_id == '6')
                                                @if($data->full_day == '1')
                                                    @php ($appointment_charge = !empty($data->hcp_fees) ? $data->hcp_fees : 0)
                                                    @php ($appointment_charge_text = 'Charge (per Day)')
                                                @else
                                                    @php ($appointment_charge = !empty($data->hcp_fees) ? $data->hcp_fees * ($data->start_to_end_time_diff/60) : 0)
                                                    @php ($appointment_charge_text = 'Charge (per Hour)')
                                                @endif
                                            @else
                                                @php ($appointment_charge = !empty($data->hcp_fees) ? $data->hcp_fees * ($data->start_to_end_time_diff/60) : 0)
                                                @php ($appointment_charge_text = ($data->urgent == '1')? 'Charge (per Hour)' : 'Charge (per Hour)')
                                            @endif
                                        @endif
                                        
                                        <table class="table">
                                            <thead>
                                            @if(!empty($data->appointmentServices) && count($data->appointmentServices) > 0)
                                            <tr>
                                                <td class="text-center"><strong>Service Name</strong></td>
                                                <td class="text-center"><strong>Service Charge</strong></td>
                                                <td class="text-center"><strong>Quantity</strong></td>
                                                <td class="text-center"><strong>Total Amount</strong></td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td class="text-center"><strong>Start Date Time</strong></td>
                                                <td class="text-center"><strong>End Date Time</strong></td>                                                
                                                <td class="text-center"><strong>{{$appointment_charge_text}}</strong></td>
                                                <td class="text-center"><strong>Time Difference (H:M:S)</strong></td>
                                                <td class="text-center"><strong>Charge Amount</strong></td>
                                            </tr>
                                            @endif
                                            </thead>
                                            <tbody>
                                             @php ($sub_total = 0)
                                             @if(!empty($data->appointmentServices) && count($data->appointmentServices) > 0)      
                                             @foreach($data->appointmentServices as $service)
                                             @php($appointment_charge += $service->service_price)
                                             <tr>
                                                <td class="text-center">{{$service->userService->service->service_name}}</td>
                                                <td class="text-center">{{$currency_symbol.$service->service_price}}</td>
                                                <td class="text-center">1</td>
                                                <td class="text-center">{{$currency_symbol.$service->service_price}}</td>
                                            </tr> 
                                            @php ($sub_total += $service->service_price)
                                            @endforeach
                                            @else 
                                            <tr>
                                                <td class="text-center">{{Helper::getDateTimeFormate($data->start_datetime)}}</td>
                                                <td class="text-center">{{Helper::getDateTimeFormate($data->completed_datetime)}}</td>                                                
                                                <td class="text-center">{{$currency_symbol.$data->hcp_fees}}</td>
                                                @if(!empty($data->full_day) &&  $data->full_day == '1')
                                                    <td class="text-center">Full Day</td>
                                                @else
                                                    <td class="text-center">{{$data->start_to_end_time_diff_format}} </td>
                                                @endif                                                
                                                <td class="text-center">{{$currency_symbol.round($appointment_charge, 2)}}</td>
                                            </tr> 
                                            @endif     
                                            <tr>
                                                @if(!empty($data->appointmentServices) && count($data->appointmentServices) > 0)
                                                <td class="no-line text-center" colspan="2"></td>
                                                @else
                                                <td class="no-line text-center" colspan="3"></td>
                                                @endif
                                                <td class="thick-line text-center">
                                                    <strong>Subtotal</strong></td>
                                                <td class="thick-line text-center">{{$currency_symbol.round($appointment_charge, 2)}}</td>
                                            </tr>                                            
                                            @if(!empty($data->voucher_code_id) && !empty($data->voucherDetails))
                                            <tr>
                                                <td class="no-line text-center" colspan="3"></td>
                                                <td class="thick-line text-center">
                                                    <strong>Voucher Amount (-)</strong></td>
                                                <td class="thick-line text-center">{{$currency_symbol.round($data->voucher_amount, 2)}}</td>
                                            </tr>
                                            @endif
                                            @if($data->appointment_type == '1' && ($data->user->category_id == '8' || $data->user->category_id == '9' || $data->user->category_id == '10'))
                                            <tr>
                                                @if(!empty($data->appointmentServices) && count($data->appointmentServices) > 0)
                                                <td class="no-line text-center" colspan="2"></td>
                                                @else
                                                <td class="no-line text-center" colspan="3"></td>
                                                @endif
                                                <td class="thick-line text-center">
                                                    <strong>Home Visit Fees (+)</strong></td>
                                                <td class="thick-line text-center">{{$currency_symbol.round($data->home_visit_fees, 2)}}</td>
                                            </tr>
                                            @endif
                                            @if($data->urgent == '1' && $data->user->category_id == '4')
                                            <tr>
                                                @if(!empty($data->appointmentServices) && count($data->appointmentServices) > 0)
                                                <td class="no-line text-center" colspan="2"></td>
                                                @else
                                                <td class="no-line text-center" colspan="3"></td>
                                                @endif
                                                <td class="thick-line text-center">
                                                    <strong>Urgent Care (+)</strong></td>
                                                <td class="thick-line text-center">{{$currency_symbol.round($data->home_visit_fees, 2)}}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                @if(!empty($data->appointmentServices) && count($data->appointmentServices) > 0)
                                                <td class="no-line text-center" colspan="2"></td>
                                                @else
                                                <td class="no-line text-center" colspan="3"></td>
                                                @endif
                                                <td class="thick-line text-center">
                                                    <strong>Total</strong></td>
                                                <td class="thick-line text-center"><h4 class="m-0">{{$currency_symbol.round($data->appointment_price, 2)}}</h4></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-print-none mo-mt-2">
                                        <div class="float-right">
                                            <a  href="javascript:window.print()" onClick="document.title = '{{ !empty($data->id) ? $data->invoice_no_generate.'_'.strtotime(now()) : '' }}';" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                            <a  href="#" onclick="history.go(-1)" class="btn btn-primary waves-effect waves-light">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div> <!-- end row -->

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

</div><!-- container fluid -->
@endsection

@section('script')
<script>
    var appointment_url = "{{url('/appointment')}}";
    var data_obj = {};
    var data_status = '';
    var data_user_id = '';
    var data_urgent = '';
</script>
<script src="{{ asset('js/admin/appointment.js') }}" ></script>
@endsection