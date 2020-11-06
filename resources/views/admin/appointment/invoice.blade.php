@extends('layouts.backend')

 @section('title','View Appointment Invoice')

@section('content')

<div class="container-fluid">

    <div class="row d-print-none">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
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
                                <h4 class="float-right font-16"><strong>Appointment # {{ !empty($data->id) ? $data->id : '' }}</strong></h4>
                                <h3 class="m-t-0">
                                    <img src="{{ asset('admin/images/logo.png') }}" alt="logo" height="40"/>
                                </h3>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <address>
                                        <strong>Billed To:</strong><br>
                                        {{$data->name}}<br>
                                        {{$data->email}}<br>
                                        {{$data->mobile_no}}<br>
                                        {{$data->address}}<br>
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 m-t-30">
                                    <address>
                                        <strong>Completed Date:</strong>&nbsp;&nbsp;{{$data->completed_datetime}}
                                    </address>
                                    <address>
                                        <strong>Appointment Type:</strong>&nbsp;&nbsp;{{array_key_exists($data->appointment_type, $appointment_types) ? $appointment_types[$data->appointment_type]: ''}}
                                    </address>
                                    <address>
                                        <strong>Status:</strong>&nbsp;&nbsp;{{array_key_exists($data->status, $status) ? $status[$data->status]: ''}}
                                    </address>
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
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <td><strong>HCP Type</strong></td>
                                                @if(!empty($data->user->categoryChild))
                                                <td class="text-center"><strong>HCP Subype</strong></td>
                                                @endif
                                                <td class="text-center"><strong>Email</strong></td>
                                                <td class="text-center"><strong>Mobile No.</strong></td>
                                                <td class="text-center"><strong>Start Date Time</strong></td>
                                                <td class="text-center"><strong>End Date Time</strong></td>
                                                <td class="text-center"><strong>Amount</strong></td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @php ($sub_total = 0)
                                                @php ($sub_total = $sub_total)
                                            <tr>
                                                <td >{{!empty($data->user) && !empty($data->user->categoryParent)? $data->user->categoryParent->name:''}}</td>
                                                @if(!empty($data->user->categoryChild))
                                                <td class="text-center">{{!empty($data->user) && !empty($data->user->categoryChild)? $data->user->categoryChild->name:''}}</td>
                                                @endif
                                                <td class="text-center">{{!empty($data->user) ? $data->user->email:''}}</td>
                                                <td class="text-center">{{!empty($data->user) ? $data->user->mobile_no:''}}</td>
                                                <td class="text-center">{{$data->appointment_date .' '. $data->appointment_time}}</td>
                                                <td class="text-center">{{$data->completed_datetime}}</td>
                                                <td class="text-right">{{$data->appointment_price}}</td>
                                            </tr>       
                                            <tr>
                                                @if(!empty($data->user->categoryChild))
                                                <td class="no-line text-center" colspan="5"></td>
                                                @else
                                                <td class="no-line text-center" colspan="4"></td>
                                                @endif
                                                <td class="thick-line text-center">
                                                    <strong>Subtotal</strong></td>
                                                <td class="thick-line text-right">{{$data->appointment_price}}</td>
                                            </tr>
                                            <tr>
                                                @if(!empty($data->user->categoryChild))
                                                <td class="no-line text-center" colspan="5"></td>
                                                @else
                                                <td class="no-line text-center" colspan="4"></td>
                                                @endif
                                                <td class="no-line text-center">
                                                    <strong>Total</strong></td>
                                                <td class="no-line text-right"><h4 class="m-0">{{$data->appointment_price}}</h4></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-print-none mo-mt-2">
                                        <div class="float-right">
                                            <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
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
</script>
<script src="{{ asset('js/admin/appointment.js') }}" ></script>
@endsection