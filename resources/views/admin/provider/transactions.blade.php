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
            @if($provider == 'patients')
            <div class="col-xl-3 col-md-6">
                <div class="card d-card-part bg-info mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="fa fa-money float-right mb-0"></i>
                        </div>
                        <h6 class="mb-0">Wallet Balance</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <h6><span class="d-block mb-1 d-number-count">{{ !empty($patient_wallet_balance) ? $currency_symbol.($patient_wallet_balance->wallet_balance + $patient_wallet_balance->lock_wallet_balance) : 0  }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card d-card-part bg-primary mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="fa fa-money float-right mb-0"></i>
                        </div>
                        <h6 class="mb-0">Available Balance</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <h6><span class="d-block mb-1 d-number-count">{{ !empty($patient_wallet_balance) ? $currency_symbol.$patient_wallet_balance->wallet_balance : 0  }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card d-card-part bg-primary mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="fa fa-money float-right mb-0"></i>
                        </div>
                        <h6 class="mb-0">Locked Balance</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <h6><span class="d-block mb-1 d-number-count">{{ !empty($patient_wallet_balance) ? $currency_symbol.$patient_wallet_balance->lock_wallet_balance : 0  }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if($provider != 'patients')
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
                                <h6><span class="d-block mb-1 d-number-count" >{{ isset($payout_pending_balance) && isset($payout_approved_balance) ? $currency_symbol.($payout_approved_balance + $payout_pending_balance) : 0  }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card d-card-part bg-primary mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="fa fa-money float-right mb-0"></i>
                        </div>
                        <h6 class="mb-0">Approved Payout</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <h6><span class="d-block mb-1 d-number-count">{{ isset($payout_approved_balance) ? $currency_symbol.$payout_approved_balance : 0  }}</span></h6>
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
                        <h6 class="mb-0">Pending Payout</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <h6><span class="d-block mb-1 d-number-count">{{ isset($payout_pending_balance) ? $currency_symbol.$payout_pending_balance : 0  }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="col-md-3 block-options-item mb-3 float-left">
                            <div class="form-group">
                                <label>Date Range</label>
                                <input type="text" class="form-control" name="date_range" id="transaction-date-range"  />
                                    <input type="hidden" class="form-control" id="start_date" name="start" />
                                    <input type="hidden" class="form-control" id="end_date" name="end"  />
                                    <input type="hidden" class="form-control" id="user_id" name="user_id"  value = "{{ $id }}" />
                                    <input type="hidden" class="form-control" id="provider" name="provider" value = "{{ $provider }}"/>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="user_transaction_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>HCP Provider Name</th>
                                    <th>Patient Name</th>                                    
                                    <th>Transaction</th>
                                    <th>Transaction date</th>
                                    <th>Transaction Type</th>
                                    <th>Amount</th>
                                    <th>Payout Amount</th>
                                    <th>Paymet Type</th>
                                    <th>Status</th>
                                    <th>Payout Status</th>
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
    $('#start_date').val(moment().subtract(30, 'days').format("YYYY-MM-DD"));
    $('#end_date').val(moment().format("YYYY-MM-DD"));
    var data_obj = {};
</script>
<script src="{{ asset('js/admin/provider.js') }}" ></script>
@endsection