<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}">
@if(count($booking['next_upcoming_bookings']) > 0)
<div id="wlvProximosCheckins" class="WAjaxListView ">
	<table id="example2" class="wListView" style="display:" cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
		<thead>
			<tr>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($booking['next_upcoming_bookings'] as $next_booking)
				<tr>
					<td data-urlcompromiso="https://app.shortletrentals.com/index.php?record=16237751&amp;module=Compromisos&amp;action=DetailView&amp;return_module=Home&amp;return_action=Ajax">
						<a href="{{ route('admin.property.show',['id'=>$next_booking->id]) }}">
							<div class="genericRow">
								<div class="contentRow">
									<span class="clientName columnRight">{{ $next_booking->property_title }}</span>
									<span class="dateCheckin genericFormat columnLeft">enter {{ date('d/m', strtotime($next_booking->from_date)) }}</span>
								</div>
								<div class="contentRow">
									<span class="propertyName genericFormat columnRight">{{ $next_booking->property_description }}</span>
									<span class="timeCheckin genericFormat columnLeft">{{ date('h:i', strtotime($next_booking->from_date)) }}</span>
								</div>
							</div>
						</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
<!-- @if($booking['total_upcoming_pages'] > 1)
<div class="pagination_div">
	<ul>
		<li> <a href="javascript::void(0)" onClick="goToPage()"> < </a> </li>
		@for($i = 1; $i <= $booking['total_upcoming_pages']; $i++)
			<li><a href="javascript::void(0)" onClick="goToPage({{$i}})">{{$i}}</a></li>
		@endfor
		<li> <a href="javascript::void(0)" onClick="goToPage()"> > </a> </li>
	</ul>
</div>
@endif -->
@else
	<div class="no_upcoming_bookings" style="text-align: center;color:black;"><strong>You have no upcoming check-ins requests for the selected time period.</strong></div>
@endif 
<script src="{{ URL::asset('assets/vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script>
// $('#example1').DataTable();
$('#example2').DataTable({
	// "bProcessing": true,
    // "sAutoWidth": false,
    // "bDestroy":true,
    // "sPaginationType": "bootstrap", // full_numbers
    "iDisplayStart ": 4,
    "iDisplayLength": 4,
    // "bPaginate": false, //hide pagination
    "bFilter": false, //hide Search bar
    "bInfo": false, // hide showing entries
	// "paging": false,
	"bLengthChange": false,
	"ordering": false,
});
</script>