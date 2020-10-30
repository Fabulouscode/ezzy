@extends('invoice.layout')

@section('title','Invoice Order Details')

@section('content')

<div class="container-fluid">
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
                              <div class="col-6 float-right">
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
                              <div class="col-12 m-t-10">
                                  <address>
                                      <strong>Payment Method:</strong>
                                      Visa ending **** 4242, {{$data->clientDetails->email}}
                                  </address>
                                  <address>
                                      <strong>Delivery Type:</strong>&nbsp;&nbsp;{{$delivery_type[$data->delivery_type]}}
                                  </address>
                                  <address>
                                      <strong>Status:</strong>&nbsp;&nbsp;{{$status[$data->status]}}
                                  </address>
                                  <address>
                                      <strong>Order Date:</strong>&nbsp;&nbsp;{{$data->created_at}}
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
                                              <td>{{$order_details->medicineDetails->medicine_name}}</td>
                                              <td class="text-center">{{$order_details->medicineDetails->medicine_sku}}</td>
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
