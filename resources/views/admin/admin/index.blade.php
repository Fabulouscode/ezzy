@extends('layouts.backend')

@section('title','Admin List')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Admin List</li>
                </ol>
            </div>
            <h5 class="page-title">Admin List</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    @can('admin-add')
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{url('/admin/users/create')}}" class="btn btn-info">Add Admin </a>
                    </div>                   
                    @endcan
                    <div class="table-responsive">
                        <table id="user_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                   <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
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
    var admin_user_url = "{{url('/admin/users')}}";
</script>
<script src="{{ asset('js/admin/admin_user.js') }}" ></script>
@endsection