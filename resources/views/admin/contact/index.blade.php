@extends('layouts.backend')

@section('title','Contact Form Details')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Contact Form Details</li>
                </ol>
            </div>
            <h5 class="page-title">Contact Form Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <!-- Custom Filter -->
                    <div class="row">
                        <div class="col-md-6">
                            <div id="AdvanceFiletrShow" class="mb-4 ml-3 justify-content-start">
                                <label>Advanced Filter</label>
                                <div class="col-md-6">
                                    <div className="form-group">
                                        <label>Date Range</label>
                                        <input type="text" class="form-control" name="date_range" id="contact-date-range"  />
                                        <input type="hidden" class="form-control" id="contact_start_date" name="start_date" />
                                        <input type="hidden" class="form-control" id="contact_end_date" name="end_date"  />     
                                    </div>
                                </div>   
                            </div>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table id="contact_form_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Country</th>
                                    <th>Mobile</th>
                                    <th>Subject</th>
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
    var contact_form_url = "{{url('/donotezzycaretouch/contact_form')}}";
</script>
<script src="{{ asset('js/admin/contact_form.js') }}" ></script>
@endsection