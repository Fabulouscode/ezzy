@extends('layouts.backend')

@section('title','Permissions')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Permissions</li>
                </ol>
            </div>
            <h5 class="page-title">Permissions</h5>
        </div>
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">      
                    @can('permission-add')
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="addRow()" class="btn btn-info">Add Permission</a>
                    </div>                            
                    @endcan              
                    <div class="table-responsive">
                        <table id="permission_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <!-- <th>Id</th> -->                                
                                    <th>Permission Category Name</th>
                                    <th>Permission Name</th>
                                    <th>Permission Value</th>
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
<div class="modal fade bs-addPermission" id="addPermission" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="permission_form" name="permission_form">
                    @csrf
                    <input id="permission_id" type="hidden" name="id" >
                                
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Permission Category</label>
                            <select  required id="permission_category_id" class="form-control" name="permission_category_id" >
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Permission Name</label>
                            <input type="text" required placeholder="Permission Name" class="form-control" id="permission_title" name="permission_title" >
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Permission Value</label>
                            <input type="text" required placeholder="Permission Value" class="form-control" id="permission_name" name="permission_name" >
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
    var permission_url = "{{url('/permission')}}";
</script>
<script src="{{ asset('js/admin/permission.js') }}" ></script>
@endsection