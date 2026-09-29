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
                <h2 class="heading-inner mb-0">{{ $blogDetail->title }} <span>{{ date('M d,Y', strtotime($blogDetail->created_at)) }}</span></h2>
                <div class="ms-auto tag-cls">
                    <span class="border-btn">{{ $blogDetail->name }}</span>
                </div>
              </div>
              <div class="my-booking-sec">
                  <div class="helpcenter-banner">
                    <!-- <img src="img/helpcenter.png" alt="Helpcenter"> -->
                    @if(isset($blogDetail->image) && !empty($blogDetail->image))
                      <img src="{{ $blogDetail->image }}">
                    @else
                      <img src="{{ URL::asset('assets/web/img/h-1.png')}}" alt="">
                    @endif
                  </div>
                  <div class="helpcenter-desc">
                    {!! $blogDetail->description !!}
                  </div>
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
@endsection