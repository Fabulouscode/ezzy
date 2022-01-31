@if(!empty($support_chat) && count($support_chat) > 0)
<div class="order-chat-content chat-scrollbar">
	@foreach($support_chat as $chat_msg)
		@if(!empty($chat_msg->user_id) && empty($chat_msg->admin_id))
		<div class="order-chat-list-sec left-chat">
			<div class="order-chat-area">
                {!! $chat_msg->message !!}                
            </div>
            <div class="msg-time">{{Helper::getDateTimeFormate($chat_msg->created_at)}}</div>
		</div>
		@endif
		@if(empty($chat_msg->user_id) && !empty($chat_msg->admin_id))
		<div class="order-chat-list-sec right-chat">
			<div class="order-chat-area">
				{!! $chat_msg->message !!}   
				<div class="chat-actions">
					<button onclick="editChatMessage({{$chat_msg->id}})" class="chat-edit-btn" title="Edit Message"><i class="dripicons-pencil"></i></button>
					<button onclick="deleteChatMessage({{$chat_msg->id}})" class="chat-delete-btn" title="Delete Message"><i class="dripicons-trash"></i></button>
				</div>      	    
             </div>
             <div class="msg-time">{{Helper::getDateTimeFormate($chat_msg->created_at)}}</div>			 
		</div>
		@endif
	@endforeach
</div>
@endif

