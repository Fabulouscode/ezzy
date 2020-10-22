@extends('layouts.backend')

@if(!empty($data->id))
    @section('title','Edit Pharmacy Details')
@else
    @section('title','Add Pharmacy Details')
@endif

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/support_request')}}">Support Request</a></li>
                    <li class="breadcrumb-item active">{{ !empty($data->id) ? 'Edit' : 'Add' }} Support Request</li>
                </ol>
            </div>
            <h5 class="page-title">{{ !empty($data->id) ? 'Edit' : 'Add' }} Support Request</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" action="{{ url('support_request') }}" id="support_request_form" name="user_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <input id="id" type="hidden" name="user_id" value="{{ !empty($data->user_id) ? $data->user_id : '' }}">
                       
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Title</label>
                                <input readonly type="text"  class="form-control" name="title" value="{{ !empty($data->title) ? $data->title : old('title') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <select required class="form-control @error('status') is-invalid @enderror" name="status" >
                                    <option value="">Select Status</option>
                                    @foreach($status as $key => $value)
                                        <option value="{{$key}}"  {{ isset($data->status) && $key == $data->status ? 'selected' : '' }}>{{$value}}</option>
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
                                <label>Description</label>
                                <textarea readonly rows="5" class="form-control" name="description">{{$data->description}}</textarea>
                            </div>
                        </div>      

                       
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{ url('support_request') }}">
                                    <button type="reset" class="btn btn-secondary waves-effect m-l-5">
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