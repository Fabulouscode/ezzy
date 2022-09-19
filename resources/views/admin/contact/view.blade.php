@extends('layouts.backend')

@section('title', 'Contact Form Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
            <h5 class="page-title">Contact Form Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST"  id="user_form" name="user_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">Contact Form</h4>
                                <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-3"><label>Name</label></dt>
                                        <dd class="col-sm-9"> 
                                            {{$data->name}} 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-3"><label>Email</label></dt>
                                        <dd class="col-sm-9"> 
                                            {{$data->email}} 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-3"><label>Mobile</label></dt>
                                        <dd class="col-sm-9"> 
                                            {{$data->mobile}} 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-3"><label>Subject</label></dt>
                                        <dd class="col-sm-9"> 
                                            {{$data->subject}} 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-3"><label>Message</label></dt>
                                        <dd class="col-sm-9"> 
                                            {{$data->message}} 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-3"><label>Created Date</label></dt>
                                        <dd class="col-sm-9"> 
                                            {{ !empty($data->created_at) ? Helper::getDateTimeFormate($data->created_at) : '' }}
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="form-group col-md-12">
                                <a href="{{url('/donotezzycaretouch/contact_form')}}">
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
    var user_url = "{{url('/donotezzycaretouch/user')}}";
    var data_obj = {};
    var data_status = '';
    var data_category_id = '';
    var data_provider = '';
</script>
<script src="{{ asset('js/admin/provider.js') }}" ></script>
@endsection