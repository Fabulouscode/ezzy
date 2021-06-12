@extends('layouts.backend')

@section('title','Add Medicine Category')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Add Medicine Category</li>
                </ol>
            </div>
            <h5 class="page-title">Add Medicine Category</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">{{!empty($data->id) ? 'Edit' : 'Add' }} Medicine Categories</h4>
       
                    <form method="POST" action="{{ url('donotezzycaretouch/medicine/categories') }}" id="medicine_category_form" name="medicine_category_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Category Name</label>
                                <input id="name" type="text" required class="form-control @error('name') is-invalid @enderror" name="name" value="{{!empty($data->name) ? $data->name : old('name') }}" autocomplete="name" autofocus/>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <select required class="form-control @error('status') is-invalid @enderror" name="status" >
                                    <option value="">Select Status</option>
                                    @foreach($status as $key => $value)
                                        <option value="{{$key}}"  {{ isset($data->status) && $key == $data->status ? 'selected' : '' }}>{{$value}}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{ url('/donotezzycaretouch/medicine/categories') }}">
                                    <button type="button" class="btn btn-secondary waves-effect m-l-5">
                                        Cancel
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div> <!-- end col -->
    </div>
</div>
@endsection

@section('script')
<script>
    var medicine_category_url = "{{url('/donotezzycaretouch/medicine/categories')}}";
</script>
<script src="{{ asset('js/admin/medicine_category.js') }}" ></script>
@endsection