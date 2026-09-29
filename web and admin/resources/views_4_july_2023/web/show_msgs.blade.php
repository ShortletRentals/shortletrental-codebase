<?php if ($data->status) { foreach ($data->data as $key => $value) { ?>
    @if($value->sent_by_detail)
      @if ($value->sent_by_detail->id != $login_user_id)
        <div class="chat-content-leftside">
          <div class="d-flex">
            <!-- <img src="{{$value->sent_by_detail->image}}" width="48" height="48" class="rounded-circle" alt="" /> -->
            <div class="flex-grow-1 ms-2">
              <p class="chat-left-msg">{{$value->message}}</p>
              <p class="chat-time">{{$value->sent_by_detail->name}}, {{ date('d M Y', strtotime($value->created_at))}}</p>
            </div>
          </div>
        </div>
      @else
        <div class="chat-content-rightside">
          <div class="d-flex ms-auto">
            <div class="flex-grow-1 me-2">
              <p class="chat-right-msg">{{$value->message}}</p>
              <p class="chat-time text-end">{{$value->sent_by_detail->name}}, {{ date('d M Y', strtotime($value->created_at))}}</p>
            </div>
          </div>
        </div>
      @endif
    @endif
  <?php } ?>
<?php } ?>