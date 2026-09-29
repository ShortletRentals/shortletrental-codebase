<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\MountManager;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    // return what you want
});
Auth::routes(['verify' => true, 'register' => false]);



Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });

    // login route
    Route::POST('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::POST('login', 'Auth\LoginController@login')->name('login');

    //admin profile
    Route::get('profile', 'SettingController@frontend')->name('profile');
    Route::post('/saveProfile', 'SettingController@saveProfile');
    Route::get('/change_password','SettingController@change_password')->name('change_password');
    Route::post('/change_password','SettingController@changePassword')->name('changePassword');

    //Admin Setting
    Route::get('admin-settings', 'SettingController@settings')->name('admin-settings.index');
    Route::post('setting-save/{id}', 'SettingController@update_settings');
    Route::post('host_profile_update','SettingController@host_profile_update')->name('host_profile_update');
    Route::post('influencer_profile_update','SettingController@influencer_profile_update')->name('influencer_profile_update');

    // SubAdmin manager route
    // Route::resource('api/subadmin', 'SubAdminController');
    Route::get('subadmin/index','SubAdminController@index')->name('subadmin.index');  
    Route::get('subadmin', 'SubAdminController@frontend')->name('subadmin');
    Route::get('subadmin/create', 'SubAdminController@create')->name('subadmin.create');
    Route::post('subadmin/store','SubAdminController@store')->name('subadmin.store'); 
    Route::get('subadmin/edit','SubAdminController@edit')->name('subadmin.edit');
    Route::get('subadmin/show','SubAdminController@show')->name('subadmin.show');
    Route::get('subadmin/status','SubAdminController@status')->name('subadmin.status');
    Route::post('subadmin/update','SubAdminController@update')->name('subadmin.update'); 
    Route::get('subadmin/permission','SubAdminController@permission')->name('subadmin.permission');
    Route::get('subadmin/delete/{id}','SubAdminController@destroy')->name('subadmin.delete');  

    //Manufacturer Staff manager    
    Route::get('influencer','InfluencerController@index')->name('influencer.index');          
    Route::get('influencer/create','InfluencerController@create')->name('influencer.create');    
    Route::post('influencer/store','InfluencerController@store')->name('influencer.store');      
    Route::get('influencer/edit','InfluencerController@edit')->name('influencer.edit');          
    Route::post('influencer/update','InfluencerController@update')->name('influencer.update');     
    Route::get('influencer/show','InfluencerController@show')->name('influencer.show');       
    Route::get('influencer/status','InfluencerController@status')->name('influencer.status');
    Route::get('influencer/delete/{id}','InfluencerController@destroy')->name('influencer.delete');
    
    //Customer manager
    Route::get('customer','CustomerController@index')->name('customer.index');
    Route::get('customer/create','CustomerController@create')->name('customer.create');    
    Route::post('customer/store','CustomerController@store')->name('customer.store');      
    Route::get('customer/edit','CustomerController@edit')->name('customer.edit');          
    Route::post('customer/update','CustomerController@update')->name('customer.update');     
    Route::get('customer/show','CustomerController@show')->name('customer.show');       
    Route::get('customer/status','CustomerController@status')->name('customer.status');
    Route::get('customer/delete/{id}','CustomerController@destroy')->name('customer.delete');

    //Host manager
    Route::get('host','HostController@index')->name('host.index');
    Route::get('host/create','HostController@create')->name('host.create');    
    Route::post('host/store','HostController@store')->name('host.store');      
    Route::get('host/edit','HostController@edit')->name('host.edit');          
    Route::post('host/update','HostController@update')->name('host.update');     
    Route::get('host/show','HostController@show')->name('host.show');       
    Route::get('host/status','HostController@status')->name('host.status');
    Route::get('host/delete/{id}','HostController@destroy')->name('host.delete');  
    Route::post('host/change_superhost','HostController@change_superhost')->name('host.change_superhost');  
    Route::post('host/importData', 'HostController@importData');
    Route::post('host/exportData', 'HostController@exportData');

    //Become A Host manager
    Route::get('become_a_host','BecomeAHostController@index')->name('become_a_host.index');
    Route::get('become_a_host/create','BecomeAHostController@create')->name('become_a_host.create');
    Route::post('become_a_host/store','BecomeAHostController@store')->name('become_a_host.store');
    Route::get('become_a_host/edit','BecomeAHostController@edit')->name('become_a_host.edit');
    Route::post('become_a_host/update','BecomeAHostController@update')->name('become_a_host.update');
    Route::get('become_a_host/show','BecomeAHostController@show')->name('become_a_host.show');
    // Route::get('become_a_host/status','BecomeAHostController@status')->name('become_a_host.status');
    Route::post('become_a_host/status', 'BecomeAHostController@status');
    Route::get('become_a_host/delete/{id}','BecomeAHostController@destroy')->name('become_a_host.delete');

    //Building manager
    Route::get('building','BuildingController@index')->name('building.index');
    Route::get('building/create','BuildingController@create')->name('building.create');
    Route::post('building/store','BuildingController@store')->name('building.store');
    Route::get('building/edit','BuildingController@edit')->name('building.edit');
    Route::post('building/update','BuildingController@update')->name('building.update');
    Route::get('building/show','BuildingController@show')->name('building.show');
    Route::get('building/status','BuildingController@status')->name('building.status');
    Route::get('building/delete/{id}','BuildingController@destroy')->name('building.delete');
    Route::post('building/buildingStore','BuildingController@buildingStore')->name('building.buildingStore');

    //appointment manager
    Route::get('appointment','AppointmentController@index')->name('appointment.index');
    Route::get('appointment/create','AppointmentController@create')->name('appointment.create');
    Route::post('appointment/store','AppointmentController@store')->name('appointment.store');
    Route::get('appointment/edit','AppointmentController@edit')->name('appointment.edit');
    Route::post('appointment/update','AppointmentController@update')->name('appointment.update');
    Route::get('appointment/show','AppointmentController@show')->name('appointment.show');
    Route::get('appointment/status','AppointmentController@status')->name('appointment.status');
    Route::get('appointment/cancel/{id}','AppointmentController@cancel')->name('appointment.cancel');
    Route::get('appointment/delete/{id}','AppointmentController@destroy')->name('appointment.delete');

    //Country manager
    Route::get('country','CountryController@index')->name('country.index');
    Route::get('country/create','CountryController@create')->name('country.create');    
    Route::post('country/store','CountryController@store')->name('country.store');      
    Route::get('country/edit','CountryController@edit')->name('country.edit');          
    Route::post('country/update','CountryController@update')->name('country.update');     
    Route::get('country/show','CountryController@show')->name('country.show');       
    Route::get('country/status','CountryController@status')->name('country.status');
    Route::get('country/delete/{id}','CountryController@destroy')->name('country.delete');

    //Province manager
    Route::get('province','ProvinceController@index')->name('province.index');
    Route::get('province/create','ProvinceController@create')->name('province.create');    
    Route::post('province/store','ProvinceController@store')->name('province.store');      
    Route::get('province/edit','ProvinceController@edit')->name('province.edit');          
    Route::post('province/update','ProvinceController@update')->name('province.update');     
    Route::get('province/show','ProvinceController@show')->name('province.show');       
    Route::get('province/status','ProvinceController@status')->name('province.status');
    Route::get('province/delete/{id}','ProvinceController@destroy')->name('province.delete');
    Route::post('province/provinceStore','ProvinceController@provinceStore')->name('province.provinceStore');

    //City manager
    Route::get('city','CityController@index')->name('city.index');
    Route::get('city/create','CityController@create')->name('city.create');
    Route::post('city/store','CityController@store')->name('city.store');
    Route::get('city/edit','CityController@edit')->name('city.edit');
    Route::post('city/update','CityController@update')->name('city.update');
    Route::get('city/show','CityController@show')->name('city.show');
    Route::get('city/status','CityController@status')->name('city.status');
    Route::get('city/show_province/{country_id}/{province_id?}','CityController@show_province')->name('city.show_province');
    Route::get('city/delete/{id}','CityController@destroy')->name('city.delete');
    Route::post('city/cityStore','CityController@cityStore')->name('city.cityStore');

    //Area manager
    Route::get('area','AreaController@index')->name('area.index');
    Route::get('area/create','AreaController@create')->name('area.create');    
    Route::post('area/store','AreaController@store')->name('area.store');      
    Route::get('area/edit','AreaController@edit')->name('area.edit');          
    Route::post('area/update','AreaController@update')->name('area.update');     
    Route::get('area/show','AreaController@show')->name('area.show');       
    Route::get('area/status','AreaController@status')->name('area.status');
    Route::get('area/show_province/{country_id}/{province_id?}','AreaController@show_province')->name('area.show_province');
    Route::get('area/show_province_new/{country_id}/{province_id?}','AreaController@show_province_new')->name('area.show_province_new');
    Route::get('area/show_city/{country_id}/{province_id}/{city_id?}','AreaController@show_city')->name('area.show_city');
    Route::get('area/show_area/{country_id}/{province_id}/{city_id}/{area_id?}','AreaController@show_area')->name('area.show_area');
    Route::get('area/delete/{id}','AreaController@destroy')->name('area.delete');
    Route::post('area/areaStore','AreaController@areaStore')->name('area.areaStore');

    //Category manager
    Route::get('category','CategoryController@index')->name('category.index');
    Route::get('category/create','CategoryController@create')->name('category.create');    
    Route::post('category/store','CategoryController@store')->name('category.store');      
    Route::get('category/edit','CategoryController@edit')->name('category.edit');          
    Route::post('category/update','CategoryController@update')->name('category.update');     
    Route::get('category/show','CategoryController@show')->name('category.show');              
    Route::get('category/delete/{id}','CategoryController@destroy')->name('category.delete');
    Route::get('category/status','CategoryController@status')->name('category.status');
    Route::get('category/changeStatus/{id}/{status}', 'CategoryController@changeStatus');
    Route::get('category/show_category/{type_val}/{category?}','CategoryController@show_category')->name('category.show_category');
    Route::post('category/categoryStore','CategoryController@categoryStore')->name('category.categoryStore');

    //Amenity manager
    Route::get('amenity','AmenityController@index')->name('amenity.index');
    Route::get('amenity/create','AmenityController@create')->name('amenity.create');    
    Route::post('amenity/store','AmenityController@store')->name('amenity.store');      
    Route::get('amenity/edit','AmenityController@edit')->name('amenity.edit');          
    Route::post('amenity/update','AmenityController@update')->name('amenity.update');     
    Route::get('amenity/show','AmenityController@show')->name('amenity.show');       
    Route::get('amenity/status','AmenityController@status')->name('amenity.status'); 
    Route::get('amenity/delete/{id}','AmenityController@destroy')->name('amenity.delete');
    Route::post('amenity/amenityStore','AmenityController@amenityStore')->name('amenity.amenityStore');

    //Extra_service manager
    Route::get('extra_service','ExtraServiceController@index')->name('extra_service.index');
    Route::get('extra_service/create','ExtraServiceController@create')->name('extra_service.create');    
    Route::post('extra_service/store','ExtraServiceController@store')->name('extra_service.store');      
    Route::get('extra_service/edit','ExtraServiceController@edit')->name('extra_service.edit');          
    Route::post('extra_service/update','ExtraServiceController@update')->name('extra_service.update');     
    Route::get('extra_service/show','ExtraServiceController@show')->name('extra_service.show');       
    Route::get('extra_service/status','ExtraServiceController@status')->name('extra_service.status');
    Route::get('extra_service/defaltStatus','ExtraServiceController@defaltStatus')->name('extra_service.defaltStatus');
    Route::get('extra_service/delete/{id}','ExtraServiceController@destroy')->name('extra_service.delete');
    Route::post('extra_service/extraServiceStore','ExtraServiceController@extraServiceStore')->name('extra_service.extraServiceStore');

    //Property manager
    Route::get('property','PropertyController@index')->name('property.index');
    Route::get('property/create','PropertyController@create')->name('property.create');    
    Route::post('property/store','PropertyController@store')->name('property.store');      
    Route::get('property/edit','PropertyController@edit')->name('property.edit');          
    Route::post('property/update','PropertyController@update')->name('property.update');     
    Route::get('property/show','PropertyController@show')->name('property.show');       
    Route::get('property/delete/{id}','PropertyController@destroy')->name('property.delete');
    Route::get('property/changeStatus','PropertyController@changeStatus')->name('property.status');
    Route::get('/property/imageView/{u_id}', 'PropertyController@imageView');
    Route::put('property/add-more-images/{id}', 'PropertyController@addMoreImages');
    Route::delete('/property/propertyImagesDelete/{del_id}', 'PropertyController@propertyImagesDelete');

    Route::get('property/position/{id}/{position}','PropertyController@position')->name('property.position');


    Route::post('property/exportProperties', 'PropertyController@exportProperties');
    Route::post('property/change_book_type', 'PropertyController@change_book_type');
    Route::post('property/change_posiation', 'PropertyController@change_posiation');
    // Route::get('property/exportProperties', 'PropertyController@exportProperties')->name('property.export');
    Route::get('property1/showTags', 'PropertyController@showTags');
    Route::get('property/duplicate','PropertyController@duplicate')->name('property.duplicate');

    Route::get('property/calendar','PropertyController@view_calendar')->name('property.calendar');
    Route::get('property/checkBlockDate','PropertyController@checkBlockDate')->name('property.checkBlockDate');
    Route::get('property/blockDate','PropertyController@blockDate')->name('property.blockDate');
    Route::post('property/blockDateRange','PropertyController@blockDateRange')->name('property.blockDateRange');
    Route::post('property/unBlockDateRange','PropertyController@unBlockDateRange')->name('property.unBlockDateRange');
    Route::get('property/load_calendar','PropertyController@load_calendar')->name('property.load_calendar');

    Route::post('getLatLongByCountry', 'PropertyController@getLatLongByCountry');

    //Rate manager
    Route::get('rate','RateController@index')->name('rate.index');
    Route::get('rate/create','RateController@create')->name('rate.create');
    Route::post('rate/store','RateController@store')->name('rate.store');
    Route::get('rate/edit','RateController@edit')->name('rate.edit');
    Route::post('rate/update','RateController@update')->name('rate.update');
    Route::get('rate/show','RateController@show')->name('rate.show');
    Route::get('rate/status','RateController@status')->name('rate.status');
    Route::get('rate/delete/{id}','RateController@destroy')->name('rate.delete');

    //Discount manager
    Route::get('discount','DiscountController@index')->name('discount.index');
    Route::get('discount/create','DiscountController@create')->name('discount.create');
    Route::post('discount/store','DiscountController@store')->name('discount.store');
    Route::get('discount/edit','DiscountController@edit')->name('discount.edit');
    Route::post('discount/update','DiscountController@update')->name('discount.update');
    Route::get('discount/show','DiscountController@show')->name('discount.show');
    Route::get('discount/status','DiscountController@status')->name('discount.status');
    Route::get('discount/delete/{id}','DiscountController@destroy')->name('discount.delete');

    Route::get('discount/discount_customer_view/{id}','DiscountController@discount_customer_view');
    Route::post('discount/discount_customer_export', 'DiscountController@discount_customer_export');

    //Commission manager
    Route::get('commission','CommissionController@index')->name('commission.index');
    Route::get('commission/create','CommissionController@create')->name('commission.create');    
    Route::post('commission/store','CommissionController@store')->name('commission.store');      
    Route::get('commission/edit','CommissionController@edit')->name('commission.edit');          
    Route::post('commission/update','CommissionController@update')->name('commission.update');     
    Route::get('commission/show','CommissionController@show')->name('commission.show');       
    Route::get('commission/status','CommissionController@status')->name('commission.status');
    Route::get('commission/getAccomodation/{host_id}','CommissionController@getAccomodationByHost');
    Route::get('commission/delete/{id}','CommissionController@destroy')->name('commission.delete');

    //Chat manager
    Route::get('chat','ChatController@index')->name('chat.index');
    Route::get('chat_list','ChatController@chatList')->name('chat.list');
    Route::get('chat-detail/{chatId}','ChatController@chatDetail');

    Route::get('guest-chat','ChatController@guest_chat')->name('guest_chat.index');

    Route::get('guest_list','ChatController@guestList')->name('guest_chat.guest_list');

    Route::get('show_notification','ChatController@show_notification')->name('show_notification.index');
    Route::get('show_notification_count','ChatController@show_notification_count')->name('show_notification_count.index');

    // Password reset routes...
    // Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');


    //notification manager
    Route::get('notification','NotificationController@index')->name('notification.index');
    Route::get('notification/create','NotificationController@create')->name('notification.create');    
    Route::post('notification/store','NotificationController@store')->name('notification.store');      
    Route::get('notification/edit','NotificationController@edit')->name('notification.edit');          
    Route::post('notification/update','NotificationController@update')->name('notification.update');     
    Route::get('notification/show','NotificationController@show')->name('notification.show');       
    Route::get('notification/status','NotificationController@status')->name('notification.status');
    Route::get('notification/delete/{id}','NotificationController@destroy')->name('notification.delete');

    Route::get('notifications/readNotification/{id}', 'NotificationController@readNotification')->name('readNotification');
    Route::get('notifications/deleteNotification/{id}', 'NotificationController@deleteNotification')->name('deleteNotification');
    Route::get('notifications/readAllNotification', 'NotificationController@readAllNotification')->name('readAllNotification');
    Route::get('notifications/clearAllNotification', 'NotificationController@clearAllNotification')->name('clearAllNotification');

    //content manager
    Route::get('content','ContentController@index')->name('content.index');
    Route::get('content/create','ContentController@create')->name('content.create');    
    Route::post('content/store','ContentController@store')->name('content.store');      
    Route::get('content/edit','ContentController@edit')->name('content.edit');          
    Route::post('content/update','ContentController@update')->name('content.update');     
    Route::get('content/show','ContentController@show')->name('content.show');       
    Route::get('content/status','ContentController@status')->name('content.status');
    Route::get('content/changeStatus/{id}/{status}', 'ContentController@changeStatus');

    //Subscribe Users manager
    Route::get('subscribe_users','SubscribeUsersController@index')->name('subscribe_users.index');
    Route::get('subscribe_users/show','SubscribeUsersController@show')->name('subscribe_users.show');       
    Route::get('subscribe_users/status','SubscribeUsersController@status')->name('subscribe_users.status');
    Route::get('subscribe_users/changeStatus/{id}/{status}', 'SubscribeUsersController@changeStatus');
    Route::post('subscribe_users/exportSubscribeUsers', 'SubscribeUsersController@exportSubscribeUsers');

    //email manager
    Route::get('email_template_lang','EmailTemplateController@index')->name('email_template_lang.index');
    Route::get('email_template_lang/create','EmailTemplateController@create')->name('email_template_lang.create');    
    Route::post('email_template_lang/store','EmailTemplateController@store')->name('email_template_lang.store');      
    Route::get('email_template_lang/edit','EmailTemplateController@edit')->name('email_template_lang.edit');          
    Route::post('email_template_lang/update','EmailTemplateController@update')->name('email_template_lang.update');     
    Route::get('email_template_lang/show','EmailTemplateController@show')->name('email_template_lang.show');       
    Route::get('email_template_lang/status','EmailTemplateController@status')->name('email_template_lang.status');
    // Route::post('email_template_lang/status','EmailTemplateController@status');
    Route::get('email_template_lang/changeStatus/{id}/{status}', 'EmailTemplateController@changeStatus');

    //booking manager
    Route::get('booking','BookingController@index')->name('booking.index');
    Route::get('booking/create','BookingController@create')->name('booking.create');    
    Route::post('booking/store','BookingController@store')->name('booking.store');      
    Route::get('booking/edit','BookingController@edit')->name('booking.edit');          
    Route::post('booking/update','BookingController@update')->name('booking.update');     
    Route::get('booking/show','BookingController@show')->name('booking.show');
    Route::get('booking/check_in_show','BookingController@check_in_show')->name('booking.check_in_show');
    Route::get('booking/check_in_action','BookingController@check_in_action')->name('booking.check_in_action');
    Route::get('booking/status','BookingController@status')->name('booking.status');
    Route::get('booking/bookingStatus','BookingController@bookingStatus')->name('booking.bookingStatus');
    Route::get('booking/bookingType','BookingController@bookingType')->name('booking.bookingType');
    Route::post('booking/exportBookings', 'BookingController@exportBookings');
    Route::get('booking/delete/{id}','BookingController@destroy')->name('booking.delete');
     Route::post('booking/exportProperties', 'BookingController@exportProperties');

    //booking manager
    Route::get('booking_reserve','BookingReserveController@index')->name('booking_reserve.index');
    Route::get('booking_reserve/create','BookingReserveController@create')->name('booking_reserve.create');    
    Route::post('booking_reserve/store','BookingReserveController@store')->name('booking_reserve.store');      
    Route::get('booking_reserve/edit','BookingReserveController@edit')->name('booking_reserve.edit');          
    Route::post('booking_reserve/update','BookingReserveController@update')->name('booking_reserve.update');     
    Route::get('booking_reserve/show','BookingReserveController@show')->name('booking_reserve.show');
    Route::get('booking_reserve/check_in_show','BookingReserveController@check_in_show')->name('booking_reserve.check_in_show');
    Route::get('booking_reserve/status','BookingReserveController@status')->name('booking_reserve.status');
    Route::get('booking_reserve/bookingStatus','BookingReserveController@bookingStatus')->name('booking_reserve.bookingStatus');
    Route::get('booking_reserve/bookingType','BookingReserveController@bookingType')->name('booking_reserve.bookingType');
    Route::get('booking_reserve/delete/{id}','BookingReserveController@destroy')->name('booking.delete');
    Route::post('booking_reserve/exportBookings', 'BookingReserveController@exportBookings');

    //booking search manager
    Route::get('booking_search','BookingSearchController@index')->name('booking_search.index');
    Route::get('booking_search/create','BookingSearchController@create')->name('booking_search.create');
    Route::post('booking_search/store','BookingSearchController@store')->name('booking_search.store');
    Route::get('booking_search/edit','BookingSearchController@edit')->name('booking_search.edit');
    Route::post('booking_search/update','BookingSearchController@update')->name('booking_search.update');
    Route::get('booking_search/show','BookingSearchController@show')->name('booking_search.show');
    Route::get('booking_search/status','BookingSearchController@status')->name('booking_search.status');

    //transection manager
    Route::get('transection','TransectionController@index')->name('transection.index');
    Route::get('transection/create','TransectionController@create')->name('transection.create');    
    Route::post('transection/store','TransectionController@store')->name('transection.store');      
    Route::get('transection/edit','TransectionController@edit')->name('transection.edit');          
    Route::post('transection/update','TransectionController@update')->name('transection.update');     
    Route::get('transection/show','TransectionController@show')->name('transection.show');       
    Route::get('transection/status','TransectionController@status')->name('transection.status');
    Route::post('transection/export', 'TransectionController@export');
    Route::get('transection/sattlementStatus','TransectionController@sattlementStatus')->name('transection.sattlementStatus');

    //sattlement manager
    Route::get('sattlement','SattlementController@index')->name('sattlement.index');
    Route::get('sattlement/create','SattlementController@create')->name('sattlement.create');    
    Route::post('sattlement/store','SattlementController@store')->name('sattlement.store');      
    Route::get('sattlement/edit','SattlementController@edit')->name('sattlement.edit');          
    Route::post('sattlement/update','SattlementController@update')->name('sattlement.update');     
    Route::get('sattlement/show','SattlementController@show')->name('sattlement.show');       
    Route::get('sattlement/status','SattlementController@status')->name('sattlement.status');
    Route::post('sattlement/export', 'SattlementController@export');

    //transection manager
    Route::get('bookings_commission','BookingsCommissionController@index')->name('bookings_commission.index');
    Route::get('bookings_commission/create','BookingsCommissionController@create')->name('bookings_commission.create');
    Route::post('bookings_commission/store','BookingsCommissionController@store')->name('bookings_commission.store');
    Route::get('bookings_commission/edit','BookingsCommissionController@edit')->name('bookings_commission.edit');
    Route::post('bookings_commission/update','BookingsCommissionController@update')->name('bookings_commission.update');
    Route::get('bookings_commission/show','BookingsCommissionController@show')->name('bookings_commission.show');
    Route::get('bookings_commission/status','BookingsCommissionController@status')->name('bookings_commission.status');
    Route::post('bookings_commission/exportBookings', 'BookingsCommissionController@exportBookings');

    //Offer manager
    Route::get('offer','OfferController@index')->name('offer.index');
    Route::get('offer/create','OfferController@create')->name('offer.create');    
    Route::post('offer/store','OfferController@store')->name('offer.store');      
    Route::get('offer/edit','OfferController@edit')->name('offer.edit');          
    Route::post('offer/update','OfferController@update')->name('offer.update');     
    Route::get('offer/show','OfferController@show')->name('offer.show');       
    Route::get('offer/status','OfferController@status')->name('offer.status');
    Route::get('offer/delete/{id}','OfferController@destroy')->name('offer.delete');

    //Blog manager
    Route::get('blog','BlogController@index')->name('blog.index');
    Route::get('blog/create','BlogController@create')->name('blog.create');    
    Route::post('blog/store','BlogController@store')->name('blog.store');      
    Route::get('blog/edit','BlogController@edit')->name('blog.edit');          
    Route::post('blog/update','BlogController@update')->name('blog.update');     
    Route::get('blog/show','BlogController@show')->name('blog.show');       
    Route::get('blog/status','BlogController@status')->name('blog.status');
    Route::get('blog/delete/{id}','BlogController@destroy')->name('blog.delete');

    //Blog Category manager
    Route::get('blog_category','BlogCategoryController@index')->name('blog_category.index');
    Route::get('blog_category/create','BlogCategoryController@create')->name('blog_category.create');    
    Route::post('blog_category/store','BlogCategoryController@store')->name('blog_category.store');      
    Route::get('blog_category/edit','BlogCategoryController@edit')->name('blog_category.edit');          
    Route::post('blog_category/update','BlogCategoryController@update')->name('blog_category.update');     
    Route::get('blog_category/show','BlogCategoryController@show')->name('blog_category.show');       
    Route::get('blog_category/status','BlogCategoryController@status')->name('blog_category.status');
    Route::get('blog_category/delete/{id}','BlogCategoryController@destroy')->name('blog_category.delete');


    Route::get('/dashboard', 'HomeController@index')->name('home')->middleware(['verified', 'auth']);
    Route::post('previous_day_records','HomeController@previous_day_records');
    Route::post('pending_action_records','HomeController@pending_action_records');
    Route::post('change_booking_cancellation','HomeController@change_booking_cancellation');
    Route::post('change_avg_amount_and_nights','HomeController@change_avg_amount_and_nights');
    Route::post('change_admin_commission','HomeController@change_admin_commission');
    Route::post('booking_change','HomeController@booking_change');
    Route::post('occupancyFilter','HomeController@occupancyFilter');
    Route::get('next_day_records/{value}','HomeController@next_day_records');
    Route::post('goToPage','HomeController@goToPage');

    // Permission
    Route::get('/permissions', 'PermissionController@index')->name('permissions');
    Route::get('/getRole', 'PermissionController@getRole');
    Route::get('/getPermissions/{id?}', 'PermissionController@getPermissions');
    Route::get('savePermission/{permission_id}/{role_id}', 'PermissionController@savePermission');
    Route::get('deletePermission/{permission_id}/{role_id}', 'PermissionController@deletePermission');
    Route::get('saveUserPermission/{permission_id}/{user_id}', 'PermissionController@saveUserPermission');
    Route::get('deleteUserPermission/{permission_id}/{user_id}', 'PermissionController@deleteUserPermission');

    Route::get('permissions/user_listing', 'PermissionController@perm_userData')->name('ajax.permUserdata');
    Route::get('permissions/user_permissions/{id}','PermissionController@user_permissions')->name('permissions.user_permissions');
    Route::get('permissions/add_role_permission/{r_id}/{p_name}', 'PermissionController@saveRolePermission');
    Route::get('permissions/delete_role_permission/{r_id}/{p_name}', 'PermissionController@deleteRolePermission');
    Route::get('permissions/add_user_permission/{u_id}/{p_name}', 'PermissionController@saveUserPermission');
    Route::get('permissions/delete_user_permission/{u_id}/{p_name}', 'PermissionController@deleteUserPermission');
});

Route::get('email-test', function(){
    $details['email'] = 'agrawal.mayank@inventcolab.com';
    dispatch(new App\Jobs\SendEmailJob($details));
    dd('done');
});
Route::get('download/{filename}', 'AuthController@downloadFile')->name('downloadFile');
Route::get('auth/google', 'Web\AuthController@redirectToGoogle')->name('user.auth.redirect-togoogle');
Route::get('auth/google/callback', 'Web\AuthController@handleGoogleCallback')->name('user.auth.handle_googlecallback');

Route::get('auth/facebook', 'Web\AuthController@redirectToFacebook')->name('user.auth.redirect-tofacebook');
Route::get('auth/facebook/callback', 'Web\AuthController@handleFacebookCallback')->name('user.auth.handle_facebookcallback');

Route::get('/webLoginSocial', 'Web\AuthController@webLoginSocial')->name('webLoginSocial');
Route::get('/', 'Web\HomeController@index')->name('web.home');
// Route::get('/', 'Web\HomeController@index')->name('web.home');
Route::get('/bookings/{booking_id}', 'Web\HomeController@booking_detail_without_auth'); /////without login
Route::get('/become_a_partner', 'Web\HomeController@become_a_partner')->name('web.become_a_partner');
Route::get('/payment', 'Web\HomeController@payment')->name('web.payment');
Route::get('/become_a_host', 'Web\BecomeAHostController@become_a_host')->name('web.become_a_host');
Route::get('/properties', 'Web\HomeController@properties')->name('web.properties');
Route::post('/getFilteredProperties', 'Web\HomeController@getFilteredProperties')->name('web.getFilteredProperties');

Route::post('/getHomeProperties', 'Web\HomeController@getHomeProperties')->name('web.getHomeProperties');
Route::get('/properties/map', 'Web\HomeController@properties_map')->name('web.properties-map');
Route::post('/getFilteredMapProperties', 'Web\HomeController@getFilteredMapProperties')->name('web.getFilteredMapProperties');
Route::get('/property-detail/{id}', 'Web\HomeController@property_detail')->name('web.property-detail');
Route::post('/rateListPricePropertyDetails', 'Web\HomeController@rateListPricePropertyDetails')->name('web.rateListPricePropertyDetails');
Route::get('/property-detail-booking_calender', 'Web\HomeController@property_detail_booking_calender')->name('web.property-detail-booking-calender');
Route::get('/getCity/{id}/{city_id?}', 'Web\HomeController@getCity');
Route::get('/getCityArea/{id}/{city_id?}', 'Web\HomeController@getCityArea');
Route::post('/schedule_appointment_form', 'Web\HomeController@schedule_appointment')->name('web.schedule_appointment_form');
Route::get('guest-chat','Web\ChatController@guestChat')->name('web.guest-chat');

Route::post('/web_login', 'Web\AuthController@webLogin')->name('web.login_form');
Route::post('/host_login_form', 'Web\AuthController@HostWebLogin')->name('web.host_login_form');
Route::post('/become_a_host_signup_form', 'Web\AuthController@webSignup')->name('web.become_a_host_signup_form');
Route::post('/partner_signup_form', 'Web\AuthController@webPartnerSignup')->name('web.partner_signup_form');
Route::post('/customer_signup_form', 'Web\AuthController@webCustomerSignup')->name('web.customer_signup_form');
Route::post('/forget_password_form', 'Web\AuthController@webForgetPassword')->name('web.forget_password_form');
Route::post('/otp_form', 'Web\AuthController@varifyOtp')->name('web.otp_form');
Route::post('/forget_otp_form', 'Web\AuthController@forgetVarifyOtp')->name('web.forget_otp_form');
// Route::post('/resend_otp_form', 'Web\AuthController@resend_otp_form')->name('web.resend_otp_form');
Route::get('/resend_otp_form', 'Web\AuthController@resend_otp_form')->name('web.resend_otp_form');

// static pages

Route::get('/hashkey', 'Web\StaticPageController@getHashes')->name('web.hashkey');

Route::get('/about-us', 'Web\StaticPageController@about_us')->name('web.about-us');
Route::get('/contact-us', 'Web\StaticPageController@contact_us')->name('web.contact-us');
Route::post('save-contact-us', 'Web\StaticPageController@save_contact_us')->name('web.save-contact-us');
Route::get('/privacy-policy', 'Web\StaticPageController@privacy_policy')->name('web.privacy-policy');
Route::get('/cancellation-policy', 'Web\StaticPageController@cancellation_policy')->name('web.cancellation-policy');
Route::get('/how-it-works', 'Web\StaticPageController@how_it_works')->name('web.how-it-works');
Route::get('/term-of-use', 'Web\StaticPageController@term_of_use')->name('web.term-of-use');
Route::get('/host-policy', 'Web\StaticPageController@host_policy')->name('web.host-policy');
Route::post('/subscription', 'Web\StaticPageController@subscription')->name('web.subscription');
Route::get('/help-center', 'Web\StaticPageController@help_center')->name('web.help-center');
Route::get('/blog/{slug}', 'Web\StaticPageController@help_center_detail');
Route::get('/offer_list', 'Web\StaticPageController@offer_list');
Route::get('/blog/category/{id}', 'Web\StaticPageController@help_center_category');
Route::post('/searchBlog', 'Web\StaticPageController@searchBlog');
Route::get('/cookies-policy', 'Web\StaticPageController@cookies_policy')->name('web.cookies-policy');
Route::get('/thank-you', 'Web\StaticPageController@thank_you')->name('web.thank-you');


Route::get('area/show_province/{country_id}/{province_id?}','Web\StaticPageController@show_province')->name('area.show_province');
Route::get('area/show_province_partner/{country_id}/{province_id?}','Web\StaticPageController@show_province_partner')->name('area.show_province_partner');
Route::get('area/show_city/{country_id}/{province_id}/{city_id?}','Web\StaticPageController@show_city')->name('area.show_city');
Route::get('area/show_city_partner/{country_id}/{province_id}/{city_id?}','Web\StaticPageController@show_city_partner')->name('area.show_city_partner');
Route::get('area/show_area/{country_id}/{province_id}/{city_id}/{area_id?}','Web\StaticPageController@show_area')->name('area.show_area');
Route::get('area/show_area_partner/{country_id}/{province_id}/{city_id}/{area_id?}','Web\StaticPageController@show_area_partner')->name('area.show_area_partner');

Route::post('get_property_price', 'Web\StaticPageController@get_property_price')->name('web.get_property_price');

Route::get('/guest-area/checkin/search', 'Web\GuestCheckInController@index')->name('web.guest_check_in');
Route::post('/checkin_login_form', 'Web\GuestCheckInController@checkin_login_form')->name('web.checkin_login_form');
Route::get('/guest-area/checkin/{booking_token}', 'Web\GuestCheckInController@guest_checkin_first');
Route::get('/guest-area/checkin/personal/{booking_token}', 'Web\GuestCheckInController@guest_checkin_personal');
Route::get('/guest-area/checkin/documents/{booking_token}', 'Web\GuestCheckInController@guest_checkin_documents');
Route::get('/guest-area/checkin/upload/{booking_token}', 'Web\GuestCheckInController@guest_checkin_upload');
Route::get('/guest-area/checkin/no_camera/{booking_token}', 'Web\GuestCheckInController@guest_checkin_no_camera');
Route::post('/organise_your_trip', 'Web\GuestCheckInController@organise_your_trip')->name('web.organise_your_trip');
Route::post('/check_in_complete', 'Web\GuestCheckInController@check_in_complete')->name('web.check_in_complete');

Route::post('/forget_update_password_form', 'Web\HomeController@forget_update_password_form')->name('forget_update_password_form');



Route::post('/provinceStoreBefour','Web\BecomeAHostController@provinceStore')->name('web.become_a_host.provinceStore_befour');
Route::get('area/show_provinceBefour/{country_id}/{province_id?}','Web\StaticPageController@show_province')->name('area.show_province_before');

Route::post('/cityStoreBefoure','Web\BecomeAHostController@cityStore')->name('web.become_a_host.cityStore_befour');
Route::post('/areaStoreBefoure','Web\BecomeAHostController@areaStore')->name('web.become_a_host.areaStore_befour');

Route::get('area/show_city_befour/{country_id}/{province_id}/{city_id?}','Web\StaticPageController@show_city')->name('area.show_city_befour');


Route::group(['middleware' => 'auth.web'], function () {
    Route::get('/my_account', 'Web\HomeController@my_account')->name('web.my_account');
    Route::get('/favorites', 'Web\HomeController@my_favorites')->name('web.my_favorites');
    Route::get('/bookings', 'Web\HomeController@my_bookings')->name('web.my_bookings');
    Route::get('/reservations', 'Web\HomeController@my_reservations')->name('web.my_reservations');
    // Route::get('/bookings/{booking_id}', 'Web\HomeController@booking_detail_without_auth'); /////without login
    Route::post('/filtered_bookings', 'Web\HomeController@filtered_bookings')->name('web.filtered_bookings');
    Route::get('/booking-details', 'Web\HomeController@booking_details');
    Route::get('web/logout', 'Web\AuthController@web_logout')->name('web.logout');
    Route::get('my_card', 'Web\HomeController@my_card')->name('web.my_card');
    Route::post('update_profile', 'Web\HomeController@update_profile')->name('web.update_profile');
    Route::post('update_royalty_form', 'Web\HomeController@update_royalty_form')->name('web.update_royalty_form');
    Route::post('/addToWishList', 'Web\HomeController@addToWishList')->name('web.addToWishList');
    Route::post('/share_booking_form', 'Web\HomeController@share_booking_form')->name('web.share_booking_form');
    Route::post('/card_form', 'Web\HomeController@store_card')->name('web.card_form');
    Route::post('edit_card_form', 'Web\HomeController@edit_card_form')->name('web.edit_card_form');
    Route::post('deleteCard', 'Web\HomeController@deleteCard')->name('web.deleteCard');
    Route::post('defaultCard', 'Web\HomeController@defaultCard')->name('web.defaultCard');
    Route::post('update_password_form', 'Web\HomeController@update_password_form')->name('web.update_password_form');

    // Route::get('/become_a_host', 'Web\BecomeAHostController@become_a_host')->name('web.become_a_host');
    Route::get('/become_a_host_type', 'Web\BecomeAHostController@become_a_host_type')->name('web.become_a_host.host_type');
    Route::get('/host_category/{type}', 'Web\BecomeAHostController@host_category');
    // Route::get('/host_category/{type}', 'Web\BecomeAHostController@host_category')->name('web.become_a_host.host_category');
    Route::post('/provinceStore','Web\BecomeAHostController@provinceStore')->name('web.become_a_host.provinceStore');
    Route::post('/cityStore','Web\BecomeAHostController@cityStore')->name('web.become_a_host.cityStore');
    Route::post('/areaStore','Web\BecomeAHostController@areaStore')->name('web.become_a_host.areaStore');
    Route::post('/buildingStore','Web\BecomeAHostController@buildingStore')->name('web.become_a_host.buildingStore');
    Route::get('/host_address', 'Web\BecomeAHostController@host_address')->name('web.become_a_host.host_address');
    Route::get('/host_guest', 'Web\BecomeAHostController@host_guest')->name('web.become_a_host.host_guest');
    Route::get('/host_amenity', 'Web\BecomeAHostController@host_amenity')->name('web.become_a_host.host_amenity');
    Route::get('/host_max_guest', 'Web\BecomeAHostController@host_max_guest')->name('web.become_a_host.host_max_guest');
    Route::get('/host_title', 'Web\BecomeAHostController@host_title')->name('web.become_a_host.host_title');
    Route::get('/host_description', 'Web\BecomeAHostController@host_description')->name('web.become_a_host.host_description');
    Route::get('/host_extra_services', 'Web\BecomeAHostController@host_extra_services')->name('web.become_a_host.host_extra_services');
    Route::get('/host_price', 'Web\BecomeAHostController@host_price')->name('web.become_a_host.host_price');
    Route::get('/host_images', 'Web\BecomeAHostController@host_images')->name('web.become_a_host.host_images');
    Route::post('images_upload', 'Web\BecomeAHostController@images_upload')->name('web.images_upload');
    Route::post('images_upload_compress', 'Web\BecomeAHostController@images_upload_compress')->name('web.images_upload_compress');
    Route::post('host_submit_form', 'Web\BecomeAHostController@host_submit_form')->name('web.host_submit_form');
    Route::get('host_profile', 'Web\BecomeAHostController@host_profile')->name('web.host_profile');
    // Route::post('/host_address', 'Web\BecomeAHostController@host_address')->name('web.become_a_host.host_address');
    Route::post('store_become_a_host_type', 'Web\BecomeAHostController@store_become_a_host_type')->name('web.store_become_a_host_type');
    Route::post('getLatLongByCountry', 'Web\BecomeAHostController@getLatLongByCountry')->name('web.getLatLongByCountry');

    //Booking Section
    Route::post('addToCart', 'Web\HomeController@addToCart')->name('web.addToCart');
    Route::post('addToReserve', 'Web\HomeController@addToReserve')->name('web.addToReserve');
    Route::get('checkout', 'Web\HomeController@checkout')->name('web.checkout');
    Route::get('checkout_reserve', 'Web\HomeController@checkout_reserve')->name('web.checkout_reserve');
    Route::get('reserve-booking-checkout/{booking_id}', 'Web\HomeController@reserveBookingCheckout');
    Route::post('checkCouponCode', 'Web\HomeController@checkCouponCode')->name('web.checkCouponCode');
    Route::post('checkout_form', 'Web\HomeController@checkout_form')->name('web.checkout_form');
    Route::post('cancelBooking', 'Web\HomeController@cancelBooking')->name('web.cancelBooking');
    Route::post('rating_form', 'Web\HomeController@rating_form')->name('web.rating_form');
    Route::post('reBooking', 'Web\HomeController@reBooking')->name('web.reBooking');

    Route::get('booking-rating-form/{booking_id}', 'Web\HomeController@bookingRatingForm')->name('web.bookingRatingForm');


    Route::post('booking-rating-submit', 'Web\HomeController@bookingRatingSubmit')->name('web.bookingRatingSubmit');

    

    //Notification section
    Route::get('/notifications', 'Web\HomeController@my_notifications')->name('web.my_notifications');
    Route::post('/notifications/clearAllNotification', 'Web\HomeController@clearAllNotification')->name('web.clearAllNotification');
    Route::post('/notifications/readAllNotification', 'Web\HomeController@readAllNotification')->name('web.readAllNotification');

    //Chat manager
    Route::get('chat','Web\ChatController@index')->name('web.chat');
    Route::get('chat-detail/{id}','Web\ChatController@chatDetail')->name('web.chat-detail');
});

Route::get('cron/job', 'Web\HomeController@cronJob')->name('web.cronJob');
Route::get('cron/job/notification', 'Web\HomeController@cronJobNotification')->name('web.cronJobNotification');
