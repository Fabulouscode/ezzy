@extends('layouts.backend')

@if(!empty($data->id))
    @section('title','Edit Support Request')
@else
    @section('title','Add Support Request')
@endif

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/support_request')}}">Support Request</a></li>
                    <li class="breadcrumb-item active">{{ !empty($data->id) ? 'Edit' : 'Add' }} Support Request</li>
                </ol>
            </div>
            <h5 class="page-title">{{ !empty($data->id) ? 'Edit' : 'Add' }} Support Request</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" action="{{ url('support_request') }}" id="support_request_form" name="user_form">
                        @csrf
                        <input id="support_id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <input id="id" type="hidden" name="user_id" value="{{ !empty($data->user_id) ? $data->user_id : '' }}">
                       
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Subject</label>
                                <input readonly type="text"  class="form-control" name="title" value="{{ !empty($data->title) ? $data->title : old('title') }}" />
                            </div>
                            <div class="form-group col-md-6">
                                <label>Status</label>
                                <select  class="form-control @error('status') is-invalid @enderror" name="status" >
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
                                <textarea readonly rows="2" class="form-control" name="description">{{$data->description}}</textarea>
                            </div>
                        </div>      
                        
                        <div class="row">
                            <dt class="col-sm-5"><label>Attachment File</label></dt>
                            <dd class="col-sm-7"> 
                                <img src="{{$data->attachment}}" style="max-width: 100%;height:100px;display:block;">
                                <a href="{{$data->attachment}}" download>
                                    Click Here to Download
                                </a>
                            </dd>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{!empty($data->id) ? 'Update' : 'Submit' }}
                                </button>
                                <a href="{{ url('support_request') }}">
                                    <button type="button" class="btn btn-secondary waves-effect m-l-5">
                                        Cancel
                                    </button>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($data->chatSupport) && count($data->chatSupport) > 0)
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="text-center">Support Chat</h4>
                    <div class="order-chat-section">
                        <div class="order-chat-header">
                            <div class="order-store-detail">
                                <div class="order-store-img bg-dark-shop">
                                    @if(!empty($data->userDetails))
                                    <img src="{{$data->userDetails->profile_image}}" alt="">
                                    @endif
                                </div>
                                <div class="order-store-text-head">
                                    @if(!empty($data->userDetails))
                                    <h3>{{$data->userDetails->user_name}}</h3>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="chat_window" class="mCSB_container"></div> 
                        @if(isset($data->status) && ($data->status != '3'))
                        <div class="order-chat-footer">
                            <div class="order-chat-enter-area">                                    
                                <textarea id="reply_message" row="2" name="message" placeholder="Type a message" class="form-control"></textarea>
                                <div class="chat-actions-button">
                                    <button type="button" class="chat-msg-send-btn" onclick="sendReply()"><i class="fa fa-paper-plane-o"></i></button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    @endif
</div>
@endsection

@section('script')
<script>
    var support_request_url = "{{url('/support_request')}}";
</script>
<script src="{{ asset('js/admin/support_request.js') }}" ></script>
<script>
    $(document).ready(function() {
        getChatMessage();
    });
var timerID = setInterval(function() {
                getChatMessage();
            }, 60 * 1000);
    // clearInterval(timerID);
</script>
@endsection