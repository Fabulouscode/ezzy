@extends('layouts.backend')

@section('title',array_key_exists($provider, $provider_names) ? $provider_names[$provider].' Service Details': 'Service Details ')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/'.$provider .'/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/'.$provider .'/user')}}">
                        {{array_key_exists($provider, $provider_names) ? $provider_names[$provider]: ''}}
                    </a></li>
                    <li class="breadcrumb-item active">Service Details</li>
                </ol>
            </div>
            <h5 class="page-title"> Service Details </h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <table id="service_laboratories_datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Service Name</th>
                                <th>Service Amount</th>
                                <th>Service Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>                        
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
<!-- container fluid End -->
@endsection

@section('script')
<script>
    var user_url = "{{url('/user')}}";
    var data_obj = {'provider': '{{ $provider }}','user_id': '{{$id}}'};
</script>
<script src="{{ asset('js/admin/provider.js') }}" ></script>
@endsection