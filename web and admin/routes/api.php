<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group([
    'middleware' => ['throttle:250,1', 'api', 'language'],
    'prefix' => 'auth'

], function ($router) {
    Route::post('notificationOnOff', 'AuthController@notificationOnOff');
    Route::post('getContactDetails', 'AuthController@getContactDetails');
    Route::post('updateContactDetails', 'AuthController@updateContactDetails');
    Route::post('getAllBlogs', 'AuthController@getAllBlogs');
    Route::post('getBlogDetails', 'AuthController@getBlogDetails');
    Route::post('verify-otp', 'AuthController@otpVerify');
    Route::post('send-otp', 'AuthController@sendOtp');
    Route::post('partner-signup', 'AuthController@partner_signup');
    Route::post('signUp', 'AuthController@signUp');
    Route::post('login', 'AuthController@login');
    Route::post('host_login', 'AuthController@host_login');
    Route::post('mobileSocialLogin', 'AuthController@mobileSocialLogin');
    Route::post('HostLogin', 'AuthController@HostLogin');
    Route::post('logout', 'AuthController@logout');
    Route::get('user-profile', 'AuthController@userProfile');
    Route::get('royalty-points', 'AuthController@royalty_points');
    Route::post('update-profile', 'AuthController@updateProfile');
    Route::post('change-password', 'AuthController@changePassword');
    Route::post('update-password', 'AuthController@updatePassword');
    Route::post('becomeahost_signUp', 'AuthController@becomeahost_signUp');
    Route::post('customer_signUp', 'AuthController@customer_signUp');
    Route::post('getHomeData', 'AuthController@getHomeData');
    Route::post('getHomeData1', 'AuthController@getHomeData1');
    Route::post('propertyList', 'AuthController@propertyList');
    Route::post('propertyDetails', 'AuthController@propertyDetails');
    Route::post('propertyDetailsBookingDates', 'AuthController@propertyDetailsBookingDates');
    Route::get('getOffer', 'AuthController@getOffer');
    Route::post('OfferDetail', 'AuthController@OfferDetail');
    Route::post('update_profile_host', 'AuthController@update_profile_host');
    Route::post('schedule_appointment', 'AuthController@schedule_appointment');
    Route::get('getAllTypes', 'AuthController@getAllTypes');
    Route::post('becomeahost_add_property', 'AuthController@becomeahost_add_property');
    Route::post('becomeahost_user_details', 'AuthController@becomeahost_user_details');
    Route::post('becomeahost_user_update', 'AuthController@becomeahost_user_update');

    Route::get('getAllCountry', 'AuthController@getAllCountry');
    Route::post('getAllProvince', 'AuthController@getAllProvince');
    Route::post('getAllProvince1', 'AuthController@getAllProvince1');
    Route::post('getAllCity', 'AuthController@getAllCity');
    Route::post('getAllArea', 'AuthController@getAllArea');
    Route::post('getCityArea', 'AuthController@getCityArea');

    Route::post('addProvince', 'AuthController@addProvince');
    Route::post('addCity', 'AuthController@addCity');
    Route::post('addArea', 'AuthController@addArea');


    Route::post('getAllCategory', 'AuthController@getAllCategory');
    Route::get('getAllServices', 'AuthController@getAllServices');
    Route::get('getAllAmenities', 'AuthController@getAllAmenities');

    Route::get('getAllDiscount', 'AuthController@getAllDiscount');
    Route::get('getAllAccomodation', 'AuthController@getAllAccomodation');

    Route::post('getPropertyMinMaxPrice', 'AuthController@getPropertyMinMaxPrice');

    Route::post('share-booking', 'Api\ApiController@shareBooking');
    Route::post('addToWishList', 'Api\ApiController@addToWishList');
    Route::get('my_favorites', 'Api\ApiController@my_favorites');
    Route::get('get_store_card', 'Api\ApiController@get_store_card');
    Route::post('store-card', 'Api\ApiController@store_card');
    Route::post('update_card', 'Api\ApiController@update_card');
    Route::post('deleteCard', 'Api\ApiController@deleteCard');
    Route::post('defaultCard', 'Api\ApiController@defaultCard');
    Route::post('update_profile', 'Api\ApiController@update_profile');
    Route::post('update_loyalty', 'Api\ApiController@update_loyalty');

    // notification API
    Route::post('notification-list', 'Api\ApiController@notificationList');
    Route::post('notification-count', 'Api\ApiController@getNotificationCount');
    Route::post('read-notification', 'Api\ApiController@readNotification');
    Route::post('notification-remove', 'Api\ApiController@removeNotification');

    // Booking Section
    Route::post('add-cart', 'Api\ApiController@addcart');
    Route::post('mobileBooking', 'Api\ApiController@mobileBooking');
    Route::post('mobileBookingReserve', 'Api\ApiController@mobileBookingReserve');
    Route::post('getMobileBookingReserve', 'Api\ApiController@getMobileBookingReserve');
    Route::post('add-reserve', 'Api\ApiController@addreserve');
    Route::get('get-cart', 'Api\ApiController@getCart');
    Route::get('get-resv_booking', 'Api\ApiController@getResvBooking');
    Route::post('checkCouponCode', 'Api\ApiController@checkCouponCode');
    Route::post('checkout', 'Api\ApiController@checkout');
    Route::post('checkout_from_reserve', 'Api\ApiController@checkout_from_reserve');
    Route::post('checkout_reserve', 'Api\ApiController@checkout_reserve');

    Route::post('/booking-verify', 'Api\ApiController@booking_verify');


    Route::get('getbookings', 'Api\ApiController@getbookings');
    Route::get('getreservations', 'Api\ApiController@getreservations');

    Route::post('bookingDetail', 'Api\ApiController@bookingDetail');
    Route::post('cancelBooking', 'Api\ApiController@cancelBooking');
    Route::post('reBooking', 'Api\ApiController@reBooking');
    Route::post('submit-rate', 'Api\ApiController@submitRate');

    Route::post('set-password', 'AuthController@setPassword');
    Route::post('update-profile-mobile', 'AuthController@updateProfileMobile');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('forgot-password', 'AuthController@forgot_password');
    Route::post('reset-password', 'AuthController@resetPassword');
    Route::post('static-content', 'AuthController@getStaticData');
    Route::post('saveDetails', 'AuthController@saveDetails');

    //Chat Section
    Route::get('chat-list', 'Api\ChatController@chatList');
    Route::get('chat-detail/{id}', 'Api\ChatController@chatDetail');


    //-- Send Msg --\\
    Route::post('/send-message', 'Api\ChatController@sendMessage');
    Route::post('/save-message', 'Api\ChatController@saveMessage');
    Route::post('/seen-message', 'Api\ChatController@seenMessage');

    //Admin Section
    Route::post("becomeahost_login", "AuthController@becomeahost_login");
    Route::post("becomeahost_userdetail", "AuthController@becomeahost_userdetail");



    Route::post("becomeahostBooking", "AuthController@becomeahostBooking");
    Route::post("becomeahostBookingShow", "AuthController@becomeahostBookingShow");
    Route::post("becomeahostBookingReserve", "AuthController@becomeahostBookingReserve");
    Route::post("becomeahostBookingReserveShow", "AuthController@becomeahostBookingReserveShow");

    Route::post("becomeahostBookingSearch", "AuthController@becomeahostBookingSearch");
    Route::post("becomeahostBookingSearchShow", "AuthController@becomeahostBookingSearchShow");

    Route::post("becomeahostAllCityList", "AuthController@becomeahostAllCityList");
    Route::post("becomeahostAllAreaList", "AuthController@becomeahostAllAreaList");
    Route::post("becomeahostAllCategoryList", "AuthController@becomeahostAllCategoryList");
    Route::post("becomeahostStatusUpdate", "AuthController@becomeahostStatusUpdate");

    Route::post('admin_userprofile', 'AuthController@admin_userProfile');
    Route::post('admin_updateprofile', 'AuthController@admin_updateProfile');
    Route::post('becomeahost_changePassword', 'AuthController@becomeahost_changePassword');
    Route::post('admin_Accomodation_list', 'AuthController@admin_Accomodation_list');
    
    Route::post('admin_influencerlist', 'AuthController@admin_influencerlist');
    Route::post('admin_view_influencer', 'AuthController@admin_view_influencer');

    Route::post('becomeahostAlloffersList', 'AuthController@becomeahostAlloffersList');
    Route::post('becomeahost_addOffer', 'AuthController@becomeahost_addOffer');
    Route::post('becomeahost_editOffer', 'AuthController@becomeahost_editOffer');
    Route::post('becomeahost_deleteOffer', 'AuthController@becomeahost_deleteOffer');
    Route::post('becomeahost_viewOffer', 'AuthController@becomeahost_viewOffer');
    Route::post('becomeahost_edit_view_Offer', 'AuthController@becomeahost_edit_view_Offer');

    Route::post('becomeahosttransactionShow', 'AuthController@becomeahosttransactionShow');
    Route::post('becomeahostsattlementShow', 'AuthController@becomeahostsattlementShow');

    Route::post('becomeahost_listBuilding', 'AuthController@becomeahost_listBuilding');
    Route::post('becomeahost_addBuilding', 'AuthController@becomeahost_addBuilding');
    Route::post('becomeahost_editBuilding', 'AuthController@becomeahost_editBuilding');
    Route::post('becomeahost_deleteBuilding', 'AuthController@becomeahost_deleteBuilding');
    Route::post('becomeahost_viewBuilding', 'AuthController@becomeahost_viewBuilding');
    Route::post('becomeahost_edit_view_Building', 'AuthController@becomeahost_edit_view_Building');

    Route::post('becomeahost_listExtraService', 'AuthController@becomeahost_listExtraService');
    Route::post('becomeahost_addExtraService', 'AuthController@becomeahost_addExtraService');
    Route::post('becomeahost_editExtraService', 'AuthController@becomeahost_editExtraService');
    Route::post('becomeahost_deleteExtraService', 'AuthController@becomeahost_deleteExtraService');
    Route::post('becomeahost_viewExtraService', 'AuthController@becomeahost_viewExtraService');
    Route::post('becomeahost_edit_view_ExtraService', 'AuthController@becomeahost_edit_view_ExtraService');
    
    Route::post('becomeahost_listContent', 'AuthController@becomeahost_listContent');
    Route::post('becomeahost_viewContent', 'AuthController@becomeahost_viewContent');

    Route::post('becomeahost_listCountry', 'AuthController@becomeahost_listCountry');
    Route::post('becomeahost_addCountry', 'AuthController@becomeahost_addCountry');
    Route::post('becomeahost_edit_view_Country', 'AuthController@becomeahost_edit_view_Country');
    Route::post('becomeahost_updateCountry', 'AuthController@becomeahost_updateCountry');
    Route::post('becomeahost_deleteCountry', 'AuthController@becomeahost_deleteCountry');
    Route::post('becomeahost_viewCountry', 'AuthController@becomeahost_viewCountry');

    Route::post('becomeahost_listProvince', 'AuthController@becomeahost_listProvince');
    Route::post('becomeahost_addProvince', 'AuthController@becomeahost_addProvince');
    Route::post('becomeahost_edit_view_Province', 'AuthController@becomeahost_edit_view_Province');
    Route::post('becomeahost_updateProvince', 'AuthController@becomeahost_updateProvince');
    Route::post('becomeahost_deleteProvince', 'AuthController@becomeahost_deleteProvince');
    Route::post('becomeahost_viewProvince', 'AuthController@becomeahost_viewProvince');

    Route::post('becomeahost_listCity', 'AuthController@becomeahost_listCity');
    Route::post('becomeahost_addCity', 'AuthController@becomeahost_addCity');
    Route::post('becomeahost_edit_view_City', 'AuthController@becomeahost_edit_view_City');
    Route::post('becomeahost_updateCity', 'AuthController@becomeahost_updateCity');
    Route::post('becomeahost_deleteCity', 'AuthController@becomeahost_deleteCity');
    Route::post('becomeahost_viewCity', 'AuthController@becomeahost_viewCity');

    Route::post('becomeahost_listArea', 'AuthController@becomeahost_listArea');
    Route::post('becomeahost_addArea', 'AuthController@becomeahost_addArea');
    Route::post('becomeahost_edit_view_Area', 'AuthController@becomeahost_edit_view_Area');
    Route::post('becomeahost_updateArea', 'AuthController@becomeahost_updateArea');
    Route::post('becomeahost_deleteArea', 'AuthController@becomeahost_deleteArea');
    Route::post('becomeahost_viewArea', 'AuthController@becomeahost_viewArea');

    Route::post('becomeahost_listAccomodation', 'AuthController@becomeahost_listAccomodation');
    Route::post('becomeahost_view_Accomodation', 'AuthController@becomeahost_view_Accomodation');
    Route::post('becomeahost_AccomodationDuplicate', 'AuthController@becomeahost_AccomodationDuplicate');
    Route::post('becomeahost_delete_Accomodation', 'AuthController@becomeahost_delete_Accomodation');
    Route::post('becomeahost_AddMoreImage_Accomodation', 'AuthController@becomeahost_AddMoreImage_Accomodation');
    Route::post('becomeahost_imageView_Accomodation', 'AuthController@becomeahost_imageView_Accomodation');
    Route::post('becomeahost_imagesDelete_Accomodation', 'AuthController@becomeahost_imagesDelete_Accomodation');
    Route::post("becomeahost_propertyAdd", "AuthController@becomeahost_propertyAdd");
    Route::post("becomeahost_propertyUpdate", "AuthController@becomeahost_propertyUpdate");
    Route::post("becomeahost_edit_view_Accomodation", "AuthController@becomeahost_edit_view_Accomodation");
    Route::post("becomeahost_view_calendar", "AuthController@becomeahost_view_calendar");
    Route::post("becomeahost_load_calendar", "AuthController@becomeahost_load_calendar");
    Route::post("becomeahost_blockDate", "AuthController@becomeahost_blockDate");
    Route::post("becomeahost_blockDateRange", "AuthController@becomeahost_blockDateRange");
    Route::post("becomeahost_unBlockDateRange", "AuthController@becomeahost_unBlockDateRange");
    Route::post("becomeahost_exportProperties", "AuthController@becomeahost_exportProperties");


    Route::post('becomeahost_typeWiseCategorylist', 'AuthController@becomeahost_typeWiseCategorylist');
    Route::post('becomeahost_typelist', 'AuthController@becomeahost_typelist');
    Route::post('becomeahost_countryWiseProvincelist', 'AuthController@becomeahost_countryWiseProvincelist');
    Route::post('becomeahost_ProvinceWiseCitylist', 'AuthController@becomeahost_ProvinceWiseCitylist');
    Route::post('becomeahost_CityWiseArealist', 'AuthController@becomeahost_CityWiseArealist');
    Route::post('becomeahost_ExtraService_List', 'AuthController@becomeahost_ExtraService_List');
    
    Route::post('becomeahost_user_permissions', 'AuthController@becomeahost_user_permissions');
    
    Route::post('becomeahost_addCategory', 'AuthController@becomeahost_addCategory');
    Route::post('becomeahost_addAmenity', 'AuthController@becomeahost_addAmenity');





});




