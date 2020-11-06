@extends('layouts.backend')

@section('title','Services')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Services</li>
                </ol>
            </div>
            <h5 class="page-title">Services</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                
                    <div class="block-options-item mb-3 ml-3">
                        <a href="{{url('/services/create')}}" class="btn btn-info">Add Service</a>
                    </div>

                    <table id="services_datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Service Name</th>
                                <th>Service Type</th>
                                <th>Status</th>
                                <th>Action</th>
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
    var services_url = "{{url('/services')}}";
</script>
<script src="{{ asset('js/admin/services.js') }}" ></script>
@endsection