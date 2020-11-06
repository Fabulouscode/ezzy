@extends('layouts.backend')

@section('title','User List')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Drixo</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
            <h5 class="page-title">User Review List</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <!-- <div class="block-options-item mb-3 ml-3">
                        <a href="{{url('/user/create')}}" class="btn btn-info">Add User</a>
                    </div> -->

                    <table id="user_review_datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Email</th>
                                <th>Mobile No.</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Review Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>                        
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
<input type="hidden" class="rating" data-filled="mdi mdi-star font-32 text-primary" data-empty="mdi mdi-star-outline font-32 text-muted" data-readonly value = "2.5" />
@endsection

@section('script')
<script>
    var user_review_url = "{{url('/user/review')}}";
</script>
<script src="{{ asset('js/admin/user_review.js') }}" ></script>
@endsection