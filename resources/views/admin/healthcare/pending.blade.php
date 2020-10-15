@extends('layouts.backend')

@section('title','User List')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/healthcare/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pending Health Care Providers</li>
                </ol>
            </div>
            <h5 class="page-title">Pending Health Care Providers</h5>
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
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Mobile No.</th>
                                <th>Category</th>
                                <th>Subcategory</th>
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
    var data = {'status':'1', 'category_id':'1'};
</script>
<script src="{{ asset('js/admin/user.js') }}" ></script>
@endsection