@extends('layouts.backend')

@section('title','Approved Health Care Providers')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/healthcare/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Approved  Providers</li>
                </ol>
            </div>
            <h5 class="page-title">Approved Health Care Providers</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                
                    <!-- <div class="block-options-item mb-3 ml-3">
                        <a href="{{url('/user/create')}}" class="btn btn-info">Add User</a>
                    </div> -->
                    {!! $dataTable->table(['class' => 'table table-striped table-bordered dt-responsive nowrap', 'style' => 'border-collapse: collapse; border-spacing: 0; width: 100%;'], true) !!}
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
<!-- container fluid End -->
@endsection

@section('script')
{!! $dataTable->scripts() !!}
<script>
    var user_url = "{{url('/user')}}";
    var data_obj = {'status': ['0','2'], 'category_id':'1', 'provider':'healthcare'};
</script>
<script src="{{ asset('js/admin/user.js') }}" ></script>
@endsection