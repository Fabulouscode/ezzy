@extends('layouts.backend')

@section('title', 'View Activity Details')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="float-right page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/donotezzycaretouch') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/donotezzycaretouch/Admin_activity') }}">View Activity Details</a>
                        </li>
                        <li class="breadcrumb-item active">View</li>
                    </ol>
                </div>
                <h5 class="page-title">View Activity Details</h5>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 header-title">View Activity Details</h4>
                                <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Title</label></dt>
                                        <dd class="col-sm-7">
                                            @if (!empty($data->title))
                                                {{ $data->title }}
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Description</label></dt>
                                        <dd class="col-sm-7">
                                            @if (!empty($data->description))
                                                {{ $data->description }}
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Old Values</label></dt>
                                        <dd class="col-sm-7">
                                            @if (!empty($data->old_values))
                                                {{ $data->old_values }}
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>New Values</label></dt>
                                        <dd class="col-sm-7">
                                            @if (!empty($data->new_values))
                                                {{ $data->new_values }}
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Date Time</label></dt>
                                        <dd class="col-sm-7">
                                            @if (!empty($data->created_at))
                                                {{ Helper::getTimeFormate($data->created_at) }}
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <a href="{{url('/donotezzycaretouch/admin_activity')}}">
                                    <button type="button" class="btn btn-secondary waves-effect m-l-5">
                                        Cancel
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
@endsection

@section('script')
    
@endsection
