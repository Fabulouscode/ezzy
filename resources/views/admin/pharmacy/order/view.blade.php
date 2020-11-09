@extends('layouts.backend')

 @section('title','View Order Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/order')}}">Order List</a></li>
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
       
                    <form method="POST"  id="pharmacy_order_form" name="pharmacy_order_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        @if(!empty($data->userLocationDetails))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Shipping Details</h4>
                            <div class="row">
                                <dt class="col-sm-5"><label>Name</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(!empty($data->userLocationDetails->name))
                                        {{$data->userLocationDetails->name}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Email</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(!empty($data->userLocationDetails->email))
                                        {{$data->userLocationDetails->email}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Mobile No.</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(!empty($data->userLocationDetails->mobile_no))
                                        {{$data->userLocationDetails->mobile_no}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Address</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(!empty($data->userLocationDetails->address))
                                        {{$data->userLocationDetails->address}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Delivery Type</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->delivery_type))
                                       {{array_key_exists($data->delivery_type, $delivery_type) ? $delivery_type[$data->delivery_type]: ''}}
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
                        </div>
                        @endif


                        @if(!empty($data->orderProductDetails))                        
                            <div class="border border-dark rounded p-3 mb-3">
                                <h4 class="mt-0 header-title">Medicine Details</h4>
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Medicine Name</th>
                                                <th>Medicine SKU</th>
                                                <th>Medicine Type</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Totals</th>
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
                                                <td>{{$order_details->shopMedicineDetails->medicineDetails->medicine_sku}}</td>
                                                <td>{{$medicine_types[$order_details->shopMedicineDetails->medicine_type]}}</td>
                                                <td>{{$order_details->shopMedicineDetails->mrp_price}}</td>
                                                <td>{{$order_details->quantity}}</td>
                                                <td>{{$medicine_total}}</td>
                                            </tr>
                                        @endforeach
                                            <tr>
                                                <td class="no-line " colspan="4"></td>
                                                <td class="thick-line ">
                                                    <strong>Subtotal</strong></td>
                                                <td class="thick-line ">{{$sub_total}}</td>
                                            </tr>
                                            <tr>
                                                <td class="no-line " colspan="4"></td>
                                                <td class="no-line ">
                                                    <strong>Shipping</strong></td>
                                                <td class="no-line ">{{$data->shipping_price}}</td>
                                            </tr>
                                            <tr>
                                                <td class="no-line " colspan="4"></td>
                                                <td class="no-line ">
                                                    <strong>Total</strong></td>
                                                <td class="no-line "><h4 class="m-0">{{$sub_total + $data->shipping_price}}</h4></td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>                         
                            </div>
                        @endif
                        
                        
                        @if(!empty($data->clientDetails))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Customer Details</h4>
                            <div class="row">
                                <dt class="col-sm-5"><label>User Name</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->clientDetails->first_name))
                                        {{$data->clientDetails->first_name .' '. $data->clientDetails->last_name}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Email</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->clientDetails->email))
                                        {{$data->clientDetails->email}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Mobile No.</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->clientDetails->mobile_no))
                                        {{$data->clientDetails->mobile_no}}
                                    @endif 
                                </dd>
                            </div>
                        </div>
                        @endif

                        @if(!empty($data->userDetails))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title"> Healthcare Provider Details</h4>
                            <div class="row">
                                <dt class="col-sm-5"><label>HCP Type</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->userDetails->categoryParent))
                                        {{$data->userDetails->categoryParent->name}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>User Name</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->userDetails->first_name))
                                        {{$data->userDetails->first_name .' '. $data->userDetails->last_name}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Email</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->userDetails->email))
                                        {{$data->userDetails->email}}
                                    @endif 
                                </dd>
                            </div>
                            <div class="row">
                                <dt class="col-sm-5"><label>Mobile No.</label></dt>
                                <dd class="col-sm-7"> 
                                    @if(isset($data->userDetails->mobile_no))
                                        {{$data->userDetails->mobile_no}}
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



                        <div class="row">
                            <div class="form-group col-md-12">
                                <a href="{{ url('pharmacy/order') }}">
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