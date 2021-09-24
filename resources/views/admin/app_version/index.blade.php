@extends('layouts.backend')

@section('title','App Version Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch/healthcare/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">App Version Details</li>
                </ol>
            </div>
            <h5 class="page-title">App Version Details</h5>
        </div>
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" action="{{ url('donotezzycaretouch/app_version') }}"  id="user_form" name="user_form" enctype="multipart/form-data">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">App Version Details</h4>
                                 <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Android Version</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text" required placeholder="Android Version" class="form-control @error('android_version') is-invalid @enderror" name="android_version" value="{{!empty($data->android_version) ? $data->android_version : old('android_version') }}" >
                                            @error('android_version')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>IOS Version</label></dt>
                                        <dd class="col-sm-7"> 
                                            <input type="text" required placeholder="IOS Version" class="form-control @error('ios_version') is-invalid @enderror" name="ios_version" value="{{!empty($data->ios_version) ? $data->ios_version : old('ios_version') }}" >
                                            @error('ios_version')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </dd>
                                    </div>
                                    <div class="row d-flex justify-content-end mt-3">
                                        <div class="form-group col-md-12 d-flex justify-content-end">
                                            <button type="submit" class="btn btn-info waves-effect m-l-5">
                                                Update
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
    var base_url = "{{url('/donotezzycaretouch')}}";
    var user_url = "{{url('/donotezzycaretouch/user')}}";
     var data_obj = {};
     var data_status  = {};
</script>
<script src="{{ asset('js/admin/provider.js') }}" ></script>
@endsection