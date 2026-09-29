@extends('layouts.web.master')
<?php
  $login_user_id = '';

  $userData =  Session::get('AuthUserData') ?? null;

  if ($userData) {
      $login_user_id = $userData->data->id;
  }
?>
@section('content')
    <main>
	    <section class="contact_page space-cls">
	        <div class="container">
	        	<div class="contact_inner">
              <div class="row">
                <div class="col-md-12">
                  <div class="chat-wrapper">
                    <div class="filter_mobile_s">
                      <h4>Chat Listing</h4> <img id="chatlist_btn" src="{{ URL::asset('assets/web/img/filter.png')}}">
                    </div>
                    <div class="chat-sidebar">
                      <a href="javascript:void(0);" class="filter_cross">✖</a>
                      <div class="chat-sidebar-content">
                        <div class="tab-content" id="pills-tabContent">
                          <div class="tab-pane fade show active" id="pills-Chats">
                            <div class="chat-list">
                              <div class="list-group list-group-flush">

                                @if(isset($data->data) && $data->success)

                                  <?php foreach ($data->data as $key => $value) { ?>

                                    @if ($value->sender_detail)
                                      <a href="javascript:;" onclick="changeChatRoom(this)" class="list-group-item front_chat_list_data front_chat_list_data_{{$value->id}}" data-chatId="{{$value->id}}" data-senderId="{{$value->sender_id}}" data-receiverId="{{$value->receiver_id}}">
                                        <div class="d-flex">
                                          <div class="chat-user-online">
                                            <img src="{{ $value->sender_detail ? $value->sender_detail->image : ''}}" width="42" height="42" class="rounded-circle" alt="">
                                          </div>
                                          <div class="flex-grow-1 ms-2">
                                            <h6 class="mb-0 chat-title">Shortlet Rentals Support</h6>
                                            <!-- <h6 class="mb-0 chat-title">{{ $value->sender_detail ? $value->sender_detail->name : ''}}</h6> -->
                                            <p class="mb-0 chat-msg">{{$value->last_message ? $value->last_message->message : ''}}</p>
                                          </div>
                                          <div class="chat-time">{{ $value->last_message ? date('d M Y', strtotime($value->last_message->created_at)) : ''}}</div>
                                        </div>
                                      </a>
                                    @endif
                                  <?php } ?>
                                @endif
                                <!-- <a href="javascript:;" class="list-group-item">
                                  <div class="d-flex">
                                    <div class="chat-user-online">
                                      <img src="{{ URL::asset('assets/web/img/peter-parker.png')}}" width="42" height="42" class="rounded-circle" alt="">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                      <h6 class="mb-0 chat-title">Rachel Zane</h6>
                                      <p class="mb-0 chat-msg">I was thinking that we could...</p>
                                    </div>
                                    <div class="chat-time">Wed</div>
                                  </div>
                                </a>
                                <a href="javascript:;" class="list-group-item">
                                  <div class="d-flex">
                                    <div class="chat-user-online">
                                      <img src="{{ URL::asset('assets/web/img/kattie.png')}}" width="42" height="42" class="rounded-circle" alt="">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                      <h6 class="mb-0 chat-title">Donna Paulsen</h6>
                                      <p class="mb-0 chat-msg">Mike, I know everything!</p>
                                    </div>
                                    <div class="chat-time">Tue</div>
                                  </div>
                                </a>
                                <a href="javascript:;" class="list-group-item">
                                  <div class="d-flex">
                                    <div class="chat-user-online">
                                      <img src="{{ URL::asset('assets/web/img/peter-parker.png')}}" width="42" height="42" class="rounded-circle" alt="">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                      <h6 class="mb-0 chat-title">Jessica Pearson</h6>
                                      <p class="mb-0 chat-msg">Have you finished the draft...</p>
                                    </div>
                                    <div class="chat-time">9/3/2020</div>
                                  </div>
                                </a>
                                <a href="javascript:;" class="list-group-item">
                                  <div class="d-flex">
                                    <div class="chat-user-online">
                                      <img src="{{ URL::asset('assets/web/img/kattie.png')}}" width="42" height="42" class="rounded-circle" alt="">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                      <h6 class="mb-0 chat-title">Harold Gunderson</h6>
                                      <p class="mb-0 chat-msg">Thanks Mike! :)</p>
                                    </div>
                                    <div class="chat-time">12/3/2020</div>
                                  </div>
                                </a>
                                <a href="javascript:;" class="list-group-item">
                                  <div class="d-flex">
                                    <div class="chat-user-online">
                                      <img src="{{ URL::asset('assets/web/img/peter-parker.png')}}" width="42" height="42" class="rounded-circle" alt="">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                      <h6 class="mb-0 chat-title">Katrina Bennett</h6>
                                      <p class="mb-0 chat-msg">I've sent you the files for...</p>
                                    </div>
                                    <div class="chat-time">16/3/2020</div>
                                  </div>
                                </a>
                                <a href="javascript:;" class="list-group-item">
                                  <div class="d-flex">
                                    <div class="chat-user-online">
                                      <img src="{{ URL::asset('assets/web/img/kattie.png')}}" width="42" height="42" class="rounded-circle" alt="">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                      <h6 class="mb-0 chat-title">Charles Forstman</h6>
                                      <p class="mb-0 chat-msg">Mike, this isn't over.</p>
                                    </div>
                                    <div class="chat-time">18/3/2020</div>
                                  </div>
                                </a>
                                <a href="javascript:;" class="list-group-item">
                                  <div class="d-flex">
                                    <div class="chat-user-online">
                                      <img src="{{ URL::asset('assets/web/img/peter-parker.png')}}" width="42" height="42" class="rounded-circle" alt="">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                      <h6 class="mb-0 chat-title">Jonathan Sidwell</h6>
                                      <p class="mb-0 chat-msg">That's bullshit. This deal..</p>
                                    </div>
                                    <div class="chat-time">24/3/2020</div>
                                  </div>
                                </a> -->
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="chat-content" id="messages">
                        
                    </div>

                    <form action="">
                      <div class="chat-footer d-flex align-items-center">
                        <div class="flex-grow-1 pe-2">
                          <div class="input-group">
                            <input type="hidden" name="chatId" id="web_chatId" value="">
                            <input type="hidden" name="senderId" id="web_senderId" value="">
                            <input type="hidden" name="receiverId" id="web_receiverId" value="">
                            <input type="text" id="web_text_msg" class="form-control" autocomplete="off" placeholder="Type a message">
                            <button type="submit"><span class="input-group-text"><img src="{{ URL::asset('assets/web/img/send.png')}}" alt="Send"></span></button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>  
            </div>
	        </div>
	    </section>
    </main>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.3.0/socket.io.js"
        integrity="sha512-v8ng/uGxkge3d1IJuEo6dJP8JViyvms0cly9pnbfRxT6/31c3dRWxIiwGnMSWwZjHKOuY3EVmijs7k1jz/9bLA=="
        crossorigin="anonymous"></script>
<script>
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
            var senderChatId = $('#web_chatId').val();
            var sender_id = $('#web_senderId').val();
            var receiver_id = $('#web_receiverId').val();
            var roomId = sender_id.toString() + receiver_id.toString();

            if (roomId) {
              var currentdate = new Date();
              var datetime = moment(currentdate).format('DD MMM, YYYY h:mma');


              if ($('#web_text_msg').val()) {
                $('#messages').append('<div class="chat-content-rightside"><div class="d-flex"><div class="flex-grow-1 me-2"><p class="chat-right-msg">'+$('#web_text_msg').val()+'</p><p class="chat-time text-end">you, '+datetime+'</p></div></div></div>');

                socket.emit('sendMessage', {
                    type: 'text',
                    message: $('#web_text_msg').val(),
                    booking_id: 4,
                    sender_id: sender_id,
                    receiver_id: receiver_id,
                    chatId: senderChatId
                });

                $("#messages").animate({
                  scrollTop: $('#messages')[0].scrollHeight - $('#messages')[0].clientHeight
                }, 1000);

                $('#web_text_msg').val('');
                return false;

              } else {
                toastr.error('Please enter message!!!');
              }

            } else {
                toastr.error('Please select user for chat!!!');
            }


        });

        // socket.emit('join', roomId);

        /* socket.emit('chat message', {
                message: "Hey",
                sender_id: 8,
                receiver_id: 1,
            }); */

        socket.on('receivedMessage', function (msg) {
            console.log("receivedMessage", msg)

            var currentdate = new Date();
            var datetime = moment(currentdate).format('DD MMM, YYYY h:mma');
            //$("#room_id").val(msg.roomId);
            /*if (msg.receiver_id == "8") {
                $('#messages').append($('<li>').text(msg.data.last_message.message));
            }*/
            //$('#messages').append($('<li>').text(msg.data.last_message.message));

            // if (msg.sender_id == login_user_id) {
            // }
              $('#messages').append('<div class="chat-content-leftside"><div class="d-flex"><div class="flex-grow-1 ms-2"><p class="chat-left-msg">'+msg.last_message.message+'</p><p class="chat-time">'+msg.last_message.sent_by_detail.name+', '+datetime+'</p></div></div></div>');

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

  var widths = $(window).width();

  function changeChatRoom($this) {
    if(widths <= 769)
    {
      $('.chat-sidebar').hide();
    }

    var chatId = $($this).attr('data-chatId');
    // var senderId = $($this).attr('data-senderId');
    var login_user_id = "{{$login_user_id}}";
    var senderId = login_user_id;
    var receiverId = $($this).attr('data-receiverId');

    if (login_user_id == receiverId) {
      receiverId = $($this).attr('data-senderId');
    }

    var roomId = senderId.toString() + receiverId.toString();
    console.log(roomId+'---------------------roomId---------------');
    socket.emit('join', roomId);
    $('.front_chat_list_data').removeClass('active');

    if (chatId) {
      $('.front_chat_list_data_'+chatId).addClass('active');
          $.ajax({
              url:'{{url("chat-detail")}}/'+chatId,
              dataType: 'html',
              success:function(result)
              {
                $('#web_chatId').val(chatId);
                $('#web_senderId').val(senderId);
                $('#web_receiverId').val(receiverId);
                $('#messages').html(result);
            
                $("#messages").animate({
                  scrollTop: $('#messages')[0].scrollHeight - $('#messages')[0].clientHeight
                }, 1000);
              }
          });

    } else {
      toastr.error('Something went wrong!!');
    }
  }

  $(document).on('click','#chatlist_btn',function(){
    $('.chat-sidebar').show();
  });
</script>
@endsection