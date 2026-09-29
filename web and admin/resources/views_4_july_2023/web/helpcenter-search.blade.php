@if(isset($allBlogs) && count($allBlogs) > 0)
@foreach($allBlogs as $data)
  <div class="helpcenter-listing">
    <div class="hotel-img">
      @if(isset($data->image) && !empty($data->image))
        <img src="{{ $data->image }}">
      @else
        <img src="{{ URL::asset('assets/web/img/h-1.png')}}" alt="">
      @endif
    </div>
    <div class="hotel-cont">
      <div class="help-tag">
        <div class="tag-left">
          <span class="border-btn">{{ $data->name }}</span>
        </div>
        <div class="tag-right">
          <span>{{ date('M d,Y', strtotime($data->created_at)) }}</span>
        </div>
      </div>
      <h5><a href="{{ url('blog/'.$data->slug) }}">{{ $data->title }}</a></h5>
      <p>{!! substr($data->description, 0, 250) !!}</p>
    </div>
  </div>
@endforeach
@else
  <div>No Posts found.</div>
@endif