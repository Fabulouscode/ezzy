@extends('layouts.backend')

@if(!empty($data->id))
    @section('title','Edit Category Details')
@else
    @section('title','Add Category Details')
@endif

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch/category')}}">Categories</a></li>
                    <li class="breadcrumb-item active">{{!empty($data->id) ? 'Edit' : 'Add' }}</li>
                </ol>
            </div>
            <h5 class="page-title">{{!empty($data->id) ? 'Edit' : 'Add' }} Category Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" action="{{ url('donotezzycaretouch/category') }}" id="category_form" name="category_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Category Name</label>
                                <input id="name" type="text" required class="form-control @error('name') is-invalid @enderror" name="name" value="{{!empty($data->name) ? $data->name : old('name') }}" autocomplete="name" autofocus/>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Parent Category</label>
                                <select id="parent_id"  type="text" required class="form-control @error('parent_id') is-invalid @enderror" name="parent_id" >
                                    <option value="">Select Parent Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}"  {{ !empty($data->parent_id) && $category->id == $data->parent_id ? 'selected' : '' }}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @error('parent_id')
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
                                <a href="{{ url('category') }}">
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
    var category_url = "{{url('/donotezzycaretouch/category')}}";
</script>
<script src="{{ asset('js/admin/category.js') }}" ></script>
@endsection