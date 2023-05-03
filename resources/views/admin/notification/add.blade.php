@extends('layouts.backend')

@section('title','Add Notification')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Add Notification</li>
                </ol>
            </div>
            <h5 class="page-title">Add Notification</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">{{!empty($data->id) ? 'Edit' : 'Add' }} Notification</h4>
       
                    <form method="POST" action="{{ url('donotezzycaretouch/notifications') }}" id="notification_form" name="notification_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Title</label>
                                <input id="title" type="text" required class="form-control @error('title') is-invalid @enderror" name="title" value="{{!empty($data->title) ? $data->title : old('title') }}" autocomplete="title" autofocus/>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Message</label>
                                <textarea id="message" rows="3" required class="form-control @error('message') is-invalid @enderror" name="message" >{{!empty($data->message) ? $data->message : old('message') }}</textarea>
                                @error('message')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                @if($hcp_types)
                                @foreach($hcp_types as $hcp_type)
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="send_category[]" value="{{$hcp_type->id}}" id="{{$hcp_type->name.'_'.$hcp_type->id}}" {{ isset($data->send_category) && in_array($hcp_type->id, $data->send_category) ? 'checked' : '' }} >
                                        <label class="custom-control-label" for="{{$hcp_type->name.'_'.$hcp_type->id}}">{{$hcp_type->name}}</label>
                                    </div>
                                @endforeach
                                @endif
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="send_category[]" value="1" id="patients_1" {{ isset($data->send_category) && in_array('0', $data->send_category) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="patients_0">Patients</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{ url('/donotezzycaretouch/notifications') }}">
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
    var notification_url = "{{url('/donotezzycaretouch/notifications')}}";
</script>
<script src="{{ asset('js/admin/notification.js') }}" ></script>
@endsection