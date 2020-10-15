@extends('layouts.backend')

@section('title','Category Add')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Drixo</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
            <h5 class="page-title">Dashboard</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">{{!empty($data->id) ? 'Edit' : 'Add' }} Health Care Provider Type</h4>
       
                    <form method="POST" action="{{ url('category') }}" id="category_form" name="category_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input id="name" type="text" required class="form-control @error('name') is-invalid @enderror" name="name" value="{{!empty($data->name) ? $data->name : old('name') }}" autocomplete="name" autofocus/>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Parent Category</label>
                            <select id="parent_id"  type="text" class="form-control @error('parent_id') is-invalid @enderror" name="parent_id" >
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

                       
                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{ url('permission') }}">
                                    <button type="reset" class="btn btn-secondary waves-effect m-l-5">
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
    var category_url = "{{url('/category')}}";
</script>
<script src="{{ asset('js/admin/category.js') }}" ></script>
@endsection