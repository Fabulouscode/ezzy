@extends('layouts.backend')

@section('title','Static Pages')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Static Pages</li>
                </ol>
            </div>
            <h5 class="page-title">Static Pages</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    @can('static_page-add')
                    <div class="block-options-item mb-3 ml-3">
                        <a href="{{url('/static_pages/create')}}" class="btn btn-info">Add Static Page</a>
                    </div>
                    @endcan

                    <div class="table-responsive">
                        <table id="static_pages_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Page Name</th>
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
    var static_pages_url = "{{url('/static_pages')}}";
</script>
<script src="{{ asset('js/admin/static_page.js') }}" ></script>
@endsection