@extends('layouts.backend')

@section('title','Transaction List')

@section('content')
    <!-- container fluid Start -->
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="float-right page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url('/'.$provider.'/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Transaction List</li>
                    </ol>
                </div>
                <h5 class="page-title">Transaction List</h5>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">

                        <table id="user_transaction_datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Username</th>
                                <th>Transaction date</th>
                                <th>Amount</th>
                                <th>Mode of payment</th>
                                <th>Transaction type</th>
                                <th>Status</th>
                                <th>Created at</th>
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
        var user_url = "{{url('healthcare/user/transaction/')}}";
        var data_obj = {'provider': '{{ $provider }}', 'id' : '{{ $id }}' };
    </script>
    <script src="{{ asset('js/admin/user_transaction.js') }}" ></script>
@endsection