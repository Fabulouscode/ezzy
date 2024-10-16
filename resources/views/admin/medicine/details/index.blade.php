@extends('layouts.backend')

@section('title','Medicine Details List')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Medicine Details</li>
                </ol>
            </div>
            <h5 class="page-title">Medicine Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    @can('medicine_details-add')
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="addExportRows()" class="btn btn-info">Medicine File Exports</a>
                        <a href="javascript:void(0)" onclick="addImportRow()" class="btn btn-info">Medicine File Import</a>
                        <a href="{{url('/donotezzycaretouch/medicine/details/create')}}" class="btn btn-info">Add Medicine Details</a>
                    </div>
                    @endcan

                    <div class="table-responsive">
                        <table id="medicine_details_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Medicine Name</th>
                                    <th>Medicine SKU</th>
                                    <th>Medicine Category Name</th>
                                    <th>Status</th>
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
<div class="modal fade bs-addMedicineImport" id="addMedicineImport" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Medicine Import</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="medicine_import_form" name="medicine_import_form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Medicine File Import</label>
                            <input required id="medicine_file" type="file" required class="form-control" name="medicine_file" />
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-md-12">
                            <button type="submit" id="submit_btn" class="btn btn-primary waves-effect waves-light">
                               Submit
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
    var medicine_details_url = "{{url('/donotezzycaretouch/medicine/details')}}";
    var file_upload_url = "{{url('/donotezzycaretouch/image/upload')}}";
    var file_remove_url = "{{url('/donotezzycaretouch/image/remove')}}";
    var storage_url = "{{url('/donotezzycaretouch/storage')}}";
</script>
<script src="{{ asset('js/admin/medicine_details.js') }}" ></script>
@endsection