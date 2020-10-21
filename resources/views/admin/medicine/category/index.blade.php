@extends('layouts.backend')

@section('title','Category List')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Medicine</a></li>
                    <li class="breadcrumb-item active">Medicine Categories</li>
                </ol>
            </div>
            <h5 class="page-title">Medicine Categories</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                
                    <div class="block-options-item mb-3 ml-3">
                        <a href="{{url('/medicine/categories/create')}}" class="btn btn-outline-info">Add Medicine Category</a>
                    </div>

                    <table id="medicine_category_datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Category Name</th>
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
    var medicine_category_url = "{{url('/medicine/categories')}}";
</script>
<script src="{{ asset('js/admin/medicine_category.js') }}" ></script>
@endsection