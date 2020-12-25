@extends('layouts.backend')

@section('title','Services')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Services</li>
                </ol>
            </div>
            <h5 class="page-title">Services</h5>
        </div>
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">     
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="addRow()" class="btn btn-info">Add Service</a>
                    </div>                                          
                    <div class="table-responsive">
                        <table id="service_usage_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Name</th>
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
<div class="modal fade bs-addService" id="addService" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="service_usage_form" name="service_usage_form">
                    @csrf
                    <input id="service_usage_id" type="hidden" name="id" >
                                
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Name</label>
                            <input  type="text" required id="service_usage_name" class="form-control" name="name" >
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
    var service_usage_url = "{{url('/service_usage')}}";
</script>
<script src="{{ asset('js/admin/service_usage.js') }}" ></script>
@endsection