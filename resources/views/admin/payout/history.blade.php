@extends('layouts.backend')

@section('title','Approved Payout')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/payout')}}">Approved Payout</a></li>
                    <li class="breadcrumb-item active">Payout History</li>
                </ol>
            </div>
            <h5 class="page-title">Payout History</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="payout_history_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>                                    
                                    <th>Id</th>   
                                    <th>User Name</th>   
                                    <th>Amount</th>
                                    <th>Deduction</th>
                                    <th>Payout Amount</th>
                                    <th>Transaction Id</th>
                                    <th>Approved Name</th>
                                    <th>Approved Date</th>
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
    var payout_url = "{{url('/payout')}}";
    var payout_obj = {'payout_status':''};    
    var payout_history = {'user_id': {{$id}}};
    var payout_status = '';
</script>
<script src="{{ asset('js/admin/payout.js') }}" ></script>
@endsection