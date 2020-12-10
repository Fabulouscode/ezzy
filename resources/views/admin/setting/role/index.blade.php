@extends('layouts.backend')

@section('title','Role')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Role</li>
                </ol>
            </div>
            <h5 class="page-title">Role</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    @can('role-add')
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{url('/role/create')}}" class="btn btn-info">Add Role</a>
                    </div>
                    @endcan
                    <div class="table-responsive">
                        <table id="role_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <!-- <th>Id</th> -->
                                    <th>Role Name</th>
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
    var role_url = "{{url('/role')}}";
</script>
<script src="{{ asset('js/admin/role.js') }}" ></script>
@endsection