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
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch/'.$provider .'/dashboard')}}">Dashboard</a></li>
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
                        <a href="{{url('/donotezzycaretouch/user/create')}}" class="btn btn-info">Add User</a>
                    </div> -->
                    <!-- Custom Filter -->
                    @if (!@empty($provider) && $provider == 'healthcare')    
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="exportPendingHCPExcel()" class="btn d-flex align-items-center btn-info">Export <span id="ajax_loader" class="ml-2"></span> </a>
                    </div>
                    @elseif (!@empty($provider) && $provider == 'pharmacy')
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="exportPendingPharmacistExcel()" class="btn d-flex align-items-center btn-info">Export <span id="ajax_loader" class="ml-2"></span></a>
                    </div>
                    @elseif (!@empty($provider) && $provider == 'laboratories')
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="exportPendingLaboratoriesExcel()" class="btn d-flex align-items-center btn-info">Export
                            <span id="ajax_loader" class="ml-2"></span></a>
                    </div>
                    @endif
                    
                    <div id="AdvanceFiletrShow" class="mb-4 ml-3 justify-content-start">
                        <label>Advanced Filter</label>
                        <div class="row">  
                            @if(!empty($provider) && $provider == 'healthcare')                        
                                <div class="col-md-3 mb-3">
                                    <div className="form-group">
                                        <label>HCP Type</label>
                                        <select id="searchByHcpType" name="subcategory_id" class="form-control">
                                            <option value=''>Select Hcp Type</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>                                
                                    </div>
                                </div>
                            @elseif(!empty($provider) && $provider == 'laboratories')
                                <div class="col-md-3 mb-3">
                                    <div className="form-group">
                                        <label>HCP Type</label>
                                        <select id="searchByHcpType" name="subcategory_id" class="form-control">
                                            <option value=''>Select Hcp Type</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        </select>                                
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3 mb-3">
                                <div className="form-group">
                                    <label>Date Range</label>
                                    <input type="text" class="form-control" name="date_range" id="user-date-range"  />
                                    <input type="hidden" class="form-control" id="user_start_date" name="start_date" />
                                    <input type="hidden" class="form-control" id="user_end_date" name="end_date"  />     
                                </div>
                            </div>    
                            <div class="col-md-3 mb-3">
                                <div className="form-group">
                                    <label>Profile Completed Percentage</label>
                                    <select id="searchByHcpTypeProgress" name="completed_percentage" class="form-control">
                                        <option value=''>Select Profile Completed Percentage</option>
                                        <option value="100">100 %</option>
                                        <option value="90">90 %</option>
                                        <option value="80">80 %</option>
                                        <option value="70">70 %</option>
                                        <option value="60">60 %</option>
                                        <option value="50">50 %</option>
                                    </select>                                
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label>Country</label>
                                    <select id="searchByCountry" name="country_id" class="form-control searchByCountry" required>
                                        <option value="">Select Country</option>
                                        @if(count($country))
                                            @foreach($country as $c)
                                            <option value="{{ $c->id }}">{{ $c->country_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label>Address</label>
                                    <select id="searchByAddress" name="address" class="form-control" required>
                                        <option value="">Select Address</option>
                                        @if(count($data))
                                                    @foreach($data as $add)
                                                    @if ($add->id && $add->address)
                                                        
                                                    <option value="{{ $add->id }}">{{ $add->address }}</option>
                                                    @endif
                                                    @endforeach
                                                @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label>City</label>
                                    <select id="searchByCity" name="city_id" class="form-control" required>
                                        <option value="">Select City</option>
                                        @if(count($uniqueCities))
                                            @foreach($uniqueCities as $city)
                                            @if ($city)
                                            <option value="{{ $city }}">{{ $city }}</option>    
                                            @endif
                                            
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    

                    <div class="table-responsive">
                        <table id="user_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Mobile No.</th>
                                    <th>Wallet Amount</th>
                                    <th>HCP Type</th>
                                    <th>Date of Joining</th>
                                    <th>Licence Expiry Date</th>
                                    <th>Rating</th>
                                    <th>Profile (%)</th>
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
    var user_url = "{{url('/donotezzycaretouch/user')}}";
    var pending_hcp_url = "{{url('/donotezzycaretouch/healthcare/user/pending')}}";
    var pending_pharma_url = "{{url('/donotezzycaretouch/pharmacy/user/pending')}}";
    var pending_lab_url = "{{url('/donotezzycaretouch/pharmacy/user/pending')}}";
    var data_obj = {};
    var data_status = '';
    var data_category_id = '';
    var data_provider = '';
    if('{{$provider}}' == 'healthcare'){
        data_status = '1';
        data_category_id = '1';
        data_provider = 'healthcare';
        data_obj = {'status': '1', 'category_id':'1', 'provider': 'healthcare'};
    }else if('{{$provider}}' == 'pharmacy'){
        data_status = '1';
        data_category_id = '2';
        data_provider = 'pharmacy';
        data_obj = {'status': '1', 'category_id':'2', 'provider':'pharmacy'};
    }else if('{{$provider}}' == 'laboratories'){
        data_status = '1';
        data_category_id = '3';
        data_provider = 'laboratories';
        data_obj = {'status': '1', 'category_id':'3', 'provider':'laboratories'};
    }else{          
        data_status = '1';
        data_category_id = '';
        data_provider = 'patients';  
        data_obj = {'status': '1', 'category_id':'', 'provider':'patients'};
    }
</script>
<script src="{{ asset('js/admin/provider.js') }}" ></script>
@endsection