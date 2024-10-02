@extends('layouts.backend')

@section('title','Add Medicine Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Add Medicine Details</li>
                </ol>
            </div>
            <h5 class="page-title">Add Medicine Details</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">

                    <h4 class="mt-0 header-title">{{!empty($data->id) ? 'Edit' : 'Add' }} Medicine Details</h4>
       
                    <form method="POST" action="{{ url('donotezzycaretouch/medicine/details') }}" id="medicine_details_form" name="medicine_details_form"  enctype="multipart/form-data" >
                        @csrf
                        <input id="id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Category</label>
                                <select id="medicine_category_id" required  type="text" class="form-control @error('medicine_category_id') is-invalid @enderror" name="medicine_category_id" >
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}"  {{ !empty($data->medicine_category_id) && $category->id == $data->medicine_category_id ? 'selected' : '' }}>{{$category->name}}</option>
                                    @endforeach
                                </select>
                                @error('medicine_category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>                                                 
                            <div class="form-group col-md-6">
                                <label>Medicine Types</label>
                                <select required class="form-control @error('medicine_type') is-invalid @enderror" name="medicine_type" >
                                    <option value="">Select Medicine Types</option>
                                    @foreach($medicine_types as $key => $value)
                                        <option value="{{$key}}"  {{ isset($data->medicine_type) && $key == $data->medicine_type ? 'selected' : '' }}>{{$value}}</option>
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
                            <div class="form-group col-md-6">
                                <label>Medicine Name</label>
                                <input id="medicine_name" type="text" required class="form-control @error('medicine_name') is-invalid @enderror" name="medicine_name" value="{{!empty($data->medicine_name) ? $data->medicine_name : old('medicine_name') }}" />
                                @error('medicine_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>                       
                            <div class="form-group col-md-6">
                                <label>Medicine SKU</label>
                                <input id="medicine_sku" type="text" required class="form-control @error('medicine_sku') is-invalid @enderror" name="medicine_sku" value="{{!empty($data->medicine_sku) ? $data->medicine_sku : old('medicine_sku') }}" />
                                @error('medicine_sku')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>       
                        </div>

                        <div class="row">
                             <div class="form-group col-md-6">
                                <label>Mrp Price</label>
                                <input id="mrp_price" type="text" required class="form-control @error('mrp_price') is-invalid @enderror" name="mrp_price" value="{{!empty($data->mrp_price) ? $data->mrp_price : old('mrp_price') }}" />
                                @error('mrp_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label>Quantity</label>
                                <input id="quantity" type="text" required class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{!empty($data->quantity) ? $data->quantity : old('quantity') }}" />
                                @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                             <div class="form-group col-md-6">
                                <label>Size / Dosage</label>
                                <input id="size_dosage" type="text" required class="form-control @error('size_dosage') is-invalid @enderror" name="size_dosage" value="{{!empty($data->size_dosage) ? $data->size_dosage : old('size_dosage') }}" />
                                @error('size_dosage')
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
                                <label>Description</label>
                                <textarea id="description" type="text" required class="form-control @error('description') is-invalid @enderror" name="description"  >{{!empty($data->description) ? $data->description : old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="image">Image Upload</label>
                                <div class="dropzone dz-clickable" id="image-dropzone">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{ url('medicine/details') }}">
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
    var medicine_details_url = "{{url('/donotezzycaretouch/medicine/details')}}";
    var file_upload_url = "{{url('/donotezzycaretouch/image/upload')}}";
    var file_remove_url = "{{url('/donotezzycaretouch/image/remove')}}";
    var storage_url = "{{url('/storage')}}";
    var medicine_images = '';
    var medicine_subcategoy_id = '';
    @if(!empty($data->medicineImages))
        medicine_images = {!! json_encode($data->medicineImages) !!};
    @endif
    @if(!empty($data->medicine_subcategoy_id))
        medicine_subcategoy_id = {{$data->medicine_subcategoy_id}};
    @endif

    var uploadedImageMap = {};

</script>
<script src="{{ asset('js/admin/medicine_details.js') }}" ></script>
@endsection