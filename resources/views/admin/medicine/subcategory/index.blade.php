@extends('layouts.backend')

@section('title','Medicine Subcategories')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Medicine Subcategories</li>
                </ol>
            </div>
            <h5 class="page-title">Medicine Subcategories</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    @can('medicine_subcategory-add')
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="addRow()" class="btn btn-info">Add Medicine Subcategory</a>
                    </div>
                    @endcan

                    <div class="table-responsive">
                        <table id="medicine_subcategory_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Subcategory Name</th>
                                    <th>Category Name</th>
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
<div class="modal fade bs-addMedicineSubcategory" id="addMedicineSubcategory" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="medicine_subcategory_form" name="medicine_subcategory_form">
                    @csrf
                    <input id="medicine_subcategory_id" type="hidden" name="id" >
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Subcategory Name</label>
                            <input id="medicine_subcategory_name" type="text" required class="form-control" name="name" />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Medicine Category</label>
                            <select required class="form-control" id="medicine_category" name="medicine_category_id" >
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Status</label>
                            <select required class="form-control" id="medicine_subcategory_status" name="status" >
                            </select>
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
    var medicine_subcategory_url = "{{url('/donotezzycaretouch/medicine/subcategories')}}";
</script>
<script src="{{ asset('js/admin/medicine_subcategory.js') }}" ></script>
@endsection