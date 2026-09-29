@extends('layouts.master')
@section('css') 

@endsection
@section('content')
<?php 
	use App\Models\User;

	$user_type = User::where('id',auth()->id())->pluck('user_type')->first();
	$login_user_id = auth()->id();
// dd($user_type);
?>
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<div class="chat-wrapper">
			<div class="chat-sidebar">
				<div class="chat-sidebar-header">
					<div class="d-flex align-items-center">
						<!-- <div class="chat-user-online">
							<img src="{{ URL::asset('assets/images/avatars/avatar-1.png')}}" width="45" height="45" class="rounded-circle" alt="" />
						</div> -->
						<div class="flex-grow-1 ms-2">
							<p class="mb-0">{{$title}} List</p>
						</div>
						<!-- <div class="dropdown">
							<div class="cursor-pointer font-24 dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown"><i class='bx bx-dots-horizontal-rounded'></i>
							</div>
							<div class="dropdown-menu dropdown-menu-end"> <a class="dropdown-item" href="javascript:;">Settings</a>
								<div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Help & Feedback</a>
								<a class="dropdown-item" href="javascript:;">Enable Split View Mode</a>
								<a class="dropdown-item" href="javascript:;">Keyboard Shortcuts</a>
								<div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Sign Out</a>
							</div>
						</div> -->
					</div>
					<div class="mb-3"></div>
					<!-- <div class="input-group input-group-sm"> <span class="input-group-text"><i class='bx bx-search'></i></span>
						<input type="text" class="form-control" placeholder="People, groups, & messages"> <span class="input-group-text"><i class='bx bx-dialpad'></i></span>
					</div> -->
				</div>
				<div class="chat-sidebar-content">
					<div class="tab-content" id="pills-tabContent">
						<div class="tab-pane fade show active" id="pills-Chats">
							<div class="chat-list">
								<div class="list-group list-group-flush showchatlist">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="chat-header d-flex align-items-center">
				<div class="chat-toggle-btn"><i class='bx bx-menu-alt-left'></i>
				</div>
				<div>
					<h4 class="mb-1 font-weight-bold">Chat Detail</h4>
				</div>
			</div>
			<div class="chat-content" id="messages">

			</div>

				<div style="display:none;" class="guest_chat_form_div">
					<form action="">
						<div class="chat-footer d-flex align-items-center">
							<div class="flex-grow-1 pe-2">
								<div class="input-group">	<span class="input-group-text"><i class='bx bx-smile'></i></span>
									<input type="text" id="text_msg" class="form-control" placeholder="Type a message">
								</div>
							</div>
							<div class="chat-footer-menu">
								<input type="hidden" name="chatId" id="chatId" value="">
								<input type="hidden" name="senderId" id="senderId" value="">
								<input type="hidden" name="receiverId" id="receiverId" value="">
								<button type="submit"><i class='bx bxs-send'></i></button>
								<!-- <a href="javascript:;"><i class='bx bx-file'></i></a>
								<a href="javascript:;"><i class='bx bx-microphone'></i></a>
								<a href="javascript:;"><i class='bx bx-dots-horizontal-rounded'></i></a> -->
							</div>
						</div>
					</form>
				</div>
				@if($user_type == 1)
			
			@endif
			<!--start chat overlay-->
			<div class="overlay chat-toggle-btn-mobile"></div>
			<!--end chat overlay-->
		</div>
	</div>
</div>
<!--end page wrapper -->
	<!--start overlay-->
	<div class="overlay toggle-icon"></div>
	<!--end overlay-->
	<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
	<!--End Back To Top Button-->
</div>
<!--end wrapper-->
<script src="{{ URL::asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"
        integrity="sha512-v8ng/uGxkge3d1IJuEo6dJP8JViyvms0cly9pnbfRxT6/31c3dRWxIiwGnMSWwZjHKOuY3EVmijs7k1jz/9bLA=="
        crossorigin="anonymous"></script>
	
<script>
	new PerfectScrollbar('.chat-list');
	new PerfectScrollbar('.chat-content');
</script>
<script>

function chatList(){
			$.ajax({
					url:'{{url("admin/guest_list")}}/',
					dataType: 'html',
					success:function(result)
					{
						$('.showchatlist').html(result);
						
					}
				});
			}
			chatList();


    const socketUrl = "https://emit.ae";
    var socket = io(socketUrl);

    $(function () {
    	$("#messages").animate({
          scrollTop: $('#messages')[0].scrollHeight - $('#messages')[0].clientHeight
        }, 1000);
        var login_user_id = "{{$login_user_id}}";

        var sender_id = 2;
        var receiver_id = 3;
        var roomId = sender_id.toString() + receiver_id.toString();

        socket.on('connect', () => {
            console.log(socket.connected); // true
        });

        $('form').submit(function (e) {
            e.preventDefault(); // prevents page reloading
            var senderChatId = $('#chatId').val();
            var sender_id = $('#senderId').val();
	        var receiver_id = $('#receiverId').val();
	        var roomId = sender_id.toString() + receiver_id.toString();

            $('#messages').append('<div class="chat-content-rightside"><div class="d-flex"><div class="flex-grow-1 me-2"><p class="mb-0 chat-time text-end">you, 3:35 PM</p><p class="chat-right-msg">'+$('#text_msg').val()+'</p></div></div></div>');

            socket.emit('sendMessage', {
                type: 'text',
                message: $('#text_msg').val(),
                booking_id: 4,
                sender_id: sender_id,
                receiver_id: receiver_id,
                chatId: senderChatId
            });

            $("#messages").animate({
              scrollTop: $('#messages')[0].scrollHeight - $('#messages')[0].clientHeight
            }, 1000);

            $('#text_msg').val('');
			setTimeout(function() {chatList();}, 2000);
            return false;
        });

        // socket.emit('join', "1");

        /* socket.emit('chat message', {
                message: "Hey",
                sender_id: 8,
                receiver_id: 1,
            }); */

        socket.on('receivedMessage', function (msg) {
            console.log("receivedMessage", msg)
            //$("#room_id").val(msg.roomId);
            /*if (msg.receiver_id == "8") {
                $('#messages').append($('<li>').text(msg.data.last_message.message));
            }*/
            //$('#messages').append($('<li>').text(msg.data.last_message.message));

            // if (msg.receiver_id == login_user_id) {
            // }
            $('#messages').append('<div class="chat-content-leftside"><div class="d-flex"><img src="'+msg.last_message.sent_by_detail.image+'" width="48" height="48" class="rounded-circle" alt="" /><div class="flex-grow-1 ms-2"><p class="mb-0 chat-time">'+msg.last_message.sent_by_detail.name+', 3:35 PM</p><p class="chat-left-msg">'+msg.last_message.message+'</p></div></div></div>');

            $("#messages").animate({
	            scrollTop: $('#messages')[0].scrollHeight - $('#messages')[0].clientHeight
	        }, 1000);
        });

        socket.on('sendedMessageDetail', function (msg) {
            console.log("sendedMessageDetail", msg)
        });

        socket.on('acknowledge', function (msg) {
            console.log("acknowledge", msg)
        });
    });
</script>

<script type="text/javascript">
	function changeChatRoom($this) {
		$('.guest_chat_form_div').css('display','block');
		var chatId = $($this).attr('data-chatId');
		var login_user_id = 1	;
		var senderId = login_user_id;
		var receiverId = $($this).attr('data-receiverId');

		var roomId = senderId.toString() + receiverId.toString();
		console.log(roomId+'---------------------roomId---------------');
		socket.emit('join', roomId);

		if (login_user_id == receiverId) {
			receiverId = $($this).attr('data-senderId');
		}

		$('.chat_list_data').removeClass('active');

		if (chatId) {
			$('.chat_list_data_'+chatId).addClass('active');
	        $.ajax({
	            url:'{{url("admin/chat-detail")}}/'+chatId,
	            dataType: 'html',
	            success:function(result)
	            {
	            	$('#chatId').val(chatId);
	            	$('#senderId').val(senderId);
	            	$('#receiverId').val(receiverId);
	                $('#messages').html(result);
					//$('.chat-sidebar').hide();

					$("#messages").animate({
						scrollTop: $('#messages')[0].scrollHeight - $('#messages')[0].clientHeight
					}, 1000);
	            }
	        });

		} else {
			toastr.error('Something went wrong!!');
		}
	}
</script>
@endsection