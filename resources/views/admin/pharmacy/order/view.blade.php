@extends('layouts.backend')

 @section('title','View Order Details')

@section('content')
<div class="container-fluid">

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
                        
                        
                        @if(!empty($data->userDetails))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title"> Shop Details</h4>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Name</label>
                                    <input disabled type="text" class="form-control" name="first_name" value="{{$data->userDetails->first_name .' '. $data->userDetails->last_name}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input disabled type="text" class="form-control" name="email" value="{{$data->userDetails->email}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Mobile No.</label>
                                    <input disabled type="text" class="form-control" name="mobile_no" value="{{$data->userDetails->mobile_no}}" />
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

                        @if(!empty($data->clientDetails))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Customer Details</h4>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Name</label>
                                    <input disabled type="text" class="form-control" name="first_name" value="{{$data->clientDetails->first_name .' '. $data->clientDetails->last_name}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input disabled type="text" class="form-control" name="email" value="{{$data->clientDetails->email}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Mobile No.</label>
                                    <input disabled type="text" class="form-control" name="mobile_no" value="{{$data->clientDetails->mobile_no}}" />
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
</div>
@endsection

@section('script')
<script>
    var pharmacy_order_url = "{{url('/pharmacy/order')}}";
</script>
<script src="{{ asset('js/admin/pharmacy_order.js') }}" ></script>
@endsection