<?php if (count($data)) { foreach ($data as $key => $value) { ?>	
											<?php if ($value->receiver_detail) { ?>
												<a href="javascript:;" onclick="changeChatRoom(this)" class="list-group-item chat_list_data chat_list_data_{{$value->id}}" data-chatId="{{$value->id}}" data-senderId="{{$value->sender_id}}" data-receiverId="{{$value->receiver_id}}">
													<div class="d-flex">
														<div class="chat-user-online">
															<img src="{{$value->receiver_detail ? $value->receiver_detail->image : URL::asset('assets/images/avatars/avatar-3.png') }}" width="42" height="42" class="rounded-circle" alt="" />
														</div>
														<div class="flex-grow-1 ms-2">
															<h6 class="mb-0 chat-title">{{$value->receiver_detail->name}} ({{ $value->bookID ? $value->bookID : ''}})</h6>
															<p class="mb-0 chat-msg">{{$value->last_message ? $value->last_message->message : ''}}</p>
														</div>
														<div class="chat-time">{{ $value->last_message ? date('d M Y', strtotime($value->last_message->created_at)) : date('d M Y',strtotime($value->created_at))}}</div>
													</div>
												</a>
											<?php } ?>
										<?php } ?>
									<?php } else { ?>
										<span>No data found!!!</span>
									<?php } ?>