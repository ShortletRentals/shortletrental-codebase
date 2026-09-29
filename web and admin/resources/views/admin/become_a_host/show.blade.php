@extends('layouts.master')
@section('content')
<?php 
use App\Models\Province;
use App\Models\Country;
use App\Models\City;
use App\Models\Area;

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
                            <h5 class="dataLabel">{{strtoupper($title)}} DETAILS</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <!-- <div class="view-list">
                                        <h5>Host ID : </h5>
                                        <h6><b>{{ isset($data) && !empty($data->unique_id) ? $data->unique_id : '-' }}</b></h6>
                                    </div> -->
                                    <div class="view-list">
                                        <h5>Host Name : </h5>
                                        <h6>@if(isset($data->title) && !empty($data->title))
                                             {!! ucwords($data->title).' '. ucwords($data->name) !!}
                                            @else
                                             {{ isset($data->name) ? ucwords($data->name) : '' }}
                                            @endif
                                        </h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Surname : </h5>
                                        <h6>{{ isset($data) && !empty($data->surname) ? $data->surname : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>How did you hear about Us : </h5>
                                        <h6>{{ isset($data) && !empty($data->hear_about_us) ? $data->hear_about_us : '' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Hosting Type : </h5>
                                        <h6>{{ isset($data) && !empty($data->hosting_type) ? $data->hosting_type : '-' }}</h6>
                                    </div>
                                    <div class="view-list view-desc">
                                        <h5>Remarks : </h5>
                                        <h6>{!! isset($data->remarks) ? ucwords($data->remarks) : '' !!}</h6>
                                    </div>
                                </div>
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Image : </h5>
                                        @if(isset($data->image) && !empty($data->image))
                                            <h6><img src="{{ $data->image }}" class="logo-icon" alt="logo icon"></h6>
                                        @else
                                            <h6>-</h6>
                                        @endif
                                    </div>
                                    @if(isset($data->hosting_type) && $data->hosting_type == 'Business')
                                    <div class="view-list">
                                        <h5>Business Name : </h5>
                                        <h6>{{ isset($data) && !empty($data->hosting_type) ? $data->hosting_type : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Business Registration Image : </h5>
                                        <h6><img src="{{ $data->business_registration_image }}" class="logo-icon" alt="logo icon"></h6>
                                    </div>
                                    @endif
                                    <div class="view-list">
                                        <h5>Date of birth : </h5>
                                        <h6>{{ isset($data) && !empty($data->dob) ? date('d-m-Y', strtotime($data->dob)) : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Above information are correct or not : </h5>
                                        <h6>{{ isset($data) && !empty($data->information_correct_or_not) ? $data->information_correct_or_not : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Gender : </h5>
                                        <h6>{{ isset($data) && !empty($data->gender) ? ucfirst($data->gender) : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Marital Status : </h5>
                                        <h6>{{ isset($data) && !empty($data->marital_status) ? ucfirst($data->marital_status) : '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="view_table">
                            <h5 class="dataLabel">CONTACT</h5>
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
                                        <h6>{{ isset($data) && !empty($data->second_mobile) ? '+'.$data->second_country_code : '' }} - {{ isset($data) && !empty($data->second_mobile) ? $data->second_mobile : '' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="view_table">
                            <h5 class="dataLabel">ADDRESS</h5>
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

                                    <?php 
                                    /*
                                     <div class="view-list">
                                        <h5>Street : </h5>
                                        <h6>{{ isset($data) && !empty($data->street) ? $data->street : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Landmark : </h5>
                                        <h6>{{ isset($data) && !empty($data->landmark) ? $data->landmark : '-' }}</h6>
                                    </div> 
                                     */
                                    ?>
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
                                        <h5>Address : </h5>
                                        <h6>{{ isset($data) && !empty($data->address) ? $data->address : '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="view_table">
                            <h5 class="dataLabel">DOCUMENTATION</h5>
                            <div class="view_inner">
                                <div class="table-half">
                                    <div class="view-list">
                                        <h5>Document number : </h5>
                                        <h6>{{ isset($data) && !empty($data->document_number) ? $data->document_number : '-' }}</h6>
                                    </div>
                                    <div class="view-list">
                                        <h5>Document Image : </h5>
                                        @if(isset($data->document_image) && !empty($data->document_image))
                                        ex
                                            <h6>
                                                <a href="{{ $data->document_image }}" target="_blank">
                                                <?php /*<img src="{{ $data->document_image }}"  class="logo-icon" alt="Document Image"> */?>
                                                {{$data->document_image}}
                                            </a>
                                        </h6>
                                        @else
                                            <h6>-</h6>
                                        @endif
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
                                        <h6>{{ isset($bank_data) && !empty($bank_data->method_of_payment) ? $bank_data->method_of_payment : '-' }}</h6>
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



            </div>            
        </div>


        <div class="row">
            <div class="col-md-12 col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="view_table">
                            <h5>List of Accommodations</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>STATUS</th>
                                            <th>CODE</th>
                                            <th>ACCOMMODATION</th>
                                            <th>TYPE</th>
                                            <th>CITY</th>
                                            <th>CONTRACT</th>
                                        </tr>
                                        @if(isset($accommodations) && count($accommodations) > 0 )
                                            @foreach($accommodations as $accommodation)
                                            <tr>
                                                <td>
                                                    @if(isset($accommodation) && $accommodation->status == 1 )
                                                        <!-- <span style="align:center;"><i class="bx bx-check"></i></span> -->
                                                        <span style="align:center;">Active</span>
                                                    @else
                                                        <!-- <span style="align:center;"><i class="bx bx-cross"></i></span> -->
                                                        <span style="align:center;">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>{{ isset($accommodation) && !empty($accommodation->code) ? $accommodation->code : '-' }}</td>
                                                <td>{{ isset($accommodation) && !empty($accommodation->title) ? $accommodation->title : '-' }}</td>
                                                <td>{{ isset($accommodation) && !empty($accommodation->type) ? str_replace('_',' ',$accommodation->type) : '-' }}</td>
                                                <td>{{ isset($accommodation) && !empty($accommodation->city_name) ? $accommodation->city_name : '-' }}</td>
                                                <td>@if(isset($accommodation) && $accommodation->contract != Null )
                                                        <a href="{{ $accommodation->contract }}" target="_blank" class="contract_cls">See Contract</a>
                                                    @else
                                                        {{'-'}}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                        <tr>
                                            <td colspan="6" style="text-align:center;">No Accommodations found.</td>
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
<!--end page wrapper -->

@endsection
