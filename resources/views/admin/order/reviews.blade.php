@extends('layouts.backend')

@section('title','Order Reviews')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Order Reviews</li>
                </ol>
            </div>
            <h5 class="page-title">Order Reviews</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <table id="order_review_datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Order No</th>
                                <th>User Name</th>
                                <th>Patient Name</th>
                                <th>Rating</th>
                                <th>Reviews</th>
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
    var order_url = "{{url('pharmacy/order/reviews')}}";
</script>
<script src="{{ asset('js/admin/order_reviews.js') }}" ></script>
@endsection