@extends('layouts.backend')

@section('title','Manage Fees')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Manage Fees</li>
                </ol>
            </div>
            <h5 class="page-title">Manage Fees</h5>
        </div>
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">     
                    @can('fees-add')
                    <!-- <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="addRow()" class="btn btn-info">Add Fees</a>
                    </div>  -->
                    @endcan                                            
                    <div class="table-responsive">
                        <table id="manage_fees_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <!-- <th>Id</th> -->
                                    <th>HCP Type</th>
                                    <th>Fees (%)</th>
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
<!-- Add modal -->
<div class="modal fade bs-addManageFees" id="addManageFees" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="manage_fees_form" name="manage_fees_form">
                    @csrf
                    <input id="fees_id" type="hidden" name="id" >
                    <input id="category_id" type="hidden" name="category_id" >
                                
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>HCP Type</label>
                            <select  type="text" disabled required id="category_id_select" class="form-control" name="category_id_select" >
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Fees (%)</label>
                            <input type="text" required placeholder="Fees (%)" class="form-control" id="fees_percentage" name="fees_percentage"  autocomplete="fees_percentage" autofocus>
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
<!-- container fluid End -->
@endsection

@section('script')
<script>
    var manage_fees_url = "{{url('/manage_fees')}}";
</script>
<script src="{{ asset('js/admin/manage_fees.js') }}" ></script>
@endsection