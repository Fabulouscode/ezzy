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
                    <li class="breadcrumb-item active">Medicine Details</li>
                </ol>
            </div>
            <h5 class="page-title">Medicine Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    @can('medicine_details-add')
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{url('/medicine/details/create')}}" class="btn btn-info">Add Medicine Details</a>
                    </div>
                    @endcan

                    <div class="table-responsive">
                        <table id="medicine_details_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <!-- <th>Id</th> -->
                                    <th>Medicine Name</th>
                                    <th>Medicine SKU</th>
                                    <th>Medicine Subcategory Name</th>
                                    <th>Status</th>
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
    var medicine_details_url = "{{url('/medicine/details')}}";
</script>
<script src="{{ asset('js/admin/medicine_details.js') }}" ></script>
@endsection