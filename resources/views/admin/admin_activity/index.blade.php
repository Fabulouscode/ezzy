@extends('layouts.backend')

@section('title', 'Admin Activity')

@section('content')
    <!-- container fluid Start -->
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="float-right page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/donotezzycaretouch') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Admin Activity</li>
                    </ol>
                </div>
                <h5 class="page-title">Admin Activity</h5>
            </div>
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        {{-- @can('notification-add') --}}
                            {{-- <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{url('/donotezzycaretouch/notifications/create')}}" class="btn btn-info">Add Notification</a>
                    </div> --}}
                        {{-- @endcan --}}
                        <div id="AdvanceFiletrShow" class="mb-4 ml-3 justify-content-start">
                            <div class="row mb-3">                               
                                <div class="col-md-3">
                                    <div className="form-group">
                                        <label>Date Range</label>
                                        <input type="text" class="form-control" name="date_range" id="order-date-range"  />
                                        <input type="hidden" class="form-control" id="start_date" name="start_date" />
                                        <input type="hidden" class="form-control" id="end_date" name="end_date"  />     
                                    </div>
                                </div>          
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="admin_activity_datatable"
                                class="table ui-datatable table-striped table-bordered nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Admin Name</th>
                                        <th>Admin Email</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Date Time</th>
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
    <!-- End modal -->
    <!-- container fluid End -->
@endsection

@section('script')
    <script>
        var admin_activity_url = "{{ url('/donotezzycaretouch/admin_activity') }}";
    </script>
    <script src="{{ asset('js/admin/adminActivity.js') }}"></script>
@endsection
