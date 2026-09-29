@extends('layouts.master')
@section('content')
<?php 
use App\Models\Province;
use App\Models\Country;
use App\Models\City;
use App\Models\Area;
use App\Models\Booking;

if(isset($data) && !empty($data->country_id)){
    $country_name = Country::where('id',$data->country_id)->pluck('name')->first();
}else{
    $country_name = '';
}
if(isset($data) && !empty($data->province_id)){
    $province_name = Province::where('id',$data->province_id)->pluck('name')->first();
}else{
    $province_name = '';
}
if(isset($data) && !empty($data->city_id)){
    $city_name = City::where('id',$data->city_id)->pluck('name')->first();
}else{
    $city_name = '';
}
if(isset($data) && !empty($data->area)){
    $area_name = Area::where('id',$data->area)->pluck('name')->first();
}else{
    $area_name = '';
}
?>
<!--start page wrapper -->
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
                    <div class="card-body">
                        <div class="view_table">
                            <h5>PARTNER DETAILS</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <!-- <div class="view-list">
                                        <h5>Influencer ID</h5>
                                        <h6><b>{{ isset($data) && !empty($data->unique_id) ? $data->unique_id : '-' }}</b></h6>
                                    </div> -->
                                    <div class="view-list">
                                        <h5>Name : </h5>
                                        <h6>{!! str_replace('_',' ',$data->title).' '. ucwords($data->name) !!}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Image : </h5>
                                        <h6><a href="{{ $data->image }}" target="_blank"><img src="{{ $data->image }}" class="logo-icon" alt="logo icon"></a></h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Gender : </h5>
                                        <h6>{{ isset($data) && !empty($data->gender) ? ucwords($data->gender) : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Date of birth : </h5>
                                        <h6>{{ isset($data) && !empty($data->dob) ? date('d-m-Y', strtotime($data->dob)) : '-' }}</h6>
                                    </div>
                                    <div class="view-list view-desc">
                                        <h5>Remarks : </h5>
                                        <h6>{!! ucwords($data->remarks) !!}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Surname : </h5>
                                        <h6>{{ isset($data) && !empty($data->surname) ? ucwords($data->surname) : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Marital Status : </h5>
                                        @if(isset($data->marital_status) && $data->marital_status == 'Married')
                                            <h6>{{ isset($data) && !empty($data->marital_status) ? ucwords($data->marital_status) : '-' }}</h6>
                                        @else
                                            <h6>{{ isset($data) && !empty($data->marital_status) ? ucwords($data->marital_status) : '-' }}</h6>
                                        @endif
                                    </div>
                                    <div class="view-list">
                                        <h5>Commission : </h5>
                                        <h6>{{ isset($data) && !empty($data->commission) ? $data->commission.'%' : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>How did you hear about us : </h5>
                                        <h6>{{ isset($data) && !empty($data->hear_about_us) ? $data->hear_about_us : '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="view_table">
                            <h5>CONTACT</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Email : </h5>
                                        <h6>{{ isset($data) && !empty($data->email) ? $data->email : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Mobile Number : </h5>
                                        <h6>{{ isset($data) && !empty($data->country_code) ? '+'.$data->country_code : '' }} - {{ isset($data) && !empty($data->mobile) ? $data->mobile : '' }}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Secondary Email : </h5>
                                        <h6>{{ isset($data) && !empty($data->secondary_email) ? $data->secondary_email : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>2nd Phone Number : </h5>
                                        @if(isset($data->second_mobile) && !empty($data->second_mobile) )
                                            <h6>{{ isset($data) && !empty($data->second_country_code) ? $data->second_country_code : '' }} - {{ isset($data) && !empty($data->second_mobile) ? $data->second_mobile : '' }}</h6>
                                        @else
                                            <h6>-</h6>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="view_table">
                            <h5>ADDRESS</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Country : </h5>
                                        <h6>{{ isset($country_name) && !empty($country_name) ? $country_name : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Province : </h5>
                                        <h6>{{ isset($province_name) && !empty($province_name) ? $province_name : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>City : </h5>
                                        <h6>{{ isset($city_name) && !empty($city_name) ? $city_name : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Area : </h5>
                                        <h6>{{ isset($area_name) && !empty($area_name) ? $area_name : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Street : </h5>
                                        <h6>{{ isset($data) && !empty($data->street) ? $data->street : '-' }}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Street Number : </h5>
                                        <h6>{{ isset($data) && !empty($data->street_number) ? $data->street_number : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Number : </h5>
                                        <h6>{{ isset($data) && !empty($data->number) ? $data->number : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Postal Code : </h5>
                                        <h6>{{ isset($data) && !empty($data->postal_code) ? $data->postal_code : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Landmark : </h5>
                                        <h6>{{ isset($data) && !empty($data->landmark) ? $data->landmark : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Address : </h5>
                                        <h6>{{ isset($data) && !empty($data->address) ? $data->address : '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="view_table">
                            <h5>DOCUMENTATION</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Document number : </h5>
                                        <h6>{{ isset($data) && !empty($data->document_number) ? $data->document_number : '-' }}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Document Image : </h5>
                                        <h6><a href="{{ $data->document_image }}" target="_blank"><img src="{{ $data->document_image }}" class="logo-icon" alt="logo icon"></a></h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>ID Card Image : </h5>
                                        <h6><a href="{{ $data->id_card_image }}" target="_blank"><img src="{{ $data->id_card_image }}" class="logo-icon" alt="logo icon"></a></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>            
        </div>
        <div class="card">
                    <div class="card-header">
                    <h5>BANK DETAILS</h5>
                    </div>
                    <div class="card-body">
                        <div class="view_table">
                            <div class="view_inner">
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Method of Payment : </h5>
                                        <h6>{{ isset($bank_data) && !empty($bank_data->method_of_payment) ? str_replace('_',' ',$bank_data->method_of_payment) : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Name of account holder : </h5>
                                        <h6>{{ isset($bank_data) && !empty($bank_data->account_holder_name) ? $bank_data->account_holder_name : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Account number : </h5>
                                        <h6>{{ isset($bank_data) && !empty($bank_data->account_number) ? $bank_data->account_number : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>IBAN : </h5>
                                        <h6>{{ isset($bank_data) && !empty($bank_data->iban) ? $bank_data->iban : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Intra Community VAT number : </h5>
                                        <h6>{{ isset($bank_data) && !empty($bank_data->vat_number) ? $bank_data->vat_number : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Fiscal code : </h5>
                                        <h6>{{ isset($bank_data) && !empty($bank_data->fiscal_code) ? $bank_data->fiscal_code : '-' }}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Invoicing type : </h5>
                                        <h6>{{ isset($bank_data) && !empty($bank_data->invoicing_type) ? $bank_data->invoicing_type : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Retention (%) : </h5>
                                        <h6>{{ isset($bank_data) && !empty($bank_data->retention) ? $bank_data->retention : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Ledger Account : </h5>
                                        <h6>{{ isset($bank_data) && !empty($bank_data->ledger_account) ? $bank_data->ledger_account : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>BIC Swift : </h5>
                                        <h6>{{ isset($bank_data) && !empty($bank_data->bic_swift) ? $bank_data->bic_swift : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Bank Name : </h5>
                                        <h6>{{ isset($bank_data) && !empty($bank_data->bank_name) ? $bank_data->bank_name : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>CNAE Code : </h5>
                                        <h6>{{ isset($bank_data) && !empty($bank_data->cnae_code) ? $bank_data->cnae_code : '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="view_table">
                            <h5 class="dataLabel">List of Discounts</h5>
                            <div class="view_inner d-block">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Percentage</th>
                                                <th>Category</th>
                                                <th>Validity</th>
                                                <th>Total Customer Used</th>
                                                <th>Total Coupon</th>
                                                <th>No of use by single guest</th>
                                                <th>Status</th>
                                                <th>Added Date</th>
                                            </tr>
                                            
                                                @if(isset($all_discounts) && count($all_discounts) > 0 )
                                                    @foreach($all_discounts as $discounts)
                                                    <?php 
                                                        $validity = '';
                                                        if(isset($discounts->start_date) && $discounts->end_date != Null){
                                                            $validity = date('d M Y', strtotime($discounts->start_date)) .' - '.date('d M Y', strtotime($discounts->end_date));
                                                        }else{
                                                            $validity = date('d M Y', strtotime($discounts->start_date));
                                                        }
                                                        $total_customer_use = Booking::where(['coupon_code'=>$discounts->code])->count();
                                                    ?>
                                                    <tr>
                                                        <td>{{ isset($discounts) && !empty($discounts->code) ? $discounts->code : '-' }}</td>
                                                        <td>{{ isset($discounts) && !empty($discounts->percentage) ? $discounts->percentage : '-' }}</td>
                                                        <td>{{ isset($discounts) && !empty($discounts->name) ? $discounts->name : '-' }}</td>
                                                        <td>{{ $validity }}</td>
                                                        <td>{{ $total_customer_use }}</td>
                                                        <td>{{ isset($discounts) && !empty($discounts->total_use) ? $discounts->total_use : '-' }}</td>
                                                        <td>{{ isset($discounts) && !empty($discounts->total_single_use) ? $discounts->total_single_use : '-' }}</td>
                                                        <td>@if(isset($discounts) && $discounts->status == 1 )
                                                                Activated
                                                            @else
                                                                Deactivated
                                                            @endif
                                                        </td>
                                                        <td>{{ isset($discounts) && !empty($discounts->created_at) ? date('d-M-Y', strtotime($discounts->created_at)) : '-' }}</td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="9" style="text-align:center;">No Discount found.</td>
                                                </tr>
                                                @endif
                                            
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>            
        </div>

    </div>
</div>
</div>
<!--end page wrapper -->

@endsection
