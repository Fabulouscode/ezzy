@extends('invoice.layout')

@section('title','Invoice Order Details')

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
                                        <strong>Invoice : </strong>{{ !empty($data->id) ? $data->invoice_no_generate : '' }}<br>
                                        <strong>Invoice Date : </strong>{{ !empty($data->completed_datetime) ? Helper::getDateTimeFormate($data->completed_datetime) : '' }}
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
                                                            {{$data->clientDetails->first_name}} {{$data->clientDetails->last_name}}<br>
                                                            {{$data->clientDetails->email}}<br>
                                                            {{$data->clientDetails->mobile_no_country_code}}<br>
                                                            {{$data->clientDetails->address}}<br>
                                                    </address>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <address>
                                                          <strong>Shipped To:</strong><br>
                                                            {{!empty($data->userLocationDetails) $data->userLocationDetails->name ? ''}}<br>
                                                            {{!empty($data->userLocationDetails) $data->userLocationDetails->email ? ''}}<br>
                                                            {{!empty($data->userLocationDetails) $data->userLocationDetails->mobile_no ? ''}}<br>
                                                            {{!empty($data->userLocationDetails) $data->userLocationDetails->address ? ''}}<br>
                                                    </address>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>                                
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 m-t-30">
                                    <b>Order Date: </b>{{Helper::getDateTimeFormate($data->created_at)}}<br>
                                    <b>Delivery Type: </b>{{$data->delivery_type_name}} <br>
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
                                    <h3 class="panel-title font-20"><strong>Order summary</strong></h3>
                                </div>
                                <div class="">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <td><strong>Medicine Name</strong></td>
                                                <td class="text-center"><strong>Medicine SKU</strong></td>
                                                <td class="text-center"><strong>Medicine Type</strong></td>
                                                <td class="text-center"><strong>Price</strong></td>
                                                <td class="text-center"><strong>Quantity</strong></td>
                                                <td class="text-right"><strong>Totals</strong></td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @php ($sub_total = 0)
                                            @if(!empty($data->orderProductDetails))    
                                            @foreach($data->orderProductDetails as $key => $order_details)    
                                                @php ($medicine_total = $order_details->medicine_price * $order_details->quantity)
                                                @php ($sub_total += $medicine_total)
                                            <tr>
                                                <td>{{$order_details->shopMedicineDetails->medicineDetails->medicine_name}}</td>
                                                <td class="text-center">{{$order_details->shopMedicineDetails->medicineDetails->medicine_sku}}</td>
                                                <td class="text-center">{{$order_details->shopMedicineDetails->medicine_type_name}}</td>
                                                <td class="text-center currency_symbol">{{$currency_symbol.$order_details->medicine_price}}</td>
                                                <td class="text-center">{{$order_details->quantity}}</td>
                                                <td class="text-right currency_symbol">{{$currency_symbol.$order_details->medicine_price * $order_details->quantity}}</td>
                                            </tr>                                        
                                            @endforeach
                                            @endif
                                            <tr>
                                                <td class="no-line text-center" colspan="4"></td>
                                                <td class="thick-line text-center">
                                                    <strong>Subtotal</strong></td>
                                                <td class="thick-line text-right currency_symbol">{{$currency_symbol.$sub_total}}</td>
                                            </tr>
                                            @if($data->delivery_type == '0')
                                            @php ($sub_total += $data->shipping_price)
                                            <tr>
                                                <td class="no-line text-center" colspan="4"></td>
                                                <td class="thick-line text-center">
                                                    <strong>Shipping</strong></td>
                                                <td class="thick-line text-right currency_symbol">{{$currency_symbol.$data->shipping_price}}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td class="no-line text-center" colspan="4"></td>
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
