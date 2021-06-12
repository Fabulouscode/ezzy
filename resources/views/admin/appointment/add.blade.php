@extends('layouts.backend')

@section('title','Add Appointment Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Add Appointment Details</li>
                </ol>
            </div>
            <h5 class="page-title">Add Appointment Details</h5>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var category_url = "{{url('/donotezzycaretouch/category')}}";
</script>
<script src="{{ asset('js/admin/category.js') }}" ></script>
@endsection