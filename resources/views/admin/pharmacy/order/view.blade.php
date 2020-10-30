@extends('layouts.backend')

 @section('title','View Order Details')

@section('content')

<div class="container-fluid">

    <div class="row d-print-none">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/orders')}}">Order List</a></li>
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
                                <h4 class="float-right font-16"><strong>Order # {{ !empty($data->id) ? $data->id : '' }}</strong></h4>
                                <h3 class="m-t-0">
                                    <img src="{{ asset('admin/images/logo.png') }}" alt="logo" height="40"/>
                                </h3>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <address>
                                        <strong>Billed To:</strong><br>
                                        {{$data->clientDetails->first_name}} {{$data->clientDetails->last_name}}<br>
                                        {{$data->clientDetails->email}}<br>
                                        {{$data->clientDetails->mobile_no}}<br>
                                        {{$data->clientDetails->address}}<br>
                                    </address>
                                </div>
                                <div class="col-6 text-right">
                                    <address>
                                        <strong>Shipped To:</strong><br>
                                        {{$data->userLocationDetails->name}}<br>
                                        {{$data->userLocationDetails->email}}<br>
                                        {{$data->userLocationDetails->mobile_no}}<br>
                                        {{$data->userLocationDetails->address}}<br>
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 m-t-30">
                                    <address>
                                        <strong>Payment Method:</strong><br>
                                        Visa ending **** 4242<br>
                                        jsmith@email.com
                                    </address>
                                    <address>
                                        <strong>Delivery Type:</strong>&nbsp;&nbsp;{{$delivery_type[$data->delivery_type]}}
                                    </address>
                                    <address>
                                        <strong>Status:</strong>&nbsp;&nbsp;{{$status[$data->status]}}
                                    </address>
                                </div>
                                <div class="col-6 m-t-30 text-right">
                                    <address>
                                        <strong>Order Date:</strong><br>
                                        {{$data->created_at}}<br><br>
                                    </address>
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
                                                @php ($medicine_total = $order_details->shopMedicineDetails->mrp_price * $order_details->quantity)
                                                @php ($sub_total = $sub_total + $medicine_total)
                                            <tr>
                                                <td>{{$order_details->shopMedicineDetails->medicineDetails->medicine_name}}</td>
                                                <td class="text-center">{{$order_details->shopMedicineDetails->medicineDetails->medicine_sku}}</td>
                                                <td class="text-center">{{$medicine_types[$order_details->shopMedicineDetails->medicine_type]}}</td>
                                                <td class="text-center">{{$order_details->shopMedicineDetails->mrp_price}}</td>
                                                <td class="text-center">{{$order_details->quantity}}</td>
                                                <td class="text-right">{{$medicine_total}}</td>
                                            </tr>                                        
                                            @endforeach
                                            @endif
                                            <tr>
                                                <td class="no-line text-center" colspan="4"></td>
                                                <td class="thick-line text-center">
                                                    <strong>Subtotal</strong></td>
                                                <td class="thick-line text-right">{{$sub_total}}</td>
                                            </tr>
                                            <tr>
                                                <td class="no-line text-center" colspan="4"></td>
                                                <td class="no-line text-center">
                                                    <strong>Shipping</strong></td>
                                                <td class="no-line text-right">{{$data->shipping_price}}</td>
                                            </tr>
                                            <tr>
                                                <td class="no-line text-center" colspan="4"></td>
                                                <td class="no-line text-center">
                                                    <strong>Total</strong></td>
                                                <td class="no-line text-right"><h4 class="m-0">{{$sub_total + $data->shipping_price}}</h4></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-print-none mo-mt-2">
                                        <div class="float-right">
                                            <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i></a>
                                            <a href="{{ url('pharmacy/orders') }}" class="btn btn-primary waves-effect waves-light">Cancel</a>
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
<!-- <div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/orders')}}">Order List</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
            <h5 class="page-title">View Order Details</h5>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" action="{{ url('pharmacy/orders') }}" id="pharmacy_order_form" name="pharmacy_order_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        @if(!empty($data->userLocationDetails))
                        <div >
                            <h4 class="mt-0 header-title">Shipping Details</h4>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Name</label>
                                    <input disabled type="text" class="form-control" name="name" value="{{$data->userLocationDetails->name}}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input disabled type="text" class="form-control" name="email" value="{{$data->userLocationDetails->email}}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Mobile No</label>
                                    <input disabled type="text" class="form-control" name="mobile_no" value="{{$data->userLocationDetails->mobile_no}}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Address</label>
                                    <textarea disabled class="form-control" name="address" >{{$data->userLocationDetails->address}}</textarea>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Delivery Type</label>
                                <input disabled type="text" class="form-control" name="delivery_type" value="{{$delivery_type[$data->delivery_type]}}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <input disabled type="text" class="form-control" name="status" value="{{$status[$data->status]}}" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Total Price</label>
                                <input disabled type="text" class="form-control" name="total_price" value="{{$data->total_price}}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Shipping Price</label>
                                <input disabled type="text" class="form-control" name="last_name" value="{{$data->shipping_price}}" />
                            </div>
                        </div>

                        @if(!empty($data->clientDetails))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Customer Details</h4>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>First Name</label>
                                    <input disabled type="text" class="form-control" name="first_name" value="{{$data->clientDetails->first_name}}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Last Name</label>
                                    <input disabled type="text" class="form-control" name="last_name" value="{{$data->clientDetails->last_name}}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input disabled type="text" class="form-control" name="email" value="{{$data->clientDetails->email}}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Mobile No.</label>
                                    <input disabled type="text" class="form-control" name="mobile_no" value="{{$data->clientDetails->mobile_no}}" />
                                </div>
                            </div>
                        </div>
                        @endif
                      


                        @if(!empty($data->userDetails))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Shop Details</h4>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>First Name</label>
                                    <input disabled type="text" class="form-control" name="first_name" value="{{$data->userDetails->first_name}}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Last Name</label>
                                    <input disabled type="text" class="form-control" name="last_name" value="{{$data->userDetails->last_name}}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input disabled type="text" class="form-control" name="email" value="{{$data->userDetails->email}}" />
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Mobile No.</label>
                                    <input disabled type="text" class="form-control" name="mobile_no" value="{{$data->userDetails->mobile_no}}" />
                                </div>
                            </div>
                        </div>
                        @endif




                        @if(!empty($data->orderProductDetails))                        
                            <div class="border border-dark rounded p-3 mb-3">
                                <h4 class="mt-0 header-title">Product Details</h4>
                            @foreach($data->orderProductDetails as $key => $order_details)
                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Medicine Name</label>
                                            <input disabled type="text" class="form-control" name="medicine_name" value="{{$order_details->shopMedicineDetails->medicineDetails->medicine_name}}" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Medicine SKU</label>
                                            <input disabled type="text" class="form-control" name="medicine_sku" value="{{$order_details->shopMedicineDetails->medicineDetails->medicine_sku}}" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Medicine Type</label>
                                            <input disabled type="text" class="form-control" name="medicine_type" value="{{$medicine_types[$order_details->shopMedicineDetails->medicine_type]}}" />
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Quantity</label>
                                            <input disabled type="text" class="form-control" name="quantity" value="{{$order_details->quantity}}" />
                                        </div>
                                    </div> 
                                    @if(count($data->orderProductDetails) > ($key+1))      
                                    <br>
                                        <hr>
                                    <br> 
                                    @endif                
                            @endforeach                        
                            </div>
                        @endif
                        


                        <div class="row">
                            <div class="form-group col-md-12">
                                <a href="{{ url('pharmacy/orders') }}">
                                    <button type="button" class="btn btn-secondary waves-effect m-l-5">
                                        Cancel
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div> 
    </div>
</div> -->
@endsection

@section('script')
<script>
    var pharmacy_order_url = "{{url('/pharmacy/order')}}";
</script>
<script src="{{ asset('js/admin/pharmacy_order.js') }}" ></script>
@endsection