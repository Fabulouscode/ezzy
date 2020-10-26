@extends('layouts.backend')

@section('title','Add Medicine Details')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
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
       
                    <form method="POST" action="{{ url('medicine/details') }}" id="medicine_details_form" name="medicine_details_form"  enctype="multipart/form-data" >
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
                                <label>Subcategory</label>
                                <select id="medicine_subcategoy_id" required  type="text" class="form-control @error('medicine_subcategoy_id') is-invalid @enderror" name="medicine_subcategoy_id" >
                                    <option value="">Select Subcategory</option>
                                    @foreach($subcategories as $subcategory)
                                        <option value="{{$subcategory->id}}" data-cat_id = "{{$subcategory->medicine_category_id}}" {{ !empty($data->medicine_subcategoy_id) && $subcategory->id == $data->medicine_subcategoy_id ? 'selected' : '' }}>{{$subcategory->name}}</option>
                                    @endforeach
                                </select>
                                @error('medicine_subcategoy_id')
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

                        <div class="form-group">
                            <div>
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
    var medicine_details_url = "{{url('/medicine/details')}}";
    var file_upload_url = "{{url('/image/upload')}}";
    var file_remove_url = "{{url('/image/remove')}}";
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
    Dropzone.options.imageDropzone = {
        url: file_upload_url,
        acceptedFiles: ".png, .jpeg, .jpg, .gif",
        maxFilesize: 2, // MB
        maxFiles: 5,
        addRemoveLinks: true,
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        success: function (file, response) {
            $('form').append('<input type="hidden" class="medicine_dropzone" name="medicine_images[]" value="' + response.name + '">');
            uploadedImageMap[file.name] = response.name;
        },
        removedfile: function (file) {
            file.previewElement.remove();
            var name = '';
            console.log(uploadedImageMap);
            if (typeof file.file_name !== 'undefined') {
                name = file.file_name;
            } else {
                name = uploadedImageMap[file.name];
            }
            $('form').find('input[name="medicine_images[]"][value="' + name + '"]').remove();
              $.ajax({
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    url: file_remove_url,
                    type: "post",
                    dataType: 'json',
                    data:{'file_name':name},
                    success: function (data) {
                        toastr.success(data.msg, 'EzzyCare App');
                    },
                    error: function (error) {
                        console.log(error);
                        toastr.error(error.msg, 'EzzyCare App');
                    }
                });
        },
        init: function () {
            if(medicine_images.length > 0){
                for (var i in medicine_images) {
                    var image_name = medicine_images[i]['product_image'].substring(medicine_images[i]['product_image'].lastIndexOf('/') + 1);
                    var file = storage_url+'/'+medicine_images[i]['product_image'];
                    var mockFile = { name:  image_name, size: '1234'};
                    this.emit("addedfile", mockFile);
                    this.emit("thumbnail", mockFile, file);
                    this.emit("complete", mockFile);
                    uploadedImageMap[image_name] = medicine_images[i]['product_image'];
                    $('form').append('<input type="hidden" class="medicine_dropzone" name="medicine_images[]" value="' + medicine_images[i]['product_image'] + '">');
                }
                
            }
            
            this.on("sending", function(file, xhr, formData){
                    formData.append("folder_name", "medicine_images");
            });
        }
    }
</script>
<script src="{{ asset('js/admin/medicine_details.js') }}" ></script>
@endsection