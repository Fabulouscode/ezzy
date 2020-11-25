@extends('layouts.backend')

@if(!empty($data->id))
    @section('title','Edit Static Page Details')
@else
    @section('title','Add Static Page Details')
@endif

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/static_pages')}}">Static Page</a></li>
                    <li class="breadcrumb-item active">{{!empty($data->id) ? 'Edit' : 'Add' }}</li>
                </ol>
            </div>
            <h5 class="page-title">{{!empty($data->id) ? 'Edit' : 'Add' }} Static Page Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" action="{{ url('static_pages') }}" id="static_page_form" name="static_page_form">
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Page Name</label>
                                <input type="text" required placeholder="Page Name" class="form-control @error('page_name') is-invalid @enderror" name="page_name" value="{{!empty($data->page_name) ? $data->page_name : old('page_name') }}" autocomplete="page_name" autofocus>
                                @error('page_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                             <div class="form-group col-md-6">
                                <label>Status</label>
                                <select  type="text" class="form-control @error('status') is-invalid @enderror" name="status" >
                                    @foreach($status as $key => $value)
                                        <option value="{{$key}}" {{ isset($data->status) && $key == $data->status ? 'selected' : '' }}>{{$value}}</option>
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
                                <textarea class="summernote" name="page_description">{{!empty($data->page_description) ? $data->page_description : old('page_description') }}</textarea>
                            </div>
                        </div>


                        
                        <div class="row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{ url('static_pages') }}">
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
    var static_pages_url = "{{url('/static_pages')}}";
</script>
<script src="{{ asset('js/admin/static_page.js') }}" ></script>
@endsection