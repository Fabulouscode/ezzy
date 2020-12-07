@extends('layouts.backend')

@section('title','Pending Payout')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
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
                
                    <!-- <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="payout()" class="btn btn-info">Payout</a>
                    </div> -->

                    <div class="table-responsive">
                        <table id="payout_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="id" class="minimal" id="select_all"></th>
                                    <th>Service Provider</th>
                                    <th>User Name</th>         
                                    <th>Payout Date</th>
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
@endsection

@section('script')
<script>
    var payout_url = "{{url('/payout')}}";
    var payout_obj = {'payout_status':'1'};
</script>
<script src="{{ asset('js/admin/payout.js') }}" ></script>
@endsection