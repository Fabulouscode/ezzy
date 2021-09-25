@extends('layouts.backend')

@section('title','User Tracking Details')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">User Tracking Details</li>
                </ol>
            </div>
            <h5 class="page-title">User Tracking Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <!-- <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{url('/donotezzycaretouch/user/create')}}" class="btn btn-info">Add User</a>
                    </div> -->

                    <div class="table-responsive">
                        <table id="user_tracking_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>User Type</th>
                                    <th>Admin Name</th>
                                    <th>User Name</th>
                                    <th>Field Name</th>
                                    <th>Field Value</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>                        
                        </table>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
<!-- container fluid End -->
@endsection

@section('script')
<script>
    var user_tracking_url = "{{url('/donotezzycaretouch/user_trackings')}}";
</script>
<script src="{{ asset('js/admin/user_tracking.js') }}" ></script>
@endsection