@extends('layouts.backend')

@section('title','User Add')

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

                    <h4 class="mt-0 header-title">{{!empty($data->id) ? 'Edit' : 'Add' }} User</h4>
       
                    <form method="POST" action="{{ url('user') }}" id="user_form" name="user_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Category</label>
                                <select id="category_id"  type="text" class="form-control @error('category_id') form-control-danger @enderror" name="category_id" >
                                    <option value="">Select Parent Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}"  {{ !empty($data->category_id) && $category->id == $data->category_id ? 'selected' : '' }}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group  col-md-6">
                                <label>Subcategory</label>
                                <select id="subcategory_id"  type="text" class="form-control @error('subcategory_id') form-control-danger @enderror" name="subcategory_id" >
                                    <option value="">Select Parent Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}"  {{ !empty($data->subcategory_id) && $category->id == $data->subcategory_id ? 'selected' : '' }}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @error('subcategory_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>First Name</label>
                                <input id="first_name" type="text" required class="form-control @error('first_name') form-control-danger @enderror" name="first_name" value="{{!empty($data->first_name) ? $data->first_name : old('first_name') }}" autocomplete="first_name" autofocus/>
                                @error('first_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Last Name</label>
                                <input id="last_name" type="text" required class="form-control @error('last_name') form-control-danger @enderror" name="last_name" value="{{!empty($data->last_name) ? $data->last_name : old('last_name') }}" />
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Eazzy Card</label>
                                <input id="eazzycare_card" type="text" required class="form-control @error('eazzycare_card') form-control-danger @enderror" name="eazzycare_card" value="{{!empty($data->eazzycare_card) ? $data->eazzycare_card : old('eazzycare_card') }}" />
                                @error('eazzycare_card')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>First Name</label>
                                <input id="email" type="email" required class="form-control @error('email') form-control-danger @enderror" name="email" value="{{!empty($data->email) ? $data->email : old('email') }}" autocomplete="email" autofocus/>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Last Name</label>
                                <input id="mobile_no" type="text" required class="form-control @error('mobile_no') form-control-danger @enderror" name="mobile_no" value="{{!empty($data->mobile_no) ? $data->mobile_no : old('mobile_no') }}" />
                                @error('mobile_no')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label>Gender</label>
                                <div class="custom-control custom-radio">
                                    <input id="gender" type="radio" class="@error('gender') form-control-danger @enderror" name="gender" value="0" {{isset($data->gender) && $data->gender == '0' ? 'checked' : '' }} /> <label class="mr-5">Male</labele>
                                    <input id="gender" type="radio" class="@error('gender') form-control-danger @enderror" name="gender" value="1" {{isset($data->gender) && $data->gender == '1' ? 'checked' : '' }} /> <label class="mr-5">Female</labele>
                                </div>
                                @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
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
    var user_url = "{{url('/user')}}";
</script>
<script src="{{ asset('js/admin/user.js') }}" ></script>
@endsection