@extends('layouts.backend')

@section('title', 'Care Seeker Details')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="float-right page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/donotezzycaretouch') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Care Seeker Details</li>
                    </ol>
                </div>
                <h5 class="page-title">Care Seeker Details</h5>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div className="form-group">
                                    <label>Date Range</label>
                                    <input type="text" class="form-control" name="date_range" id="user-date-range"
                                        autocomplete="off" />
                                    <input type="hidden" class="form-control" id="user_start_date" name="start_date" />
                                    <input type="hidden" class="form-control" id="user_end_date" name="end_date" />
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="patient_detail_datatable"
                                class="table ui-datatable table-striped table-bordered nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                    <tr>
                                        <th>Id</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Mobile No.</th>
                                        <th>Date of Joining</th>
                                        <th>Total Appointments</th>
                                        <th>Total Orders</th>
                                    </tr>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        let patient_detail_url = "{{ url('/donotezzycaretouch/patient-details') }}";
        let data_status = ['0', '2'];
        let data_category_id = '';
        let data_obj = {
            'status': ['0', '2'],
            'category_id': ''
        };
    </script>
    <script src="{{ asset('js/admin/patient_detail.js') }}"></script>
@endsection
