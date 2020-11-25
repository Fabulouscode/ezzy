@extends('layouts.backend')

@section('title','Health Care Provider Types')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Health Care Provider Types</li>
                </ol>
            </div>
            <h5 class="page-title">Health Care Provider Types</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    @can('hcp_type-add')
                    <div class="block-options-item mb-3 ml-3">
                        <a href="{{url('/category/create')}}" class="btn btn-info">Add Health Care Provider Type</a>
                    </div>
                    @endcan

                    <div class="table-responsive">
                        <table id="category_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Category Name</th>
                                    <th>Parent Category Name</th>
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
    var category_url = "{{url('/category')}}";
</script>
<script src="{{ asset('js/admin/category.js') }}" ></script>
@endsection