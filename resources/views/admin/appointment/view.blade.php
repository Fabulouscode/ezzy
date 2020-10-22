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
                                <label>Date</label>
                                <input disabled  type="text"  class="form-control" name="appointment_date" value="{{$data->appointment_date}}" />
                            </div>
                            <div class="form-group col-md-4">
                                <label>Time</label>
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
                        

                        @if(!empty($data->status) && $data->status == '6')
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title"> Cancel Details</h4>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Cancel Date Time</label>
                                    <input disabled type="text"  class="form-control" name="cancel_date" value="{{$data->cancel_date}}" />
                                </div>                            
                                <div class="form-group col-md-4">
                                    <label>Cancel Reason</label>
                                    <textarea disabled class="form-control" name="cancel_reason"  >{{$data->cancel_reason}}</textarea>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Cancel User</label>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!empty($data->userDetails))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title"> {!! Helper::getCategoryName($data->userDetails->category_id) !!} Details</h4>
                            <div class="row">
                                @if(!empty($data->userDetails->category_id))
                                <div class="form-group col-md-4">
                                    <label>Category</label>
                                    <select disabled id="category_id"  type="text" class="form-control" name="category_id" >
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}"  {{ !empty($data->userDetails->category_id) && $category->id == $data->userDetails->category_id ? 'selected' : '' }}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                @if(!empty($data->userDetails->subcategory_id))
                                <div class="form-group col-md-4">
                                    <label>Subcategory</label>
                                    <select disabled id="subcategory_id"  type="text" class="form-control" name="subcategory_id" >
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}"  {{ !empty($data->userDetails->subcategory_id) && $category->id == $data->userDetails->subcategory_id ? 'selected' : '' }}>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>First Name</label>
                                    <input disabled type="text" class="form-control" name="first_name" value="{{$data->userDetails->first_name}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Last Name</label>
                                    <input disabled type="text" class="form-control" name="last_name" value="{{$data->userDetails->last_name}}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input disabled type="text" class="form-control" name="email" value="{{$data->userDetails->email}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Mobile No.</label>
                                    <input disabled type="text" class="form-control" name="mobile_no" value="{{$data->userDetails->mobile_no}}" />
                                </div>
                            </div>
                        </div>
                        @endif

                       @if(!empty($data->clientDetails))
                        <div class="border border-dark rounded p-3 mb-3">
                            <h4 class="mt-0 header-title">Appointment Customer Details</h4>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>First Name</label>
                                    <input disabled type="text" class="form-control" name="first_name" value="{{$data->clientDetails->first_name}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Last Name</label>
                                    <input disabled type="text" class="form-control" name="last_name" value="{{$data->clientDetails->last_name}}" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Email</label>
                                    <input disabled type="text" class="form-control" name="email" value="{{$data->clientDetails->email}}" />
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Mobile No.</label>
                                    <input disabled type="text" class="form-control" name="mobile_no" value="{{$data->clientDetails->mobile_no}}" />
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        

                        <div class="form-group">
                            <div>
                                <a href="{{ url('appointment') }}">
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