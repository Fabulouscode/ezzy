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
            <div class="col-xl-3 col-md-6">
                <div class="card d-card-part bg-info mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="fa fa-money float-right mb-0"></i>
                        </div>
                        <h6 class="mb-0">Total Balance</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <h6><span class="d-block mb-1 d-number-count">{{ $total_balance ?: 0  }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-xl-3 col-md-6">
                <div class="card d-card-part bg-primary mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="fa fa-money float-right mb-0"></i>
                        </div>
                        <h6 class="mb-0">Debit Balance</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <h6><span class="d-block mb-1 d-number-count">{{ $debit_balance ?: 0  }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card d-card-part bg-secondary mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="fa fa-money float-right mb-0"></i>
                        </div>
                        <h6 class="mb-0">Credit Balance</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <h6><span class="d-block mb-1 d-number-count">{{ $credit_balance ?: 0  }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="user_transaction_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>User Name</th>
                                    <th>Transaction</th>
                                    <th>Transaction date</th>
                                    <th>Amount</th>
                                    <th>Created at</th>
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
    var user_url = "{{url('user')}}";
    var data_obj = {'provider': '{{ $provider }}', 'id' : '{{ $id }}' };
</script>
<script src="{{ asset('js/admin/provider.js') }}" ></script>
@endsection