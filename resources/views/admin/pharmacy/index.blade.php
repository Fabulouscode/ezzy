@extends('layouts.backend')

@section('title','Approved Pharmacist')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/dashboard')}}">Pharmacy Dashboard</a></li>
                    <li class="breadcrumb-item active">Approved Pharmacist</li>
                </ol>
            </div>
            <h5 class="page-title">Approved Pharmacist</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                
                    <!-- <div class="block-options-item mb-3 ml-3">
                        <a href="{{url('/user/create')}}" class="btn btn-outline-info">Add User</a>
                    </div> -->

                    <table id="user_datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Mobile No.</th>
                                <th>HCP Type</th>
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
    var user_url = "{{url('/user')}}";
    var data_obj = {'status':['0','2'], 'category_id':'2', 'provider':'pharmacy'};
</script>
<script src="{{ asset('js/admin/user.js') }}" ></script>
@endsection