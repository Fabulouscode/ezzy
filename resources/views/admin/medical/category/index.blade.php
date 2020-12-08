@extends('layouts.backend')

@section('title','Medicale Categories')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Medicale Categories</li>
                </ol>
            </div>
            <h5 class="page-title">Medicale Categories</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    @can('medical_category-add')
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="addRow()" class="btn btn-info">Add Medicale Category</a>
                    </div>
                    @endcan

                    <div class="table-responsive">
                        <table id="medical_category_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Medicale Category Name</th>
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
<div class="modal fade bs-addMedicalCategory" id="addMedicalCategory" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="medical_category_form" name="medical_category_form">
                    @csrf
                    <input id="medical_category_id" type="hidden" name="id" >
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Medical Category Name</label>
                            <input id="medical_category_name" type="text" required class="form-control" name="medical_category_name" autofocus/>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Status</label>
                            <select required class="form-control" id="medical_category_status" name="status" >
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
    var medical_category_url = "{{url('/medical_category')}}";
</script>
<script src="{{ asset('js/admin/medical_category.js') }}" ></script>
@endsection