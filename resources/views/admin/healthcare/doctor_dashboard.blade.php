@extends('layouts.backend')

@section('title','Doctor Dashboard')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Main Dashboard</a></li>
                    <li class="breadcrumb-item active">Doctor Dashboard</li>
                </ol>
            </div>
            <h5 class="page-title">Doctor Dashboard</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        @if(!empty($data) && count($data) > 0)
            @foreach($data as $element)
                <div class="col-xl-3 col-md-6">
                    <div class="card d-card-part mini-stat m-b-30 {{$element['color']}}">
                        <div class="card-d-title text-white">
                            <div class="mini-stat-icon">
                                <i class="mdi mdi-account float-right mb-0"></i>
                            </div>
                            <h6 class="mb-0">{{$element['name']}}</h6>
                        </div>
                        <div class="card-body d-card-body">
                            <div class="mt-2 text-muted">
                                <div class="d-flex justify-content-between">
                                    <a href="{{url('/donotezzycaretouch/healthcare/user').'?hcp_type='.$element['name']}}">
                                        <h6>Approved <span class="d-block mb-1 d-number-count">{{ isset($element['approved_count']) ? $element['approved_count'] : '0'}}</span></h6>
                                    </a>
                                    <a href="{{url('/donotezzycaretouch/healthcare/user/pending').'?hcp_type='.$element['name']}}">
                                        <h6>Pending <span class="d-block mb-1 d-number-count">{{ isset($element['unapproved_count']) ? $element['unapproved_count'] : '0'}}</span> </h6>
                                    </a>
                                    <a href="{{url('/donotezzycaretouch/healthcare/user')}}">
                                        <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($element['total_count']) ? $element['total_count'] : '0'}}</span> </h6>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

</div><!-- container fluid End -->

@endsection
@section('script')
<!-- <script src="{{ asset('admin/pages/dashboard.js') }}"></script> -->
@endsection