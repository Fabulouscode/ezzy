@extends('layouts.backend')

@section('title','Notification')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Notification</li>
                </ol>
            </div>
            <h5 class="page-title">Manage Notification</h5>
        </div>
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body"> 
                    @can('notification-add')
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{url('/notifications/create')}}" class="btn btn-info">Add Notification</a>
                    </div>          
                    @endcan                                      
                    <div class="table-responsive">
                        <table id="notification_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Title</th>
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
    var notification_url = "{{url('/notifications')}}";
</script>
<script src="{{ asset('js/admin/notification.js') }}" ></script>
@endsection