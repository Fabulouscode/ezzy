@extends('layouts.backend')

@section('title','View Support Request')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/support_request')}}">Support Request</a></li>
                    <li class="breadcrumb-item active">View Support Request</li>
                </ol>
            </div>
            <h5 class="page-title">View Support Request</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" id="support_request_form" name="support_request_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">Support Details</h4>
                                <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Subject</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->title))
                                                {{$data->title}}
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Status</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(isset($data->status))
                                                {{$data->status_name}}
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Description</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->description))
                                                {{$data->description}}
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Attachment File</label></dt>
                                        <dd class="col-sm-7"> 
                                            <img src="{{$data->attachment}}" style="max-width: 100%;height:100px;display:block;">
                                            <a href="{{$data->attachment}}" download>
                                                Click Here to Download
                                            </a>
                                        </dd>
                                    </div>                                    
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Comment</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->comment))
                                                {{$data->comment}}
                                            @endif 
                                        </dd>
                                    </div>
                                    @if($data->status == '3')
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Close Date</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->closed_date))
                                                {{Helper::getDateTimeFormate($data->closed_date)}}
                                            @endif 
                                        </dd>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>              
                        

                        <div class="row">
                            <div class="form-group col-md-12">
                                <a href="{{ url('support_request') }}">
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
    var support_request_url = "{{url('/support_request')}}";
</script>
<script src="{{ asset('js/admin/support_request.js') }}" ></script>
@endsection