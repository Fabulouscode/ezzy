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
       
                    <form method="POST" action="{{ url('support_request') }}" id="support_request_form" name="support_request_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                     
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Title</label>
                                <input disabled type="text"  class="form-control" name="title" value="{{$data->title}}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <input disabled type="text"  class="form-control" name="title" value="{{array_key_exists($data->status, $status) ? $status[$data->status]: ''}}" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Description</label>
                                <textarea disabled rows="5" class="form-control" name="description">{{$data->description}}</textarea>
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