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
                    <div id="AdvanceFiletrShow" class="mb-4 ml-3 justify-content-start">
                        <label>Advanced Filter</label>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div className="form-group">
                                    <label>Hcp Type</label>
                                    <select id="searchByHcpType" name="category_id" class="form-control">
                                        <option value=''>Select Hcp Type</option>
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div className="form-group">
                                    <label>Appointment Type</label>
                                    <select id="searchByAppointmentType" name="appointment_type" class="form-control">
                                        <option value=''>Select Appointment Type</option>
                                        <option value='0'>Clinic</option>
                                        <option value='1'>Home</option>
                                        <option value='2'>Video</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="col-md-3 mb-3">
                                <div className="form-group">
                                    <label>Appointment Urgent</label>
                                    <select id="searchByAppointmentUrgent" name="urgent" class="form-control">
                                        <option value=''>Select Appointment Urgent</option>
                                        <option value='0'>Not Urgent</option>
                                        <option value='1'>Urgent</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div className="form-group">
                                    <label>Appointment Created Date </label>
                                    <input type="text" class="form-control" name="date_range" id="appointment-date-range" />
                                    <input type="hidden" class="form-control" id="appointment_start_date" name="start_date" />
                                    <input type="hidden" class="form-control" id="appointment_end_date" name="end_date" />
                                </div>
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