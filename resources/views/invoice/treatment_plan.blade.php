@extends('invoice.layout')

@section('title','Invoice Treatment Plan Details')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="invoice-title">
                                <h5 class="float-right font-16">
                                    <div>
                                        <strong>Invoice : </strong>#{{ !empty($data->id) ? $data->invoice_no_generate : '' }}<br>
                                        <strong>Invoice Date : </strong>{{ !empty($data->created_at) ? Helper::getDateTimeFormate($data->created_at) : '' }}
                                    </div>
                                </h5>
                                <h3 class="m-t-0">
                                    <img src="{{ asset('admin/images/logo-1.png') }}" alt="logo" height="40"/>
                                </h3>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table style="width:100%">
                                        <tr>
                                            <td>
                                                <div>
                                                    <address>
                                                            <strong>Billed To:</strong><br>
                                                            {{$data->client->first_name}} {{$data->client->last_name}}<br>
                                                            {{$data->client->email}}<br>
                                                            {{$data->client->mobile_no_country_code}}<br>
                                                            {{$data->client->address}}<br>
                                                    </address>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>                                
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 m-t-30">
                                    <b>Plan Name: </b>Treatment Plan<br>
                                    <b>Treatment Name: </b>{{$data->plan_name}}<br>
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
                                    <h3 class="panel-title font-20"><strong>Treatment summary</strong></h3>
                                </div>
                                <div class="">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <td><strong>Name</strong></td>                                                
                                                <td class="text-center"><strong>Price</strong></td>
                                                <td class="text-center"><strong>Quantity</strong></td>
                                                <td class="text-right"><strong>Totals</strong></td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php ($sub_total = 0)
                                            @if(!empty($data->chatDetails))    
                                            @foreach($data->chatDetails as $key => $ch_hi)    
                                                @php ($medicine_total = $ch_hi->price * $ch_hi->quanity)
                                                @php ($sub_total += $medicine_total)
                                            <tr>
                                                <td>{{$ch_hi->medicine_name}}</td>
                                                <td class="text-center currency_symbol">{{$currency_symbol.$ch_hi->price}}</td>
                                                <td class="text-center">{{$ch_hi->quanity}}</td>
                                                <td class="text-right currency_symbol">{{$currency_symbol.($ch_hi->price * $ch_hi->quanity)}}</td>
                                              </tr>                                        
                                            @endforeach
                                            @endif
                                            <tr>
                                                <td class="no-line text-center" colspan="2"></td>
                                                <td class="thick-line text-center">
                                                    <strong>Subtotal</strong></td>
                                                <td class="thick-line text-right currency_symbol">{{$currency_symbol.$sub_total}}</td>
                                            </tr>
                                            <tr>
                                                <td class="no-line text-center" colspan="2"></td>
                                                <td class="thick-line text-center">
                                                    <strong>Total</strong></td>
                                                <td class="thick-line text-right currency_symbol font-16">{{$currency_symbol.$sub_total}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
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
