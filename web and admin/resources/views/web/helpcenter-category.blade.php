@extends('layouts.web.master')

@section('content')
    <main>
	    <section class="mybooking_page space-cls">
	        <div class="container">
	        	<div class="booking-inner">
	        		<div class="sidebar-wrap">
	        			<div class="sidebar_l">
		        			<div class="sidebar-link">
                      <h3>RECENT POSTS</h3>
		        			    <div class="post-sec">
                        
                        @if(isset($recentBlogs) && count($recentBlogs) > 0)
                        @foreach($recentBlogs as $recent)
                          <a href="{{ url('blog/'.$recent->slug) }}" class="post-listing">
                            <div class="post-img">
                              @if(isset($recent->image) && !empty($recent->image))
                                <img src="{{ $recent->image }}" width="50" height="50">
                              @else
                                <img src="{{ URL::asset('assets/web/img/post-1.png')}}">
                              @endif
                            </div>
                            <div class="post-cont">
                              <h5>{{ $recent->title }}</h5>
                              <p>{{ date('M d,Y', strtotime($recent->created_at)) }}</p>
                            </div>
                          </a>
                        @endforeach
                        @else
                          <div>No Posts found.</div>
                        @endif
                      </div>
		        			</div>
                  <div class="sidebar-link">
                    <h3>CATEGORIES</h3>
                    <ul>
                        @if(isset($category) && count($category) > 0)
                        @foreach($category as $category1)
                          <li><a href="{{ url('blog/category/'.$category1->id) }}">{{ $category1->name }}</a></li>
                        @endforeach
                        @else
                          <div>No Category found.</div>
                        @endif
                    </ul>
                  </div>
		        		</div>
	        			<div class="sidebar_r">
	        				<div class="inner-title  d-flex align-items-center">
	        					<h2 class="heading-inner-title mb-0">Help Center</h2>
	        					<div class="ms-auto search-cls">
	        						<div class="filter_serch">
                        <div class="form-group">
                          <input type="text" name="" placeholder="Search by Post Title..." class="form-control searchBlog">
                          <div class="search_i">
                            <img src="{{ URL::asset('assets/web/img/search.png')}}" alt="">
                          </div>
                        </div>
                      </div>
	        					</div>
	        				</div>
	        				<div class="my-booking-sec allBlogSection">
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
	        				</div>
	        			</div>
	        		</div>
	        	</div>
	        </div>
	    </section>
    </main>
    <div class="button-fix">
    	<a href="#" class="message-right">Message</a>
    </div>

<script>
  $(document).on('input','.searchBlog',function(e){
    e.preventDefault();
    // $('.images_content_response').empty();
    var search_value = $(this).val();
    // if(search_value != ''){
      $('.allBlogSection').html('');
      $.ajax({
        type: 'post',
        data: {_method: 'post', _token: "{{ csrf_token() }}",'search_value':search_value},
        url:'{{url('searchBlog')}}',
        dataType: 'html',
        success:function(result)
        {
          $('.allBlogSection').html(result);
        } 
      });
    // }
  });
</script>
@endsection