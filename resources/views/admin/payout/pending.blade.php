@extends('layouts.backend')

@section('title','Pending Payout')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pending Payout</li>
                </ol>
            </div>
            <h5 class="page-title">Pending Payout</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="exportExcel()" class="btn btn-info">Export</a>
                    </div>
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="payout()" class="btn btn-info">Payout Approved</a>
                    </div>
                    <!-- Custom Filter -->
                    <div id="AdvanceFiletrShow" class="mb-4 ml-3 justify-content-start">
                        <label>Advanced Filter</label>
                        <div class="row mb-3">                       
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>Hcp Type</label>
                                    <select id="searchByHcpType" name="category_id" class="form-control">
                                        <option value=''>Select Hcp Type</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>       
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="payout_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="id" class="minimal" id="select_all"></th>
                                    <th>User Name</th>         
                                    <th>Service Provider</th>
                                    <th>Bank Details</th>
                                    <th>Amount</th>
                                    <th>Deduction</th>
                                    <th>Payout Amount</th>
                                    <th>Payout Status</th>
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
<!-- Add modal -->
<div class="modal fade bs-addPayoutAmount" id="addPayoutAmount" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="payout_amount_form" name="payout_amount_form">
                    @csrf
                    <input id="user_id" type="hidden" name="user_id" >
                    <input id="amount" type="hidden" name="amount" >
                    <input id="deduction" type="hidden" name="deduction" >
                    <input id="payout_amount" type="hidden" name="payout_amount" >

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Transaction ID</label>
                            <input type="text" required placeholder="Transaction ID" class="form-control" id="bank_transaction_id" name="bank_transaction_id"  autofocus>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Notes</label>
                            <input type="text" required placeholder="Notes" class="form-control" id="notes" name="notes"  >
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-md-12">
                            <button type="submit" id="submit_btn" class="btn btn-primary waves-effect waves-light">
                               
                            </button>
                            <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End modal -->
@endsection

@section('script')
<script>
    var payout_url = "{{url('/donotezzycaretouch/payout')}}";
    var payout_obj = {'payout_status':'1'};
    var payout_status = 1;
    var payout_history = {};
</script>
<script src="{{ asset('js/admin/payout.js') }}" ></script>
@endsection