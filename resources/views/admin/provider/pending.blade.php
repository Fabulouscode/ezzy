@extends('layouts.backend')

@if($provider != 'patients')
    @section('title',array_key_exists($provider, $provider_names) ? 'Pending '.$provider_names[$provider]: 'Pending ')
@else
    @section('title',array_key_exists($provider, $provider_names) ? $provider_names[$provider]:'')
@endif

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/'.$provider .'/dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">
                        @if($provider != 'patients')
                            {{array_key_exists($provider, $provider_names) ? 'Pending '.$provider_names[$provider]: 'Approved '}}
                        @else
                            {{array_key_exists($provider, $provider_names) ? $provider_names[$provider]: ''}}
                        @endif
                    </li>
                </ol>
            </div>
            @if($provider != 'patients')
                <h5 class="page-title">{{array_key_exists($provider, $provider_names) ? 'Pending '.$provider_names[$provider]: 'Approved '}}</h5>
            @else
                <h5 class="page-title">{{array_key_exists($provider, $provider_names) ? $provider_names[$provider]: ''}}</h5>
            @endif
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                
                    <!-- <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{url('/user/create')}}" class="btn btn-info">Add User</a>
                    </div> -->

                    <div class="table-responsive">
                        <table id="user_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Mobile No.</th>
                                    <th>HCP Type</th>
                                    <th>Date of Joining</th>
                                    <th>Rating</th>
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
    var user_url = "{{url('/user')}}";
    var data_obj = {};
    if('{{$provider}}' == 'healthcare'){
        data_obj = {'status': '1', 'category_id':'1', 'provider': 'healthcare'};
    }else if('{{$provider}}' == 'pharmacy'){
        data_obj = {'status': '1', 'category_id':'2', 'provider':'pharmacy'};
    }else if('{{$provider}}' == 'laboratories'){
        data_obj = {'status': '1', 'category_id':'3', 'provider':'laboratories'};
    }else{            
        data_obj = {'status': '1', 'category_id':'', 'provider':'patients'};
    }
</script>
<script src="{{ asset('js/admin/provider.js') }}" ></script>
@endsection