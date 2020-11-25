@extends('layouts.backend')

@if(!empty($data->id))
    @section('title','Edit Voucher Code Details')
@else
    @section('title','Add Voucher Code Details')
@endif

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/voucher_code')}}">Voucher Code</a></li>
                    <li class="breadcrumb-item active">{{!empty($data->id) ? 'Edit' : 'Add' }}</li>
                </ol>
            </div>
            <h5 class="page-title">{{!empty($data->id) ? 'Edit' : 'Add' }} Voucher Code Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" action="{{ url('voucher_code') }}" id="voucher_code_form" name="voucher_code_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Voucher Name</label>
                                <input id="voucher_name" type="text" required class="form-control @error('voucher_name') is-invalid @enderror" name="voucher_name" value="{{!empty($data->voucher_name) ? $data->voucher_name : old('voucher_name') }}" autocomplete="voucher_name" autofocus/>
                                @error('voucher_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>                            
                            <div class="form-group col-md-6">
                                <label>Voucher Code</label>
                                <input id="voucher_code" type="text" required class="form-control @error('voucher_code') is-invalid @enderror" name="voucher_code" value="{{!empty($data->voucher_code) ? $data->voucher_code : old('voucher_code') }}" />
                                @error('voucher_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Quantity</label>
                                <input id="quantity" type="text" data-parsley-type="digits" required class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{!empty($data->quantity) ? $data->quantity : old('quantity') }}" />
                                @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Expiry Date</label>
                                <input id="expiry_date" type="datetime-local" required class="form-control @error('expiry_date') is-invalid @enderror" name="expiry_date" value="{{!empty($data->expiry_date) ? $data->expiry_date : old('expiry_date') }}" />
                                @error('expiry_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Percentage</label>
                                <input id="percentage" type="text" class="form-control @error('percentage') is-invalid @enderror" name="percentage" value="{{!empty($data->percentage) ? $data->percentage : old('percentage') }}" />
                                @error('percentage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Fix Amount</label>
                                <input id="fix_amount" type="text" class="form-control @error('fix_amount') is-invalid @enderror" name="fix_amount" value="{{!empty($data->fix_amount) ? $data->fix_amount : old('fix_amount') }}" />
                                @error('fix_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Min Amount</label>
                                <input id="min_amount" type="text" class="form-control @error('min_amount') is-invalid @enderror" name="min_amount" value="{{!empty($data->min_amount) ? $data->min_amount : old('min_amount') }}" />
                                @error('min_amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Voucher Type</label>
                                <select  type="text" class="form-control @error('voucher_type') is-invalid @enderror" name="voucher_type" >
                                    @foreach($voucher_type as $key => $value)
                                        <option value="{{$key}}" {{ isset($data->voucher_type) && $key == $data->voucher_type ? 'selected' : '' }}>{{$value}}</option>
                                    @endforeach
                                </select>
                                @error('voucher_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <select  type="text" class="form-control @error('status') is-invalid @enderror" name="status" >
                                    @foreach($status as $key => $value)
                                        <option value="{{$key}}" {{ isset($data->status) && $key == $data->status ? 'selected' : '' }}>{{$value}}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       
                        <div class="row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{ url('voucher_code') }}">
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
    var voucher_code_url = "{{url('/voucher_code')}}";
</script>
<script src="{{ asset('js/admin/voucher_code.js') }}" ></script>
@endsection