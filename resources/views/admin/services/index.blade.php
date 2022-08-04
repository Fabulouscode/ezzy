@extends('layouts.backend')

@section('title','Services')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
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
                       <!-- Custom Filter -->
                    <div id="AdvanceFiletrShow" class="mb-4 ml-3 justify-content-start">
                        <label>Advanced Filter</label>
                        <div class="row mb-3">  
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>HCP Type</label>
                                    <select id="searchByHcpType" name="subcategory_id" class="form-control">
                                        <option value=''>Select Hcp Type</option>
                                        @foreach($service_type as $key => $value)
                                            <option value="{{$key}}" >{{$value}}</option>
                                        @endforeach
                                    </select>                                
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>Status</label>
                                    <select id="searchByStatus" name="status" class="form-control">
                                        <option value=''>Select Status</option>
                                        <option value="0">Active</option>
                                        <option value="2">Inactive</option>
                                    </select>       
                                </div>
                            </div>
                        </div>
                    </div>
                    @can('services-add')
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{url('/donotezzycaretouch/services/create')}}" class="btn btn-info">Add Service</a>
                    </div>
                    @endcan

                    <div class="table-responsive">
                        <table id="services_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Service Name</th>
                                    <th>Service Type</th>
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
<!-- container fluid End -->
@endsection

@section('script')
<script>
    var services_url = "{{url('/donotezzycaretouch/services')}}";
</script>
<script src="{{ asset('js/admin/services.js') }}" ></script>
@endsection