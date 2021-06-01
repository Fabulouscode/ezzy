@extends('layouts.backend')

@section('title','Medicine Details')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/pharmacy/user')}}">Pharmacy</a></li>
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

                    <div class="table-responsive">
                        <table id="shop_medicine_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Medicine Name</th>
                                    <th>Medicine SKU</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Medicine Type</th>
                                    <th>Status</th>
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
    var user_url = "{{url('/user')}}";
    var data_obj = {'user_id': '{{$id}}'};
</script>
<script src="{{ asset('js/admin/provider.js') }}" ></script>
@endsection