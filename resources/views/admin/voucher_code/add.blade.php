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
                            <div class="form-group col-md-12">
                                <label>Voucher Name</label>
                                <input id="voucher_name" type="text" required class="form-control @error('voucher_name') is-invalid @enderror" name="voucher_name" value="{{!empty($data->voucher_name) ? $data->voucher_name : old('voucher_name') }}" autocomplete="voucher_name" autofocus/>
                                @error('voucher_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
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
                            <div class="form-group col-md-12">
                                <label>Quantity</label>
                                <input id="quantity" type="text" required class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{!empty($data->quantity) ? $data->quantity : old('quantity') }}" />
                                @error('quantity')
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