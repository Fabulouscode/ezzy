@extends('layouts.backend')

@section('title','View Support Request')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch/support_request')}}">Support Request</a></li>
                    <li class="breadcrumb-item active">View Support Request</li>
                </ol>
            </div>
            <h5 class="page-title">View Support Request</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
       
                    <form method="POST" id="support_request_form" name="support_request_form">
                        @csrf
                        <input id="support_id" type="hidden" name="id" value="{{ !empty($data->id) ? $data->id : '' }}">
                        <div class="border border-light rounded mb-3">
                            <div class="card-detail-view">
                                <h4 class="mt-0 mb-0 header-title">Support Details</h4>
                                <div class="card-detail-list">
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Subject</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->title))
                                                {{$data->title}}
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Status</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(isset($data->status))
                                                {{$data->status_name}}
                                            @endif 
                                        </dd>
                                    </div>
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Description</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->description))
                                                {{$data->description}}
                                            @endif 
                                        </dd>
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
                                    @if($data->status == '3')
                                    <div class="row">
                                        <dt class="col-sm-5"><label>Close Date</label></dt>
                                        <dd class="col-sm-7"> 
                                            @if(!empty($data->closed_date))
                                                {{Helper::getDateTimeFormate($data->closed_date)}}
                                            @endif 
                                        </dd>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>              
                        

                        <div class="row">
                            <div class="form-group col-md-12">
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
        </div> <!-- end col -->
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
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    @endif
</div>
<!-- Add modal -->
<div class="modal fade bs-editSupportChatMsg" id="editSupportChatMsg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Chat Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="support_chat_msg_form" name="support_chat_msg_form">
                    @csrf
                    <input id="edit_chat_id" type="hidden" name="id" >
                                
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label id="fees_percentage_label">Message</label>
                            <textarea required rows="5" placeholder="Chat Message" class="form-control" id="edit_chat_message" name="message"> 
                            </textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="form-group col-md-12">
                            <button type="submit" id="submit_btn" class="btn btn-primary waves-effect waves-light">
                               Update
                            </button>
                            <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-secondary waves-effect m-l-5">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End modal -->
@endsection

@section('script')
<script>
    var support_request_url = "{{url('/donotezzycaretouch/support_request')}}";
</script>
<script src="{{ asset('js/admin/support_request.js') }}" ></script>
<script>
    $(document).ready(function() {
        getChatMessage();
    });
</script>
@endsection