<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourtBooking;
use App\Models\CourtLang;
use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\PropertyReserveRequest;
use App\Models\Discount;
use Illuminate\Support\Facades\Auth;
use DB, DateTime;
use App\Models\Courts;
use App\Models\Facility;
use Carbon\Carbon;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		$user_type = User::where('id',auth()->id())->pluck('user_type')->first();
		
		if($user_type == 3){
			// return redirect(route('admin.booking.index'));
			$total_coupon_code = Discount::where(['influencer'=>auth()->id() ])->count();
			$total_customer = Booking::where(['influencer_id'=>auth()->id() ])->whereNotNull('coupon_code')->count();
			// dd($total_coupon_code, $total_customer);
			return view('admin.dashboard_influencer',compact('total_coupon_code','total_customer'));
		}else if($user_type == 5){
			return redirect(route('admin.property.index'));
		}else if($user_type == 4){
			auth()->logout();		
			return redirect(route('web.home'));
		}else{
			checkContractExpire();
			$startDate = Carbon::now()->format('Y-m-d');
			// dd($startDate);
			$website_bookings = Booking::where(['booking_from'=>'Website'])->count();
			$mobile_bookings = Booking::where(['booking_from'=>'Mobile'])->count();
			$guest_ids = User::where(['status'=>1, 'is_guest'=>1])->pluck('id')->toArray();
			$customer_ids = User::where(['status'=>1, 'is_guest'=>0])->pluck('id')->toArray();
			$guest_bookings = Booking::whereIn('guest_id',$guest_ids)->count();
			$customer_bookings = Booking::whereIn('guest_id',$customer_ids)->count();
			// dd(Carbon::now()->subDay());
			$last_24hour_admin_commission = Booking::where("created_at",">",Carbon::now()->subDay())->where("created_at","<",Carbon::now())
			->whereNotIn('bookings.booking_status',['Cancelled-Booking'])
			->where(['booking_status'=>'Confirmed-by-Host'])
			->sum('admin_amount');
			// dd($last_24hour_admin_commission);
			$mobile_bookings = Booking::where(['booking_from'=>'Mobile'])->count();
			$customer = User::where(['user_type'=>4])->count();
			$host = User::where(['user_type'=>5])->count();
			$property = Property::query()->count();
			$active_property = Property::where(['status'=>1])->count();
			$total_booking = Booking::count();
			$last_30_day_date = \Carbon\Carbon::today()->subDays(30);
			///Last 7 day bookings
			$last_7_day_date = \Carbon\Carbon::today()->subDays(7);
			$next_7_day_date = Carbon::now()->addDays(7)->format('Y-m-d');
			
			$previous_incoming_bookings = Booking::whereBetween('created_at', [$last_7_day_date, Carbon::now()] )->where('instant_read',0)->count();
			$previous_not_confirmed_bookings = Booking::where('booking_status','Not-confirmed-by-Host')->whereBetween('from_date', [$last_7_day_date, Carbon::now()] )->count();
			// $previous_confirmed_bookings = Booking::where('booking_status','Confirmed-by-Host')->whereBetween('from_date', [$last_7_day_date, Carbon::now()] )->count();
			$previous_confirmed_bookings = PropertyReserveRequest::whereBetween('created_at', [$last_7_day_date, Carbon::now()] )->where('confirmed_read',0)->count();

			$seven_days_pending_payments_checkin_count = Booking::select('bookings.id','booking_check_ins.booking_id')
			->leftjoin('booking_check_ins','booking_check_ins.booking_id','bookings.id')
			->whereBetween('bookings.created_at', [$last_7_day_date, Carbon::now()] )
			->where('booking_check_ins.booking_id',null)
			->whereNotIn('bookings.booking_status',['Cancelled-Booking'])
			->get()
			->count();
/*
			$seven_days_pending_payments_count = Booking::whereBetween('created_at', [$last_7_day_date, Carbon::now()] )
			->whereNotIn('bookings.booking_status',['Cancelled-Booking'])
			->where('booking_type','<>','Paid')
			->where('booking_type','<>','Confirmed')
			->count();
			*/

			$seven_days_pending_payments_count = Booking::whereBetween('created_at', [$last_7_day_date, Carbon::now()] )
			->where('bookings.booking_status', 'Not-confirmed-by-Host')
			->whereIn('booking_type',['Pre-booking'])
			->whereNotIn('bookings.booking_status',['Cancelled-Booking'])
			->count();

			
			$seven_days_pending_payments_sum = Booking::whereBetween('created_at', [$last_7_day_date, Carbon::now()] )->where('booking_type','<>','Paid')->where('booking_type','<>','Confirmed')->sum('total_amount');
			// dd($seven_days_pending_payments_count, $seven_days_pending_payments_checkin_count);
			//dd($seven_days_pending_payments_count , $seven_days_pending_payments_checkin_count);
			$total_pending_actions_count = $seven_days_pending_payments_count + $seven_days_pending_payments_checkin_count;
			
			$total_upcoming_bookings = Booking::select('bookings.*','properties.title as property_title','properties.id  as property_id','properties.additional_notes as property_description')
			->leftjoin('properties','properties.id','=','bookings.property_id')
			->whereBetween('bookings.from_date', [$startDate, $next_7_day_date])
			->whereNotIn('bookings.booking_status',['Cancelled-Booking'])
			->get();
			$next_upcoming_bookings = Booking::select('bookings.*','properties.title as property_title','properties.id as property_id','properties.additional_notes as property_description')
			->leftjoin('properties','properties.id','=','bookings.property_id')
			->whereBetween('bookings.from_date', [$startDate, $next_7_day_date])
			->whereNotIn('bookings.booking_status',['Cancelled-Booking'])
			->get();
			if(count($total_upcoming_bookings) > 0){
				$total_upcoming_pages = (int)round(count($total_upcoming_bookings)/ 2);
			}else{
				$total_upcoming_pages = 1;
			}
			
			$current_bookings = Booking::select('bookings.*','properties.id as property_id','properties.title as property_title','properties.additional_notes as property_description')
			->leftjoin('properties','properties.id','=','bookings.property_id')
			->whereRaw(\DB::raw('CURDATE() between bookings.from_date and bookings.to_date'))
			->whereNotIn('bookings.booking_status',['Cancelled-Booking'])
			->get();
			
			$last_30_day_bookings = Booking::where(['booking_status'=>'Confirmed-by-Host'])->where('from_date', '>=', $last_30_day_date)->get();
			if(isset($last_30_day_bookings) && count($last_30_day_bookings) > 0){
				$last_30_day_total_bookings = count($last_30_day_bookings);
				$last_30_day_bookings_amount = $last_30_day_bookings->sum('host_amount');
			}else{
				$last_30_day_total_bookings = 0;
				$last_30_day_bookings_amount = 0;
			}
			$last_30_day_cancellation = Booking::where(['booking_status'=>'Cancelled-Booking'])->where('from_date', '>=', $last_30_day_date)->get();
			if(isset($last_30_day_cancellation) && count($last_30_day_cancellation) > 0){
				$last_30_day_total_cancellation = count($last_30_day_cancellation);
				$last_30_day_cancellation_amount = 	$last_30_day_cancellation->sum('host_amount');
			}else{
				$last_30_day_total_cancellation = 	0;
				$last_30_day_cancellation_amount = 	0;
			}
	
			$last_30_day_avg = Booking::select(DB::raw('AVG(host_amount) as avg_amount'))->where(['booking_status'=>'Completed-Booking'])->where('from_date', '>=', $last_30_day_date)->first();
			if(isset($last_30_day_avg) && !empty($last_30_day_avg)){
				$last_30_day_avg_amt = $last_30_day_avg->avg_amount;
			}else{
				$last_30_day_avg_amt = 0;
			}
			$last_30_day_avg_night1 = Booking::whereBetween('bookings.from_date', [$last_30_day_date, $startDate])->sum('total_days');
			// $last_30_day_avg_night1 = Booking::where('from_date', '>=', $last_30_day_date)->sum('total_days');
			$last_30_day_avg_night = $last_30_day_avg_night1 / 30;
			// $last_30_day_avg_night = Booking::select(DB::raw('DATE(from_date) AS start_date, AVG(TIME_TO_SEC(TIMEDIFF(to_date, from_date))) AS timediff'))->groupBy('start_date')->get();
	
			$period = now()->subMonths(12)->monthsUntil(now());
			$monthWiseDates = [];
			$monthWiseAmount = [];
			$monthDates = [];
			foreach ($period as $date)
			{
				$monthWiseDates[] = $date->shortMonthName;//.'-'.$date->year;
				$monthDates[] = [
					'month' => $date->month,
					'year' => $date->year,
				];
				$total_earning = Booking::select('*')->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->whereNotIn('booking_status',['Cancelled-Booking'])->sum('total_amount');
				$total_causion = Booking::select('*')->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->whereNotIn('booking_status',['Cancelled-Booking'])->sum('security_deposite');
				$optional_service_amount = Booking::select('*')->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->whereNotIn('booking_status',['Cancelled-Booking'])->sum('optional_service_amount');
				if($total_causion){
					$total_earning = $total_earning - $total_causion;
				}
				$total_earning = $total_earning + $optional_service_amount;
				$monthWiseAmount[] = round($total_earning / 1000000, 2);
				//$monthWiseAmount[] =  $total_earning;
 			}
			$last_12_months_list = json_encode($monthWiseDates);
			$last_12_months_amount = json_encode($monthWiseAmount);
	
			$date = new DateTime('now');
			$date->modify('last day of this month');
			$last_date = (int)$date->format('d');
			$all_properties = Property::count();
			$overall_nights = $all_properties * $last_date;
			$this_month_nights = Booking::whereMonth('from_date', date('m'))
			->whereYear('from_date', date('Y'))
			->whereNotIn('booking_status',['Cancelled-Booking'])
			->sum('total_days');
			if(!empty($this_month_nights) && !empty( $overall_nights))
			{
				$occupancy1 = $this_month_nights / $overall_nights * 100;
			
			}
			else{
				$occupancy1=0;
			}

			if(isset($occupancy1) && !empty($occupancy1)){
				$occupancy = number_format((float)$occupancy1, 2, '.', '');
			}else{
				$occupancy = 0;
			}
			$booking = ['incoming_bookings'=>$previous_incoming_bookings, 'not_confirmed_bookings'=>$previous_not_confirmed_bookings, 'confirmed_bookings'=>$previous_confirmed_bookings, 'next_upcoming_bookings'=>$next_upcoming_bookings, 'current_bookings'=>$current_bookings];
			$last_30_day_bookings = ['last_30_day_total_bookings'=>$last_30_day_total_bookings, 'last_30_day_bookings_amount'=>$last_30_day_bookings_amount, 'last_30_day_total_cancellation'=>$last_30_day_total_cancellation, 'last_30_day_cancellation_amount'=>$last_30_day_cancellation_amount, 'last_30_day_avg_amt'=>$last_30_day_avg_amt, 'last_30_day_avg_night'=>$last_30_day_avg_night];
	
			return view('admin.dashboard',compact('customer','host','property','total_booking','booking','last_30_day_bookings','active_property','next_upcoming_bookings','total_upcoming_pages','last_12_months_list','last_12_months_amount','occupancy', 'website_bookings','mobile_bookings','guest_bookings','customer_bookings','seven_days_pending_payments_count','seven_days_pending_payments_sum','seven_days_pending_payments_checkin_count','total_pending_actions_count','last_24hour_admin_commission'));
		}
	}

	function divideFloat($a, $b, $precision=0) {
		$a*=pow(10, $precision);
		$result=(float)($a / $b);
		return $result;
		// if (strlen($result)==$precision) return '0.' . $result;
		// else return preg_replace('/(\d{' . $precision . '})$/', '.\1', $result);
	}

	public function occupancyFilter(Request $request){
		$input = $request->all();
		// dd($input);
		$value = $input['value'];
		if($value == 'today'){
			$overall_nights = Property::count();
			$this_month_nights = Booking::whereMonth('from_date', date('Y-m-d').'00:00:00')->sum('total_days');
			$occupancy1 = $this_month_nights / $overall_nights * 100;
			if(isset($occupancy1) && !empty($occupancy1)){
				$occupancy = number_format((float)$occupancy1, 2, '.', '');
			}else{
				$occupancy = 0;
			}
		}else if($value == 'this_month'){
			$date = new DateTime('now');
			$date->modify('last day of this month');
			$last_date = (int)$date->format('d');
			$all_properties = Property::count();
			$overall_nights = $all_properties * $last_date;
			$this_month_nights = Booking::whereMonth('from_date', date('m'))->whereYear('from_date', date('Y'))->sum('total_days');
			$occupancy1 = $this_month_nights / $overall_nights * 100;
			if(isset($occupancy1) && !empty($occupancy1)){
				$occupancy = number_format((float)$occupancy1, 2, '.', '');
			}else{
				$occupancy = 0;
			}
		}else if($value == 'this_year'){
			// $date = new DateTime('now');
			// $date->modify('last day of this month');
			// $last_date = (int)$date->format('d');
			// $current_date = (int)date('d');
			$all_properties = Property::count();
			$overall_nights = $all_properties * 365;
			$this_month_nights = Booking::whereMonth('from_date', date('m'))->whereYear('from_date', date('Y'))->sum('total_days');
			// dd($overall_nights, $this_month_nights);
			$occupancy1 = $this_month_nights / $overall_nights * 100;
			// dd($occupancy1);
			if(isset($occupancy1) && !empty($occupancy1)){
				$occupancy = number_format((float)$occupancy1, 2, '.', '');
			}else{
				$occupancy = 0;
			}
		}
		return $occupancy;
	}

	public function goToPage(Request $request){
		// dd($request->all());
		$startDate = Carbon::now()->format('Y-m-d');
		$next_7_day_date = Carbon::now()->addDays(7)->format('Y-m-d');
		$next_30_day_date = Carbon::now()->addDays(30)->format('Y-m-d');
		// dd($startDate, $next_7_day_date);
		$input = $request->all();
		$value = $input['days'];
		$total_upcoming_bookings = Booking::select('bookings.*','properties.title as property_title','properties.additional_notes as property_description')->leftjoin('properties','properties.id','=','bookings.property_id')->get();
		if($value == 'today'){
			$next_upcoming_bookings = Booking::select('bookings.*','properties.title as property_title','properties.additional_notes as property_description')->leftjoin('properties','properties.id','=','bookings.property_id')->where('bookings.from_date', '=', date('Y-m-d').' 00:00:00')->take(2)->get();

			if(count($total_upcoming_bookings) > 0){
				$total_upcoming_pages = (int)round(count($total_upcoming_bookings)/ 2);
			}else{
				$total_upcoming_pages = 1;
			}
		}else if($value == 'next_7_days'){
			$next_upcoming_bookings = Booking::select('bookings.*','properties.title as property_title','properties.additional_notes as property_description')->leftjoin('properties','properties.id','=','bookings.property_id')->whereBetween('bookings.from_date', [$startDate, $next_7_day_date])->take(2)->get();

			if(count($total_upcoming_bookings) > 0){
				$total_upcoming_pages = (int)round(count($total_upcoming_bookings)/ 2);
			}else{
				$total_upcoming_pages = 1;
			}
		}else if($value == 'next_30_days'){
			$next_upcoming_bookings = Booking::select('bookings.*','properties.title as property_title','properties.additional_notes as property_description')->leftjoin('properties','properties.id','=','bookings.property_id')->whereBetween('bookings.from_date', [$startDate, $next_7_day_date])->take(2)->get();

			if(count($total_upcoming_bookings) > 0){
				$total_upcoming_pages = (int)round(count($total_upcoming_bookings)/ 2);
			}else{
				$total_upcoming_pages = 1;
			}
		}
		$booking = ['next_upcoming_bookings'=>$next_upcoming_bookings,'total_upcoming_pages'=>$total_upcoming_pages];
		// dd($booking);
		return view('admin.next_booking_dashboard',compact('booking'));
	}

	public function previous_day_records(Request $request){
		$input = $request->all();
		$value = $input['value'];
		// dd($value);
		if($value == 'today'){
			// $incoming_bookings = Booking::where('created_at', '=', date('Y-m-d').' 00:00:00')->count();
			$previous_incoming_bookings = Booking::whereDate('created_at', '=', date('Y-m-d').' 00:00:00')->count();
			$previous_not_confirmed_bookings = Booking::where('booking_status','Not-confirmed-by-Host')->where('from_date', '=', date('Y-m-d').' 00:00:00')->count();
			// $previous_confirmed_bookings = Booking::where('booking_status','Confirmed-by-Host')->where('from_date', '=', date('Y-m-d').' 00:00:00')->count();
			$previous_confirmed_bookings = PropertyReserveRequest::whereDate('created_at', '=', date('Y-m-d'))->count();
			// $not_confirmed_bookings = Booking::where('booking_status','Not-confirmed-by-Host')->where('from_date', '>=', date('Y-m-d').' 00:00:00')->count();
			$confirmed_bookings = Booking::where('booking_status','Confirmed-by-Host')->where('from_date', '=', date('Y-m-d').' 00:00:00')->count();
		}else if($value == 'last_7_days'){
			// dd('inn');
			$last_7_day_date = \Carbon\Carbon::today()->subDays(7);
			// dd($last_7_day_date);
			// $incoming_bookings = Booking::whereBetween('created_at', [$last_7_day_date, Carbon::now()] )->count();
			$previous_incoming_bookings = Booking::whereBetween('created_at', [$last_7_day_date, Carbon::now()] )->count();
			// dd($previous_incoming_bookings);
			$previous_not_confirmed_bookings = Booking::where('booking_status','Not-confirmed-by-Host')->whereBetween('from_date', [$last_7_day_date, Carbon::now()] )->count();
			// $previous_confirmed_bookings = Booking::where('booking_status','Confirmed-by-Host')->whereBetween('from_date', [$last_7_day_date, Carbon::now()] )->count();
			$previous_confirmed_bookings = PropertyReserveRequest::whereBetween('created_at', [$last_7_day_date, Carbon::now()] )->count();

		}else if($value == 'last_30_days'){
			$last_30_day_date = \Carbon\Carbon::today()->subDays(30);
			// dd($last_30_day_date);
			// $incoming_bookings = Booking::whereBetween('created_at', [$last_30_day_date, Carbon::now()] )->count();
			$previous_incoming_bookings = Booking::whereBetween('created_at', [$last_30_day_date, Carbon::now()] )->count();
			$previous_not_confirmed_bookings = Booking::where('booking_status','Not-confirmed-by-Host')->whereBetween('from_date', [$last_30_day_date, Carbon::now()] )->count();
			// $previous_confirmed_bookings = Booking::where('booking_status','Confirmed-by-Host')->whereBetween('from_date', [$last_30_day_date, Carbon::now()] )->count();
			$previous_confirmed_bookings = PropertyReserveRequest::whereBetween('created_at', [$last_30_day_date, Carbon::now()] )->count();
		}
		$booking = ['incoming_bookings'=>$previous_incoming_bookings, 'not_confirmed_bookings'=>$previous_not_confirmed_bookings, 'confirmed_bookings'=>$previous_confirmed_bookings];
		return $booking;
	}

	public function pending_action_records(Request $request){
		$input = $request->all();
		$value = $input['value'];
		$last_30_day_date = \Carbon\Carbon::today()->subDays(30);
		$last_7_day_date = \Carbon\Carbon::today()->subDays(7);
		// dd($value);
		if($value == 'today'){
			$seven_days_pending_payments_checkin_count = Booking::select('bookings.id','booking_check_ins.booking_id')->leftjoin('booking_check_ins','booking_check_ins.booking_id','bookings.id')->whereDate('bookings.created_at','=', date('Y-m-d') )->where('booking_check_ins.booking_id',null)->get()->count();

			$seven_days_pending_payments_count = Booking::whereDate('bookings.created_at','=', date('Y-m-d') )->where('booking_type','<>','Paid')->where('booking_type','<>','Confirmed')->count();
			$seven_days_pending_payments_sum = Booking::whereDate('bookings.created_at','=', date('Y-m-d') )->where('booking_type','<>','Paid')->where('booking_type','<>','Confirmed')->sum('total_amount');
			// dd($seven_days_pending_payments_count, $seven_days_pending_payments_checkin_count);
			$total_pending_actions_count = $seven_days_pending_payments_count + $seven_days_pending_payments_checkin_count;
			// dd($seven_days_pending_payments_checkin_count, $seven_days_pending_payments_count, $seven_days_pending_payments_sum, $total_pending_actions_count);
		}else if($value == 'last_7_days'){
			$seven_days_pending_payments_checkin_count = Booking::select('bookings.id','booking_check_ins.booking_id')->leftjoin('booking_check_ins','booking_check_ins.booking_id','bookings.id')->whereBetween('bookings.created_at', [$last_7_day_date, Carbon::now()] )->where('booking_check_ins.booking_id',null)->get()->count();

			$seven_days_pending_payments_count = Booking::whereBetween('created_at', [$last_7_day_date, Carbon::now()] )->where('booking_type','<>','Paid')->where('booking_type','<>','Confirmed')->count();
			$seven_days_pending_payments_sum = Booking::whereBetween('created_at', [$last_7_day_date, Carbon::now()] )->where('booking_type','<>','Paid')->where('booking_type','<>','Confirmed')->sum('total_amount');
			// dd($seven_days_pending_payments_count, $seven_days_pending_payments_checkin_count);
			$total_pending_actions_count = $seven_days_pending_payments_count + $seven_days_pending_payments_checkin_count;

		}else if($value == 'last_30_days'){
			$seven_days_pending_payments_checkin_count = Booking::select('bookings.id','booking_check_ins.booking_id')->leftjoin('booking_check_ins','booking_check_ins.booking_id','bookings.id')->whereBetween('bookings.created_at', [$last_30_day_date, Carbon::now()] )->where('booking_check_ins.booking_id',null)->get()->count();

			$seven_days_pending_payments_count = Booking::whereBetween('created_at', [$last_30_day_date, Carbon::now()] )->where('booking_type','<>','Paid')->where('booking_type','<>','Confirmed')->count();
			$seven_days_pending_payments_sum = Booking::whereBetween('created_at', [$last_30_day_date, Carbon::now()] )->where('booking_type','<>','Paid')->where('booking_type','<>','Confirmed')->sum('total_amount');
			// dd($seven_days_pending_payments_count, $seven_days_pending_payments_checkin_count);
			$total_pending_actions_count = $seven_days_pending_payments_count + $seven_days_pending_payments_checkin_count;
		}
		// $booking = ['incoming_bookings'=>$previous_incoming_bookings, 'not_confirmed_bookings'=>$previous_not_confirmed_bookings, 'confirmed_bookings'=>$previous_confirmed_bookings];
		$booking = ['seven_days_pending_payments_checkin_count'=>$seven_days_pending_payments_checkin_count, 'seven_days_pending_payments_count'=>$seven_days_pending_payments_count, 'seven_days_pending_payments_sum'=>$seven_days_pending_payments_sum, 'total_pending_actions_count'=>$total_pending_actions_count];
		return $booking;
	}

	public function next_day_records($value){
		$startDate = Carbon::now()->format('Y-m-d');
		$next_7_day_date = Carbon::now()->addDays(7)->format('Y-m-d');
		$next_30_day_date = Carbon::now()->addDays(30)->format('Y-m-d');
		// dd($startDate, $next_7_day_date);
		$total_upcoming_bookings = Booking::select('bookings.*','properties.title as property_title','properties.additional_notes as property_description')->leftjoin('properties','properties.id','=','bookings.property_id')->get();
		if($value == 'today'){
			$next_upcoming_bookings = Booking::select('bookings.*','properties.title as property_title','properties.additional_notes as property_description')->leftjoin('properties','properties.id','=','bookings.property_id')->where('bookings.from_date', '=', date('Y-m-d').' 00:00:00')->get();

			if(count($total_upcoming_bookings) > 0){
				$total_upcoming_pages = (int)round(count($total_upcoming_bookings)/ 2);
			}else{
				$total_upcoming_pages = 1;
			}
		}else if($value == 'next_7_days'){
			$next_upcoming_bookings = Booking::select('bookings.*','properties.title as property_title','properties.additional_notes as property_description')->leftjoin('properties','properties.id','=','bookings.property_id')->whereBetween('bookings.from_date', [$startDate, $next_7_day_date])->get();

			if(count($total_upcoming_bookings) > 0){
				$total_upcoming_pages = (int)round(count($total_upcoming_bookings)/ 2);
			}else{
				$total_upcoming_pages = 1;
			}
		}else if($value == 'next_30_days'){
			$next_upcoming_bookings = Booking::select('bookings.*','properties.title as property_title','properties.additional_notes as property_description')->leftjoin('properties','properties.id','=','bookings.property_id')->whereBetween('bookings.from_date', [$startDate, $next_30_day_date])->get();

			if(count($total_upcoming_bookings) > 0){
				$total_upcoming_pages = (int)round(count($total_upcoming_bookings)/ 2);
			}else{
				$total_upcoming_pages = 1;
			}
		}
		$booking = ['next_upcoming_bookings'=>$next_upcoming_bookings,'total_upcoming_pages'=>$total_upcoming_pages,'upcoming_bookings_totle'=> count($next_upcoming_bookings) ];
		$result['upcoming_bookings_totle'] = count($next_upcoming_bookings);
		$result['status'] = true;
		$result['message'] = view('admin.next_booking_dashboard',compact('booking'))->toHtml();
		return $result;
		// dd($booking);
		// return view('admin.next_booking_dashboard',compact('booking'));
		// return $booking;
	}

	public function change_booking_cancellation(Request $request){
		$input = $request->all();
		$value = $input['value'];
		// dd($value);
		if($value == 'today'){
			$last_30_day_bookings = Booking::where(['booking_status'=>'Confirmed-by-Host'])->where('from_date', '=', date('Y-m-d').' 00:00:00')->get();
			if(isset($last_30_day_bookings) && count($last_30_day_bookings) > 0){
				$last_30_day_total_bookings = count($last_30_day_bookings);
				$last_30_day_bookings_amount = $last_30_day_bookings->sum('host_amount');
			}else{
				$last_30_day_total_bookings = 0;
				$last_30_day_bookings_amount = 0;
			}
			$last_30_day_cancellation = Booking::where(['booking_status'=>'Cancelled-Booking'])->where('from_date', '=', date('Y-m-d').' 00:00:00')->get();
			if(isset($last_30_day_cancellation) && count($last_30_day_cancellation) > 0){
				$last_30_day_total_cancellation = count($last_30_day_cancellation);
				$last_30_day_cancellation_amount = 	$last_30_day_cancellation->sum('host_amount');
			}else{
				$last_30_day_total_cancellation = 	0;
				$last_30_day_cancellation_amount = 	0;
			}
		}else if($value == 'last_30_days'){
			$last_30_day_date = \Carbon\Carbon::today()->subDays(30);
			$last_30_day_bookings = Booking::where(['booking_status'=>'Confirmed-by-Host'])->where('from_date', '>=', $last_30_day_date)->get();
			if(isset($last_30_day_bookings) && count($last_30_day_bookings) > 0){
				$last_30_day_total_bookings = count($last_30_day_bookings);
				$last_30_day_bookings_amount = $last_30_day_bookings->sum('host_amount');
			}else{
				$last_30_day_total_bookings = 0;
				$last_30_day_bookings_amount = 0;
			}
			$last_30_day_cancellation = Booking::where(['booking_status'=>'Cancelled-Booking'])->where('from_date', '>=', $last_30_day_date)->get();
			if(isset($last_30_day_cancellation) && count($last_30_day_cancellation) > 0){
				$last_30_day_total_cancellation = count($last_30_day_cancellation);
				$last_30_day_cancellation_amount = 	$last_30_day_cancellation->sum('host_amount');
			}else{
				$last_30_day_total_cancellation = 	0;
				$last_30_day_cancellation_amount = 	0;
			}
		}else if($value == 'this_month'){
			$this_month = \Carbon\Carbon::today()->month;
			// $this_month = \Carbon\Carbon::now()->subMonths(1);
			// dd($this_month);
			// $last_30_day_bookings = Booking::where(['booking_status'=>'Confirmed-by-Host'])->where('from_date', '>', $this_month)->get();
			$last_30_day_bookings = Booking::where(['booking_status'=>'Confirmed-by-Host'])->whereMonth('from_date', date('m'))->whereYear('from_date', date('Y'))
			->get();
			// dd($last_30_day_bookings);
			if(isset($last_30_day_bookings) && count($last_30_day_bookings) > 0){
				$last_30_day_total_bookings = count($last_30_day_bookings);
				$last_30_day_bookings_amount = $last_30_day_bookings->sum('host_amount');
			}else{
				$last_30_day_total_bookings = 0;
				$last_30_day_bookings_amount = 0;
			}
			// dd($last_30_day_total_bookings, $last_30_day_bookings_amount);
			// $last_30_day_cancellation = Booking::where(['booking_status'=>'Cancelled-Booking'])->where('from_date', '>', $last_30_day_date)->get();
			$last_30_day_cancellation = Booking::where(['booking_status'=>'Cancelled-Booking'])->whereMonth('from_date', date('m'))->whereYear('from_date', date('Y'))->get();
			if(isset($last_30_day_cancellation) && count($last_30_day_cancellation) > 0){
				$last_30_day_total_cancellation = count($last_30_day_cancellation);
				$last_30_day_cancellation_amount = 	$last_30_day_cancellation->sum('host_amount');
			}else{
				$last_30_day_total_cancellation = 	0;
				$last_30_day_cancellation_amount = 	0;
			}
		}else if($value == 'last_12_months'){
			// $last_30_day_date = \Carbon\Carbon::today()->subDays(30);
			$last_30_day_bookings = Booking::where(['booking_status'=>'Confirmed-by-Host'])->whereBetween('from_date',[Carbon::now()->subMonth(12), Carbon::now()])
			->get();
			// dd($last_30_day_bookings);
			if(isset($last_30_day_bookings) && count($last_30_day_bookings) > 0){
				$last_30_day_total_bookings = count($last_30_day_bookings);
				$last_30_day_bookings_amount = $last_30_day_bookings->sum('host_amount');
			}else{
				$last_30_day_total_bookings = 0;
				$last_30_day_bookings_amount = 0;
			}
			// dd($last_30_day_total_bookings, $last_30_day_bookings_amount);
			// $last_30_day_cancellation = Booking::where(['booking_status'=>'Cancelled-Booking'])->whereMonth('from_date', date('m'))->whereYear('from_date', date('Y'))->get();
			$last_30_day_cancellation = Booking::where(['booking_status'=>'Cancelled-Booking'])->whereBetween('from_date',[Carbon::now()->subMonth(12), Carbon::now()])
			->get();
			if(isset($last_30_day_cancellation) && count($last_30_day_cancellation) > 0){
				$last_30_day_total_cancellation = count($last_30_day_cancellation);
				$last_30_day_cancellation_amount = 	$last_30_day_cancellation->sum('host_amount');
			}else{
				$last_30_day_total_cancellation = 	0;
				$last_30_day_cancellation_amount = 	0;
			}
		}else if($value == 'this_year'){
			$this_month = \Carbon\Carbon::today()->year;
			// $last_30_day_bookings = Booking::where(['booking_status'=>'Confirmed-by-Host'])->where('from_date', '>', $this_month)->get();
			$last_30_day_bookings = Booking::where(['booking_status'=>'Confirmed-by-Host'])->whereYear('from_date', date('Y'))->get();
			// dd($last_30_day_bookings);
			if(isset($last_30_day_bookings) && count($last_30_day_bookings) > 0){
				$last_30_day_total_bookings = count($last_30_day_bookings);
				$last_30_day_bookings_amount = $last_30_day_bookings->sum('host_amount');
			}else{
				$last_30_day_total_bookings = 0;
				$last_30_day_bookings_amount = 0;
			}
			// dd($last_30_day_total_bookings, $last_30_day_bookings_amount);
			$last_30_day_cancellation = Booking::where(['booking_status'=>'Cancelled-Booking'])->whereYear('from_date', date('Y'))->get();
			if(isset($last_30_day_cancellation) && count($last_30_day_cancellation) > 0){
				$last_30_day_total_cancellation = count($last_30_day_cancellation);
				$last_30_day_cancellation_amount = 	$last_30_day_cancellation->sum('host_amount');
			}else{
				$last_30_day_total_cancellation = 	0;
				$last_30_day_cancellation_amount = 	0;
			}
		}
		$last_30_day_bookings = ['last_30_day_total_bookings'=>$last_30_day_total_bookings, 'last_30_day_bookings_amount'=>$last_30_day_bookings_amount, 'last_30_day_total_cancellation'=>$last_30_day_total_cancellation, 'last_30_day_cancellation_amount'=>$last_30_day_cancellation_amount];
		return $last_30_day_bookings;
	}

	public function change_avg_amount_and_nights(Request $request){
		$input = $request->all();
		$value = $input['value'];
		$startDate = date('Y-m-d');
		// dd($value);
		if($value == 'today'){
			$last_30_day_avg = Booking::select(DB::raw('AVG(host_amount) as avg_amount'))->where(['booking_status'=>'Completed-Booking'])->where('from_date', '=', date('Y-m-d').' 00:00:00')->first();
			// dd($last_30_day_avg->avg_amount);
			if(isset($last_30_day_avg) && !empty($last_30_day_avg->avg_amount)){
				$last_30_day_avg_amt = round($last_30_day_avg->avg_amount,2);
			}else{
				$last_30_day_avg_amt = 0;
			}
			$last_30_day_avg_night = Booking::where('bookings.from_date', '=', date('Y-m-d'))->sum('total_days');
			// $last_30_day_avg_night = Booking::select(DB::raw('DATE(from_date) AS start_date, AVG(TIME_TO_SEC(TIMEDIFF(to_date, from_date))) AS timediff'))->groupBy('start_date')->get();
		}else if($value == 'last_30_days'){
			$last_30_day_date = \Carbon\Carbon::today()->subDays(30);
			$last_30_day_avg = Booking::select(DB::raw('AVG(host_amount) as avg_amount'))->where(['booking_status'=>'Completed-Booking'])->where('from_date', '>=', $last_30_day_date)->first();
			// dd($last_30_day_avg->avg_amount);
			if(isset($last_30_day_avg) && !empty($last_30_day_avg->avg_amount)){
				$last_30_day_avg_amt = round($last_30_day_avg->avg_amount,2);
			}else{
				$last_30_day_avg_amt = 0;
			}
			$last_30_day_avg_night1 = Booking::whereBetween('bookings.from_date', [$last_30_day_date, $startDate])->sum('total_days');
			$last_30_day_avg_night_new = $last_30_day_avg_night1 / 30;
			$last_30_day_avg_night = round($last_30_day_avg_night_new,0);
			// $last_30_day_avg_night = Booking::select(DB::raw('DATE(from_date) AS start_date, AVG(TIME_TO_SEC(TIMEDIFF(to_date, from_date))) AS timediff'))->groupBy('start_date')->get();
		}else if($value == 'this_month'){
			$this_month = \Carbon\Carbon::today()->month;
			$last_30_day_avg = Booking::select(DB::raw('AVG(host_amount) as avg_amount'))->where(['booking_status'=>'Completed-Booking'])->whereMonth('from_date', date('m'))->whereYear('from_date', date('Y'))->first();
			// dd($last_30_day_avg->avg_amount);
			if(isset($last_30_day_avg) && !empty($last_30_day_avg->avg_amount)){
				$last_30_day_avg_amt = round($last_30_day_avg->avg_amount,2);
			}else{
				$last_30_day_avg_amt = 0;
			}
			$last_30_day_avg_night1 = Booking::whereMonth('from_date', date('m'))->sum('total_days');
			$current_month = date('m');
			$current_year = date('Y');
			$month_total_days = cal_days_in_month(CAL_GREGORIAN, $current_month, $current_year); // 31
			$last_30_day_avg_night_new = $last_30_day_avg_night1 / $month_total_days;
			$last_30_day_avg_night = round($last_30_day_avg_night_new,0);
			// $last_30_day_avg_night = Booking::select(DB::raw('DATE(from_date) AS start_date, AVG(TIME_TO_SEC(TIMEDIFF(to_date, from_date))) AS timediff'))->groupBy('start_date')->get();
		}else if($value == 'last_12_months'){
			$last_30_day_avg = Booking::select(DB::raw('AVG(host_amount) as avg_amount'))->where(['booking_status'=>'Completed-Booking'])->whereBetween('from_date',[Carbon::now()->subMonth(12), Carbon::now()])->first();
			if(isset($last_30_day_avg) && !empty($last_30_day_avg->avg_amount)){
				$last_30_day_avg_amt = round($last_30_day_avg->avg_amount,2);
			}else{
				$last_30_day_avg_amt = 0;
			}
			$last_30_day_avg_night1 = Booking::whereBetween('from_date',[Carbon::now()->subMonth(12), Carbon::now()])->sum('total_days');
			$last_30_day_avg_night_new = $last_30_day_avg_night1 / 365;
			$last_30_day_avg_night = round($last_30_day_avg_night_new,0);
			// $last_30_day_avg_night = Booking::select(DB::raw('DATE(from_date) AS start_date, AVG(TIME_TO_SEC(TIMEDIFF(to_date, from_date))) AS timediff'))->groupBy('start_date')->get();
		}else if($value == 'this_year'){
			$this_month = \Carbon\Carbon::today()->year;
			$last_30_day_avg = Booking::select(DB::raw('AVG(host_amount) as avg_amount'))->where(['booking_status'=>'Completed-Booking'])->whereYear('from_date', date('Y'))->first();
			if(isset($last_30_day_avg) && !empty($last_30_day_avg->avg_amount)){
				$last_30_day_avg_amt = round($last_30_day_avg->avg_amount,2);
			}else{
				$last_30_day_avg_amt = 0;
			}
			$last_30_day_avg_night1 = Booking::whereYear('from_date', date('Y'))->sum('total_days');
			$now = time(); // or your date as well
			$your_date = date('Y-01-01');
			$datediff = $now - $your_date;
			$total_days = round($datediff / (60 * 60 * 24));
			$last_30_day_avg_night_new = $last_30_day_avg_night1 / $total_days;
			$last_30_day_avg_night = round($last_30_day_avg_night_new,0);
			// $last_30_day_avg_night = Booking::select(DB::raw('DATE(from_date) AS start_date, AVG(TIME_TO_SEC(TIMEDIFF(to_date, from_date))) AS timediff'))->groupBy('start_date')->get();
		}
		$last_30_day_bookings = ['last_30_day_avg_amt'=>$last_30_day_avg_amt, 'last_30_day_avg_night'=>$last_30_day_avg_night];
		return $last_30_day_bookings;
	}

	public function change_admin_commission(Request $request){
		$input = $request->all();
		$value = $input['value'];
		$startDate = date('Y-m-d');
		// dd($value);
		if($value == 'last_24_hours'){
			$last_24hour_admin_commission = Booking::where("created_at",">",Carbon::now()->subDay(1))->where("created_at","<",Carbon::now())->where(['booking_status'=>'Confirmed-by-Host'])->whereNotIn('bookings.booking_status',['Cancelled-Booking'])->sum('admin_amount');
		}else if($value == 'this_week'){
			// $model = Booking::orderby('created_at','desc')->first();
			// dd($model);
			// $testDate=$model->created_at;
			$testDate=Carbon::now();
			$from = $testDate->startOfWeek()->format('Y-m-d H:i');
			$to = $testDate->endOfWeek()->format('Y-m-d H:i');
			// dd($testDate, $from, $to);
			$last_24hour_admin_commission = Booking::whereBetween('created_at', [$from, $to])->where(['booking_status'=>'Confirmed-by-Host'])->whereNotIn('bookings.booking_status',['Cancelled-Booking'])->sum('admin_amount');
			// dd($last_24hour_admin_commission);
		}else if($value == 'this_month'){
			$last_24hour_admin_commission = Booking::whereMonth('created_at', date('m'))->where(['booking_status'=>'Confirmed-by-Host'])->whereYear('created_at', date('Y'))->whereNotIn('bookings.booking_status',['Cancelled-Booking'])->sum('admin_amount');
		}else if($value == 'this_year'){
			$last_24hour_admin_commission = Booking::whereYear('created_at', date('Y'))->where(['booking_status'=>'Confirmed-by-Host'])->whereNotIn('bookings.booking_status',['Cancelled-Booking'])->sum('admin_amount');
		}else if($value == 'lifetime'){
			$last_24hour_admin_commission = Booking::where(['booking_status'=>'Confirmed-by-Host'])->whereNotIn('bookings.booking_status',['Cancelled-Booking'])->sum('admin_amount');
		}
		return $last_24hour_admin_commission;
	}

	public function booking_change(Request $request){
		$input = $request->all();
		$value = $input['value'];
		// dd($value);
		if($value == 'this_year'){
			$period = now()->subMonths(12)->monthsUntil(now());
			$monthWiseDates = [];
			$monthWiseAmount = [];
			$monthDates = [];
			foreach ($period as $date)
			{
				$monthWiseDates[] = $date->shortMonthName.'-'.$date->year;
				$monthDates[] = [
					'month' => $date->month,
					'year' => $date->year,
				];
				$total_earning = Booking::select('*')->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->sum('total_amount');
				$total_causion = Booking::select('*')->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->sum('security_deposite');
				$optional_service_amount = Booking::select('*')->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->sum('optional_service_amount');
				if($total_causion){
					$total_earning = $total_earning - $total_causion;
				}
				$total_earning = $total_earning + $optional_service_amount;
				$monthWiseAmount[] = number_format($total_earning / 1000000, 3) . 'M';
			}
			$last_12_months_list = json_encode($monthWiseDates);
			$last_12_months_amount = json_encode($monthWiseAmount);
		}else if($value == 'last_month'){
			$month_ini = new DateTime("first day of last month");
			$month_end = new DateTime("last day of last month");
			$last_month_first_day = $month_ini->format('Y-m-d');
			$last_month_last_day = $month_end->format('Y-m-d');
			
			$monthWiseDates = [];
			$monthWiseAmount = [];
			$monthDates = [];
			for($i = $last_month_first_day; $i <= $last_month_last_day; $i++)
			{
				$monthWiseDates[] = date('M-d', strtotime($i));
				$total_earning = [];
				$total_earning = Booking::select('*')->whereDate('created_at','=', $i)->pluck('total_amount')->first();
				$total_causion = Booking::select('*')->whereDate('created_at','=', $i)->pluck('security_deposite')->first();
				$optional_service_amount = Booking::select('*')->whereDate('created_at','=', $i)->pluck('optional_service_amount')->first();
				if($total_causion){
					$total_earning = $total_earning - $total_causion;
				}
				$total_earning = $total_earning + $optional_service_amount;
				$monthWiseAmount[] = number_format($total_earning / 1000000, 2) . 'M';
			}
			$last_12_months_list = json_encode($monthWiseDates);
			$last_12_months_amount = json_encode($monthWiseAmount);
		}else if($value == 'this_month'){
			$last_month_first_day = date('Y-m-01'); // hard-coded '01' for first day
			$last_month_last_day  = date('Y-m-t');
			
			$monthWiseDates = [];
			$monthWiseAmount = [];
			$monthDates = [];
			for($i = $last_month_first_day; $i <= $last_month_last_day; $i++)
			{
				$monthWiseDates[] = date('M-d', strtotime($i));
				$total_earning = [];
				$total_earning = Booking::select('*')->whereDate('created_at','=', $i)->pluck('total_amount')->first();
				$total_causion = Booking::select('*')->whereDate('created_at','=', $i)->pluck('security_deposite')->first();
				$optional_service_amount = Booking::select('*')->whereDate('created_at','=', $i)->pluck('optional_service_amount')->first();
				if($total_causion){
					$total_earning = $total_earning - $total_causion;
				}
				$total_earning = $total_earning + $optional_service_amount;
				$monthWiseAmount[] = number_format($total_earning / 1000000, 2) . 'M';
				// echo '<pre>'; print_r($monthWiseAmount);
			}
			$last_12_months_list = json_encode($monthWiseDates);
			$last_12_months_amount = json_encode($monthWiseAmount);
		}
		// $last_data = ['last_12_months_list'=>$last_12_months_list, 'last_12_months_amount'=>$last_12_months_amount];
		// return $last_data;
      	return view('admin.dashboard2',compact('last_12_months_list','last_12_months_amount'));
	}

}
