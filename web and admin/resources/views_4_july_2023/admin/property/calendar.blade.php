@extends('layouts.master')

@section('content')
<div class="page-wrapper">
	<section id="first-load">
        <span>Loading...</span>
        <img src="{{ URL::asset('assets/web/img/logo.png')}}" alt="logo" width="auto">
        <div class="box-loader">
          <div class="container">
            <span class="circle-loader"></span>
            <span class="circle-loader"></span>
            <span class="circle-loader"></span>
            <span class="circle-loader"></span>
          </div>
        </div>
    </section>
	<div class="bookingCalenderView"></div>
	<div class="calendar_arrow">
		<div class="row">
			<div class="col-md-1"><i class="bx bx-chevron-left fa-icon-box" data-type='-1' aria-hidden="true"></i></div>
			<div class="col-md-10"></div>
			<div class="col-md-1" style="text-align: right !important; margin-left: auto;display: flex;justify-content: end;"><i class="bx bx-chevron-right fa-icon-box"  data-type='1' aria-hidden="true"></i></div>
		</div>
	</div>
</div>
<style type="text/css">
	.calendar_arrow {
	  margin-bottom: 20px;
	}
	.calendar_arrow .bx {
	  color: #000 !important;
	  height: 35px;
	  width: 35px;
	  font-size: 25px;
	  border: solid 1px #c6c6c6;
	  display: flex;
	  align-content: center;
	  justify-content: center;
	  line-height: 1.3;
	  border-radius: 50%;
	  background: #e3e2e2;
	}
</style>
<script type="text/javascript">
	var page = 1;
	$("#first-load").fadeOut(1500); 

	 $(document).on('click','.fa-icon-box',function() {
	    var type = $(this).data('type');

	    if(page >= 1)
	    {
	      if(type == '-1')
	      {
	        --page;
	        booking_calender(page);
	      }
	      else
	      {
	        ++page;
	        booking_calender(page);
	      }
	    }
	 });
	 booking_calender(page);

	 function booking_calender(page)
	 {
	  var url = "{{url('admin/property/load_calendar')}}?id="+"{{$id}}&page="+page;
	   $(".bookingCalenderView").load(url);
	 }

	function checkBlockCalender(date) {
		// var blockMsg = '';
		id = "{{$id}}";
		$.ajax({
			type: 'post',
			data: {_method: 'get', _token: "{{ csrf_token() }}"},
			dataType:'json',
			url: "{!! url('admin/property/checkBlockDate?id=' )!!}" + id +'&date='+date,
			success:function(res) {
				console.log('res-'+res.status);
				var blockMsg = confirm(res.message);
				blockCalender(blockMsg, date, id);
			},
			error:function(jqXHR,textStatus,textStatus){
				console.log(jqXHR);
				toastr.error(jqXHR.statusText)
			}
		});
	}

	function blockCalender(blockMsg, date, id) {
		// id = "{{$id}}";
	 	// var blockMsg = confirm('Are you sure want to block '+date+' date?');
        // alert(blockMsg);
        if (blockMsg) {
	        // id = "{{$id}}";
			// alert(id);
	        $.ajax({
				type: 'post',
				data: {_method: 'get', _token: "{{ csrf_token() }}"},
				dataType:'json',
				url: "{!! url('admin/property/blockDate?id=' )!!}" + id +'&date='+date,
				success:function(res) {
					if (res.status === 1) {
						toastr.success(res.message);
						booking_calender(page);
					} else {
						toastr.error(res.message);
						booking_calender(page);
					}
				},
				error:function(jqXHR,textStatus,textStatus){
					console.log(jqXHR);
					toastr.error(jqXHR.statusText)
				}
	        });
	    }
	}
</script>
@endsection