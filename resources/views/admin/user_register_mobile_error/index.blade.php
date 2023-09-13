@extends('layouts.backend')

@section('title','Mobile No Register Time Send OTP Error List')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Mobile No Register Time Send OTP Error List</li>
                </ol>
            </div>
            <h5 class="page-title">Mobile No Register Time Send OTP Error List</h5>
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
                        <table id="register_mobile_no_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Country Code</th>
                                    <th>Mobile No</th>
                                    <th>Device Type</th>
                                    <th>Device Id</th>
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
    var user_tracking_url = "{{url('/donotezzycaretouch/register/mobile_no')}}";
</script>
<script src="{{ asset('js/admin/user_tracking.js') }}" ></script>
@endsection