<!-- @if(!empty($support_chat) && count($support_chat) > 0)
<div class="row justify-content-md-center">
    <div class="col-md-6" id="chat_scroll_bottom" style="height:500px; overflow-x:auto">
        <ul class="list-unstyled">
        @foreach($support_chat as $chat_msg)
            @if(!empty($chat_msg->user_id) && empty($chat_msg->admin_id))
            <li class="row justify-content-start">
                <div class="p-3 mb-2 bg-light text-dark col-md-11 rounded-right border border-primary">
                    <div class="row justify-content-md-left">
                        <div class="text-right nav-user">
                            <img class="rounded-circle" style="width:50px;height:50px;" src="{{ $chat_msg->user->profile_image }}">
                        </div> 
                        <div class="text-left">
                            <p class="text-left">{{ $chat_msg->message }}</p>
                            <p class="text-left"><small>{{ date('m/d/Y H:m:s',strtotime($chat_msg->created_at)) }}</small></p>
                        </div>
                    </div>
                </div>
            </li>
            @endif
            @if(empty($chat_msg->user_id) && !empty($chat_msg->admin_id))
            <li class="row justify-content-end">
                <div class="p-3 pl-4 mb-2 bg-light text-dark col-md-11 rounded-left border border-primary">
                    <div class="row justify-content-md-right">
                        <div class="text-right">
                            <p class="text-left">{{ $chat_msg->message }}</p>
                            <p class="text-left"><small>{{ date('m/d/Y H:m:s',strtotime($chat_msg->created_at)) }}</small></p>
                        </div>
                        <div class="text-left nav-user">
                            <img class="rounded-circle" style="width:50px;height:50px;"  src="{{ asset('admin/images/avatar.jpg') }}">
                        </div>
                    </div>
                </div>
            </li>
            @endif
        @endforeach
        </ul>
    </div>
</div>
@endif -->

@if(!empty($support_chat) && count($support_chat) > 0)
<div class="order-chat-content chat-scrollbar">
	@foreach($support_chat as $chat_msg)
		@if(!empty($chat_msg->user_id) && empty($chat_msg->admin_id))
		<div class="order-chat-list-sec left-chat">
			<div class="order-chat-area">
                {!! $chat_msg->message !!}                
            </div>
            <div class="msg-time">{{ date('m-d-Y h:i:s a',strtotime($chat_msg->created_at)) }}</div>
		</div>
		@endif
		@if(empty($chat_msg->user_id) && !empty($chat_msg->admin_id))
		<div class="order-chat-list-sec right-chat">
			<div class="order-chat-area">
				{!! $chat_msg->message !!}         	    
             </div>
             <div class="msg-time">{{ date('m-d-Y h:i:s a',strtotime($chat_msg->created_at)) }}</div>
		</div>
		@endif
	@endforeach
</div>
@endif

