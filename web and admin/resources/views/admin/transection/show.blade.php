@extends('layouts.master')
@section('content')
<!--start page wrapper -->

<?php
    use App\Models\User;

    $user_type = User::where('id', auth()->id() )->pluck('user_type')->first();

    $fdate = $data->to_date;
    $tdate = $data->from_date;
    $datetime1 = new DateTime($fdate);
    $datetime2 = new DateTime($tdate);
    $interval = $datetime1->diff($datetime2);
    $days1 = $interval->format('%a');
    if(isset($days1) && $days1 != 0){
        $days = $days1;
    }else{
        $days = 0;
    }
?>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{$title}}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.'.$page.'.index')}}">{{$title}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="margin-cls border border-3 p-4 rounded"> 
                            <div class="view_table">
                                <h5 class="dataLabel">{{$title}} DATA</h5>
                                <div class="view_inner">
                                    <div class="table-half">
                                        <!-- <div class="view-list">
                                            <h5>Booking ID</h5>
                                            <h6><b>{!! $data->booking_id!!}</b></h6>
                                        </div> -->
                                        <div class="view-list">
                                            <h5>Accommodation : </h5>
                                            <h6>{{ isset($data) && !empty($data->property_details->title) ? $data->property_details->title : '-' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Check-in date : </h5>
                                            <h6>{{ isset($data) && !empty($data->from_date) ? date('d/m/Y', strtotime($data->from_date)) : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Check-out date : </h5>
                                            <h6>{{ isset($data) && !empty($data->to_date) ? date('d/m/Y', strtotime($data->to_date)) : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Host : </h5>
                                            <div class="view-list view-host">
                                                <h5>{{ $data->host_name }}</h5>
                                                <div class="view-list">
                                                    <h6>Email:</h6> 
                                                    <h6>{{ $data->host_email }}</h6>
                                                </div>
                                                <div class="view-list">
                                                    <h6>Telephone:</h6> 
                                                    <h6>{{ $data->host_mobile }}{{ isset($data) && !empty($data->host_second_mobile) ? ' - '.$data->host_second_mobile : '' }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="view-list">
                                            <h5>Guest Personal Details: </h5>
                                            <div class="view-list view-host">
                                                <h5>{{ $data->personal_first_name }} {{ $data->personal_last_name }}</h5>
                                                <div class="view-list">
                                                    <h6>Email:</h6> <h6>{{ $data->personal_email }}</h6>
                                                </div>
                                                <div class="view-list">
                                                    <h6>Telephone:</h6> <h6>{{ $data->personal_phone_number }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="view-list">
                                            <h5>Main Guest : </h5>
                                            @if(isset($data->book_for) && $data->book_for == 'other')
                                                <div class="view-list view-host">
                                                    <h5>{{ $data->guest_first_name }} {{ $data->guest_last_name }}</h5>
                                                    <div class="view-list">
                                                        <h6>Email:</h6> <h6>{{ $data->guest_email }}</h6>
                                                    </div>
                                                    <div class="view-list">
                                                        <h6>Telephone:</h6> <h6>{{ $data->guest_phone_number }}</h6>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="view-list view-host">
                                                    <h5>{{ ucwords($data->book_for) }}</h5>
                                                </div>
                                            @endif
                                        </div>
                                        @if(isset($data->is_guest) && $data->is_guest != 1)


                                        <div class="view-list">
                                            <h5>Customer Details : </h5>
                                            <div class="view-list view-host">
                                                <h5>{{ $data->guest_name.' ('.$data->guest_id.')' }}</h5>
                                                <div class="view-list">
                                                    <h6>Email:</h6> <h6>{{ $data->guest_email_address }}</h6>
                                                </div>
                                                @if(isset($data->guest_mobile) && !empty($data->guest_mobile))
                                                <div class="view-list">
                                                    <h6>Telephone:</h6> <h6>{{ $data->guest_mobile }}{{ isset($data) && !empty($data->guest_second_mobile) ? ' - '.$data->guest_second_mobile : '' }}</h6>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        <div class="view-list">
                                            <h5>Number of guests : </h5>
                                            <div class="view-list view-host">
                                                @if(isset($data) && $data->no_of_children_guest != Null )
                                                <div class="view-list">
                                                    <h6>Adults:</h6> <h6>{{ $data->no_of_adult_guest }}</h6>
                                                </div>
                                                @else
                                                <div class="view-list">
                                                    <h6> - </h6>
                                                </div>
                                                @endif
                                                    @if(isset($data) && $data->no_of_children_guest != Null )
                                                <div class="view-list">
                                                        <h6>Children:</h6> <h6>{{ $data->no_of_children_guest }}</h6>
                                                </div>
                                                    @endif
                                                    @if(isset($data) && $data->no_of_babies_guest != Null )
                                                <div class="view-list">
                                                        <h6>Babies:</h6> <h6>{{ $data->no_of_babies_guest }}</h6>
                                                </div>
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-half">
                                        <div class="view-list">
                                            <h5>Booking Type : </h5>
                                            <h6>{{ isset($data) && !empty($data->booking_type) ? str_replace('_',' ',$data->booking_type) : '' }}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Booking Date : </h5>
                                            <h6>{!! date('d-m-Y', strtotime($data->created_at)) !!}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Nights : </h5>
                                            <h6>{{ $days }} nights</h6>
                                        </div>
                                        <!-- <div class="view-list">
                                            <h5>Stage</h5>
                                            <h6>{!! $data->stage !!}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Rental</h5>
                                            <h6>{!! $data->rental !!}</h6>
                                        </div>
                                        <div class="view-list">
                                            <h5>Booking Status</h5>
                                            <h6>{!! $data->booking_status !!}</h6>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!empty($data->property_details->getExtraService)  && count($data->property_details->getExtraService) > 0)
                        <div class="card-body pb-0">
                            <div class="margin-cls border border-3 p-4 rounded"> 
                                <div class="view_table">
                                    <h5 class="dataLabel">EXTRA SERVICE</h5>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sno.</th>
                                                    <th>Name</th>
                                                    <th>Price(NGN)</th>
                                                </tr>
                                                @foreach($data->property_details->getExtraService as $k=>$extraService)
                                                @if ($extraService->getServiceData && ucwords($extraService->getServiceData->name) != 'Caution Fee')
                                                    <tr>
                                                        <td><b>{{$k+1}}</b></td>
                                                        <td>{{ ucwords($extraService->getServiceData->name) }}</td>
                                                        <td>{{ $extraService->getServiceData->price }}</td>
                                                        
                                                    </tr>
                                                @endif
                                                 @endforeach 
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="card-body pb-0">
                        <div class="margin-cls border border-3 p-4 rounded"> 
                            <div class="view_table">
                                <h5 class="dataLabel">AMOUNTS</h5>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Concept</th>
                                                <th>Price (Taxes included)</th>
                                                <th>Quantity</th>
                                                <th>Tax</th>
                                                <th>Price(NGN)</th>
                                            </tr>
                                            <tr>
                                                <td><b>Accommodation</b></td>
                                                <td>{{ isset($data) && !empty($data->property_details->price) ? $data->property_details->price : '0.00' }}</td>
                                                <td>{{ $days }} nights</td>
                                                <td>{{ isset($data) && !empty($data->property_details->tax) ? $data->property_details->tax : '0' }}%</td>
                                                @if(isset($days) && $days != 0)
                                                    <td>{{ $data->property_details->price * $days }}</td>
                                                @else
                                                    <td>{{ $data->property_details->price }}</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td colspan="4" align="right">Caution Fee</td>
                                                @if(isset($data->security_deposite) && $data->security_deposite != 0)
                                                    <td>{{ $data->security_deposite }}</td>
                                                @else
                                                    <td>0</td>
                                                @endif
                                            </tr>
                                            @if(isset($data->selected_options) && !empty($data->selected_options))
                                            <?php $extra_services = json_decode($data->selected_options) ?>
                                            <?php if(count($extra_services) > 0){
                                                foreach($extra_services as $service){
                                                ?>
                                                <tr>
                                                    <td colspan="4" align="right">{{ $service->name }}</td>
                                                    @if(isset($service->value) && $service->value != 0)
                                                        <td>{{ $service->value }}</td>
                                                    @else
                                                        <td>{{ 0 }}</td>
                                                    @endif
                                                </tr>
                                                <?php } ?>
                                            <?php } ?>
                                            @endif
                                            @if(isset($data->discount_amount) && !empty($data->discount_amount))
                                            <tr>
                                                <td colspan="4" align="right">Discount Amount</td>
                                                @if(isset($data->discount_amount) && $data->discount_amount != 0)
                                                    <td>{{ $data->discount_amount }}</td>
                                                @else
                                                    <td>0</td>
                                                @endif
                                            </tr>
                                            @endif
                                            <?php if(isset($data->property_details) && !empty($data->property_details)){
                                                $property_amount = $data->property_details->price * $days;
                                            }else{
                                                $property_amount = 0;
                                            }  ?>
                                            <tr>
                                                <td colspan="4" align="right"><b>GRAND TOTAL</b></td>
                                                @if(isset($days) && $days != 0)
                                                    <td>{{ $property_amount + $data->optional_service_amount + $data->security_deposite - $data->discount_amount }}</td>
                                                @else
                                                    <td>{{ isset($data->property_details) && !empty($data->property_details) ? $data->property_details->price : '' }}</td>
                                                @endif
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($user_type == 1 || $user_type == 2)
                    <div class="card-body pb-0">
                        <div class="view_table">
                            <div class="margin-cls border border-3 p-4 rounded"> 
                                <h5 class="dataLabel">PAYMENT DETAILS</h5>
                                <h6>Operations</h6>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>DATE</th>
                                                <th>AMOUNT(NGN)</th>
                                                <th>STATE</th>
                                                <th>TYPE OF PAYMENT</th>
                                            </tr>
                                            <tr>
                                                <td>{!! date('d-m-Y', strtotime($data->created_at)) !!}</td>
                                                @if(isset($days) && $days != 0)
                                                    <td>{{ $data->property_details->price * $days }}</td>
                                                @else
                                                    <td>{{ $data->property_details->price }}</td>
                                                @endif
                                                <td>Pending</td>
                                                <td>Bank transfer</td>
                                            <!-- <tr>
                                                <td><b>Extras/Services</b></td>
                                                <td>{{ isset($data) && !empty($data->total_amount) ? $data->total_amount : '0.00' }}</td>
                                                <td>{{ $days }} nights</td>
                                                <td>0%</td>
                                                <td></td>
                                            </tr> -->
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>            
        </div>


    </div>
</div>
</div>

<!--end page wrapper -->

@endsection
