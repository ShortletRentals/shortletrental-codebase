<?php

/****************************************************/
// Developer By @Inventcolabs.com
/****************************************************/

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
// use App\User;
use App\Models\Media;
use App\Models\UsersAddress;
use App\Models\Cart;
use App\Models\Booking;
use App\Models\Discount;
use App\Models\Notification;
use App\Models\DiscountReadUsers;
use App\Models\PropertyReserveRequest;
use Illuminate\Support\Facades\App;
use App\Models\Review;
use App\Models\Wishlist;
use App\Models\Transaction;
use App\Models\UserCard;
use App\Models\Property;
use App\Models\PropertyCategory;
use App\Models\User;
use App\Models\Rating;
use App\Models\Chat;
use App\Models\Subscription;
use App\Models\AdminSettings;
use App\Models\UserLoyaltyPoint;
use App\Models\EmailTemplateLang;
use App\Models\PropertyBlockDate;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

use DateTime;
use Illuminate\Support\Facades\Log;


class ApiController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:api');
        $this->middleware('auth:api', ['except' => ['']]);

        define("A_TO_Z", 'a_to_z');
        define("Z_TO_A", 'z_to_a');
        $this->radius = 100;
    }

    public function getRandomCode()
    {
        $userData = auth()->user();
        $userId =  $userData->id;
        $date = new \DateTime();
        $tz = new \DateTimeZone('Asia/Kolkata');
        $dt = new \DateTime(date('Y-m-d H:i:s'));
        $dt->setTimezone($tz);
        $dateNew = $dt->format('Y-m-d H:i:s');
        $dateNewFormat = $dt->format('Y-m-d');
        $getDiscount = Discount::where(['status' => 1])->where('category_type', '!=', 'Info')->where('valid_upto', '>=', $dateNewFormat)->inRandomOrder()->first();

        if ($getDiscount) {
            $response['status'] = true;
            $response['data'] = $getDiscount;
            return response()->json($response, 200);
        } else {
            $response['status'] = false;
            $response['message'] = 'Offer Not Found.';
            return response()->json($response, 200);
        }
    }

    public function shareBooking(Request $request) {
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'country_code' => 'required',
            'mobile' => 'required|digits:10',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors()->first();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {
            $booking_id = $input['booking_id'];
            if(isset($booking_id) && !empty($booking_id)){
                $booking = Booking::where('id',$booking_id)->first();
                if(isset($booking) && !empty($booking)){
                    $response['status'] = true;
                    $response['message'] = 'Booking share successfully.';
                }else{
                    $response['status'] = false;
                    $response['message'] = 'Booking not found.';
                }
            }else{
                $response['status'] = false;
                $response['message'] = 'Booking id is required.';
            }
            // $result = ApiCurlMethod('store-card', $input, 'Bearer', 'POST');
            return response()->json($response, 200);
        }
    }

    public function update_profile(Request $request){
        $serachData = $request->all();
        $userData = auth()->user();
        $userId = $userData->id;
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|:users,email',
            'country_id' => 'required',
            // 'province_id' => 'required',
            // 'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            $checkCard = User::where(['id'=>$userId])->first();

            if ($checkCard) {
                $file = $request->file('image');
                $old_data = User::where('id',$userId)->first();
                $id_file = $request->file('id_card_image');
                // dd($id_file);
                if(isset($id_file)){
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'notification/'.$newFolder; 
                    $id_result1 =  fileUploads('s3',$id_file,$folderPath,false);
                    $id_result = $id_result1['file'];
                }else{
                    $id_result = $old_data->id_card_image;
                }
                // dd($id_result);
                // dd($file);
                $document_file = $request->file('document_image');
                if(isset($input['dob']) && $input['dob'] != null){
                    $dob = date('y/m/d', strtotime($input['dob']));
                }else{
                    $dob = null;
                }

                if (isset($file)) {
        
                    $newFolder  = strtoupper(date('M') . date('Y'));
                    $folderPath	=	'user/'.$newFolder; 
                    $result =  fileUploads('s3',$file,$folderPath,false);

                    if(isset($document_file)){
                       
                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath	=	'user/'.$newFolder; 
                        $document_result =  fileUploads('s3',$document_file,$folderPath,false);

                        
                        $data = User::where('id', $userId)->update([
                            'title' => $input['title'], 
                            'name' => $input['name'], 
                            'email' => $input['email'], 
                            'surname' => $input['surname'], 
                            'remarks' => $input['remarks']??null, 
                            'is_super_host' => $input['is_super_host']??null, 
                            'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host']??null, 
                            'number' => $input['number']??null, 
                            'postal_code' => $input['postal_code'], 
                            'country_id' => $input['country_id'], 
                            'province_id' => $input['province_id'], 
                            'city_id' => $input['city_id'], 

                            'address' => $input['address'], 
                            'latitude' => $input['latitude'], 
                            'longitude' => $input['longitude'], 
                            

                            'image' => $result['file'], 
                            'secondary_email' => $input['secondary_email']??null, 
                            'second_country_code' => $input['second_country_code']??null, 
                            'second_mobile' => $input['second_mobile']??null, 
                            'dob' => $dob,
                            'document_number' => $input['document_number']??null,
                            'gender' => $input['gender'],
                            'marital_status' => $input['marital_status'],
                            'image_type' => 'local', 
                            'document_image' => $document_result['file'], 
                            'id_card_image'=>$id_result ]);
                    }else{
                        $data = User::where('id', $userId)->update(['title' => $input['title'], 'name' => $input['name'], 'email' => $input['email'], 'surname' => $input['surname'], 'remarks' => $input['remarks']??null, 'is_super_host' => $input['is_super_host']??null, 'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host']??null, 'number' => $input['number']??null, 'postal_code' => $input['postal_code'], 'country_id' => $input['country_id'], 'province_id' => $input['province_id'], 'city_id' => $input['city_id'], 'image' => $result[1], 'secondary_email' => $input['secondary_email']??null, 'second_country_code' => $input['second_country_code']??null, 'second_mobile' => $input['second_mobile']??null, 'dob' => $dob,'gender' => $input['gender'],'marital_status' => $input['marital_status'],'image_type' => 'local','document_number' => $input['document_number']??null, 'id_card_image'=>$id_result ]);
                    }
                } else {
                    if(isset($document_file)){
                      //'street' => $input['street'], 'street_number' => $input['street_number'],
                      
                        $newFolder  = strtoupper(date('M') . date('Y'));
                        $folderPath	=	'user/'.$newFolder; 
                        $document_result =  fileUploads('s3',$document_file,$folderPath,false);
                        $data = User::where('id', $userId)->update([
                            'title' => $input['title'], 
                            'name' => $input['name'], 
                            'email' => $input['email'], 
                            'surname' => $input['surname'], 
                            'remarks' => $input['remarks']??null, 
                            'is_super_host' => $input['is_super_host']??null, 
                            'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host']??null, 
                            'number' => $input['number']??null, 
                            'postal_code' => $input['postal_code'], 
                            'country_id' => $input['country_id'], 
                            'province_id' => $input['province_id'], 
                            'city_id' => $input['city_id'], 
                            
                            'address' => $input['address'], 
                            'latitude' => $input['latitude'], 
                            'longitude' => $input['longitude'], 
                            
                            'secondary_email' => $input['secondary_email']??null, 
                            'second_country_code' => $input['second_country_code']??null, 
                            'second_mobile' => $input['second_mobile']??null, 
                            'dob' => $dob,
                            'document_number' => $input['document_number']??null,
                            'gender' => $input['gender'],
                            'marital_status' => $input['marital_status'], 
                            'document_image' => $document_result['file'], 
                            'id_card_image'=>$id_result ]);        
                    }else{
                       
                        $data = User::where('id', $userId)->update([
                            'title' => $input['title'], 
                            'name' => $input['name'], 
                            'email' => $input['email'], 
                            'surname' => $input['surname'], 
                            'remarks' => $input['remarks']??null, 
                            'is_super_host' => $input['is_super_host']??null, 
                            'is_chat_disabled_for_host' => $input['is_chat_disabled_for_host']??null, 
                            'number' => $input['number']??null, 
                            'postal_code' => $input['postal_code'], 
                            'country_id' => $input['country_id'], 
                            'province_id' => $input['province_id'], 
                            'city_id' => $input['city_id'], 


                            'address' => $input['address'], 
                            'latitude' => $input['latitude'], 
                            'longitude' => $input['longitude'], 
                                    
                            'secondary_email' => $input['secondary_email']??null, 
                            'second_country_code' => $input['second_country_code']??null, 
                            'second_mobile' => $input['second_mobile']??null, 
                            'dob' => $dob,
                            'document_number' => $input['document_number']??null,
                            'gender' => $input['gender'],
                            'marital_status' => $input['marital_status'], 
                            'id_card_image'=>$id_result ]
                        );
                    }
                }
                $response['status'] = true;
                $response['message'] = 'Profile updated successfully.';
                return response()->json($response, 200);
            } else {
                $response['status'] = false;
                $response['message'] = 'Profile not updated.';
                return response()->json($response, 200);
            }
        }
    }

    public function update_loyalty(Request $request){
        $serachData = $request->all();
        $userData = auth()->user();
        $userId = $userData->id;
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'email' => 'required|:subscriptions,email',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            $checkCard = Subscription::where(['type'=>$input['type'], 'user_id'=>$userId, 'email'=>$input['email']])->first();

            if ($checkCard) {
                $data = Subscription::where('type', $input['type'])->update(['email' => $input['email'] ]);

                $response['status'] = true;
                if(isset($input['type']) && $input['type'] == 'Loyalty'){
                    $response['message'] = 'Loyalty points updated successfully.';
                }else{
                    $response['message'] = 'Newsletter updated successfully.';
                }
                return response()->json($response, 200);
            } else {
                $data = new Subscription();
                $data->type = $input['type'];
                $data->user_id = $userId;
                $data->email = $input['email'];
                $data->save();

                $response['status'] = true;
                if(isset($input['type']) && $input['type'] == 'Loyalty'){
                    $response['message'] = 'Loyalty points updated successfully.';
                }else{
                    $response['message'] = 'Newsletter updated successfully.';
                }
                return response()->json($response, 200);
            }
        }
    }

    public function store_card(Request $request){
        $serachData = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'card_holder_name' => 'required',
            'card_number' => 'required',
            'month' => 'required|min:1',
            'year' => 'required|min:4',
            'cvv' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            $checkCard = UserCard::where(['user_id'=>$userId, 'card_number'=>$request['card_number']])->first();

            if ($checkCard) {
                $response['status'] = false;
                $response['message'] = 'Card Already added.';
                return response()->json($response, 200);

            } else {
                $insertData = new UserCard();
                $insertData->user_id = $userId;
                $insertData->card_holder_name = $request['card_holder_name'];
                $insertData->card_number = $request['card_number'];
                $insertData->month = $request['month'];
                $insertData->year = $request['year'];
                $insertData->cvv = $request['cvv'];
                $insertData->save();

                $response['status'] = true;
                $response['message'] = 'Card added successfully.';
                return response()->json($response, 200);
            }
        }
    }

    public function update_card(Request $request){
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'card_id' => 'required',
            'card_holder_name' => 'required',
            'card_number' => 'required',
            'month' => 'required|min:2',
            'year' => 'required|min:4',
            'cvv' => 'required|min:3',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            $request = $request->all();
            $checkCard = UserCard::where(['user_id'=>$userId, 'id'=>$request['card_id']])->first();

            if (!$checkCard) {
                $response['status'] = false;
                $response['message'] = 'Invalid Card.';
                return response()->json($response, 200);

            } else {
                $checkCard->card_holder_name = $request['card_holder_name'];
                $checkCard->card_number = $request['card_number'];
                $checkCard->month = $request['month'];
                $checkCard->year = $request['year'];
                $checkCard->cvv = $request['cvv'];
                $checkCard->save();

                $response['status'] = true;
                $response['message'] = 'Card updated successfully.';
                return response()->json($response, 200);
            }
        }
    }

    public function deleteCard(Request $request){
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'card_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            $request = $request->all();
            $checkCard = UserCard::where(['user_id'=>$userId, 'id'=>$request['card_id']])->first();

            if (!$checkCard) {
                $response['status'] = false;
                $response['message'] = 'Invalid Card.';
                return response()->json($response, 200);

            } else {
                $checkCard->delete();
                $response['status'] = true;
                $response['message'] = 'Card deleted successfully.';
                return response()->json($response, 200);
            }
        }
    }

    public function defaultCard(Request $request){
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'card_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            $request = $request->all();
            $checkCard = UserCard::where(['user_id'=>$userId, 'id'=>$request['card_id']])->first();

            if (!$checkCard) {
                $response['status'] = false;
                $response['message'] = 'Invalid Card.';
                return response()->json($response, 200);

            } else {
                $updateData = [
                    'defalult_card' => 0,
                ];
                UserCard::where(['user_id'=>$userId])->update($updateData);

                $checkCard->defalult_card = 1;
                $checkCard->save();
                $response['status'] = true;
                $response['message'] = 'Default card updated successfully.';
                return response()->json($response, 200);
            }
        }
    }

    public function get_store_card(Request $request)
    {
        $serachData = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;
        
        $cardData = UserCard::where(['user_id'=>$userId])->get();

        if (!$cardData) {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);

        } else {
            $response['status'] = true;
            $response['message'] = 'Data found successfully.';
            $response['data'] = $cardData;
            return response()->json($response, 200);
        }
    }

    public function addToWishList(Request $request)
    {
        $serachData = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'userId' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {
            $checkAlreadyWishlist = Wishlist::where(['user_id'=>$userId, 'product_id'=>$request['product_id']])->first();

            if ($checkAlreadyWishlist) {
                $checkAlreadyWishlist->delete();
                $response['status'] = false;
                $response['message'] = 'Removed from Wishlist.';
                return response()->json($response, 200);

            } else {
                $insertData = new Wishlist();
                $insertData->user_id = $userId;
                $insertData->product_id = $request['product_id'];
                $insertData->save();
                $response['status'] = true;
                $response['message'] = 'Wishlist added.';
                return response()->json($response, 200);
            }
        }
    }

    public function my_favorites(Request $request)
    {
        $serachData = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;
        
        $wishlistData = Wishlist::select('wishlists.*','properties.id')->where(['user_id'=>$userId])->with('getProperty.getPropertyAddress','getProperty.getPropertyBedroom','getProperty.getPropertyImages')->join('properties','properties.id','=','wishlists.product_id')->get();

        if (!$wishlistData) {
            $response['status'] = false;
            $response['message'] = 'Data not found.';
            return response()->json($response, 200);

        } else {
            $response['status'] = true;
            $response['message'] = 'Data found successfully.';
            $response['data'] = $wishlistData;
            return response()->json($response, 200);
        }
    }

    public function notificationList(Request $request)
    {
        $locale = App::getLocale();
        $inputData = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;
        $notificationData = Notification::select('notifications.*')->with('getProperty')
            ->where(['notifications.user_id' => $userId])
            ->orderBy('notifications.id', 'desc')
            ->get();
        if (count($notificationData)) {
            $response['status'] = true;
            $response['data'] = $notificationData;
        } else {
            $response['status'] = false;
            $response['message'] = 'No record found.';
        }
        return response()->json($response, 200);
    }
    public function getNotificationCount(Request $request)
    {
        $userData = auth()->user();
        $userId =  $userData->id;
        $locale = App::getLocale();
        $notificationData =  Notification::where(['user_id' => $userId, 'is_read' => 0])->count();
        if ($notificationData) {
            $response['status'] = true;
            $response['data'] = $notificationData;
        } else {
            $response['status'] = true;
            $response['data'] = 0;
        }
        return response()->json($response, 200);
    }

    public function removeNotification(Request $request)
    {
        $inputData = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;

        if (!isset($inputData['notification_id'])) {
            $notificationData = Notification::where(['user_id' => $userId])->delete();
            $response['status'] = true;
            $response['message'] = __("api.Notification_all_removed_successfully");
            return response()->json($response, 200);

        } else {
            $notificationData = Notification::where(['id' => $inputData['notification_id'], 'user_id' => $userId])->first();

            if ($notificationData) {
                $notificationData->delete();
                $response['status'] = true;
                $response['message'] = __("api.Notification_removed_successfully");

            } else {
                $response['status'] = false;
                $response['message'] = __("api.Invalid_notification_id");
            }
            return response()->json($response, 200);
        }
    }

    public function readNotification(Request $request)
    {
        $inputData = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;
        if (!isset($inputData['notification_id'])) {
            $updateData = [
                'is_read' => 1,
            ];
            Notification::where(['user_id' => $userId])->update($updateData);
            $response['status'] = true;
            $response['message'] = __("api.Notification_all_read_successfully");


            return response()->json($response, 200);
        } else {
            $notificationData = Notification::where(['id' => $inputData['notification_id'], 'user_id' => $userId])->first();
            if ($notificationData) {
                $updateData = [
                    'is_read' => 1,
                ];
                $notificationData->update($updateData);
                $response['status'] = true;
                $response['message'] = __("api.Notification_read_successfully");
            } else {
                $response['status'] = false;
                $response['message'] = __("api.Invalid_notification_id");
            }
            return response()->json($response, 200);
        }
    }

    public function addcart(Request $request) {
        try{
            $input = $request->all();
            $userData = auth()->user();
            $userId =  $userData->id;

            $validator = Validator::make($request->all(), [
                'property_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'total_days' => 'required',
                'per_night_price' => 'required',
                'total_booking_amount' => 'required',
                'adultCount' => 'required',
                'childCount' => 'required',
                'infantCount' => 'required',
                'petCount' => 'required',
            ]);

            if ($validator->fails()) {
                $errors     =   $validator->errors()->first();
                $response['status'] = false;
                $response['message'] = $errors;
                return response()->json($response, 200);

            } else {
                //Delete Old Card
                
                                 
                // $alreadybooking = Booking::where('property_id', $input['property_id'])->whereDate('from_date', '>=', $input['start_date'])->whereDate('to_date', '<=', $input['end_date'])->pluck('from_date','to_date')->toArray();
                $alreadybookingFrom = Booking::where('property_id', $input['property_id'])->where('booking_status', '!=', 'Cancelled-Booking')->whereBetween('from_date', [$input['start_date'], $input['end_date']])->pluck('from_date','to_date')->toArray();
                $alreadybookingTo = Booking::where('property_id', $input['property_id'])->where('booking_status', '!=', 'Cancelled-Booking')->whereBetween('to_date', [$input['start_date'], $input['end_date']])->pluck('from_date','to_date')->toArray();

                $alreadyBlockedDate = PropertyBlockDate::where('property_id', $input['property_id'])->whereBetween('block_date', [$input['start_date'], $input['end_date']])->pluck('block_date')->toArray();
                $propertyStatus = Property::where(['id'=> $input['property_id'], 'status' => 0])->pluck('id')->toArray();
                // dd($propertyStatus);
                if(!empty($alreadybookingFrom) || !empty($alreadybookingTo) || !empty($alreadyBlockedDate)){
                    $response['status'] = false;
                    $response['message'] = 'This accommodation is already booked for selected dates. Please choose another date.';
                }else{
                    if(!empty($propertyStatus)){
                        $response['status'] = false;
                        $response['message'] = 'This accommodation is no longer available.';
                    }else{
                        $total_guest = $input['adultCount'] + $input['childCount'] + $input['infantCount'];
                        $propertyMaxGuest = Property::where(['id'=> $input['property_id'], 'status' => 1])->pluck('max_guest')->first();
                        if(isset($propertyMaxGuest) && $propertyMaxGuest != 0){
                            if($total_guest > $propertyMaxGuest){
                                $response['status'] = false;
                                $response['message'] = 'You can select max guest '.$propertyMaxGuest.'.';
                            }else{

                              
                                Cart::where('user_id', $userId)->delete();
                                $data = new Cart();
                                $data->user_id = $userId;
                                $data->property_id = $input['property_id'];
                                $data->start_date = $input['start_date'];
                                $data->end_date = $input['end_date'];
                                $data->total_days = $input['total_days'];
                                $data->per_night_price = $input['per_night_price'];
                                $data->total_booking_amount = $input['total_booking_amount'];
                                $data->adultCount = $input['adultCount'] ?? 0;
                                $data->childCount = $input['childCount'] ?? 0;
                                $data->infantCount = $input['infantCount'] ?? 0;
                                $data->petCount = $input['petCount'] ?? 0;
                                $data->coupon_code = $input['coupon_code'] ?? null;
                                $data->discount_id = $input['discount_id'] ?? null;
                                $data->discount_amount = $input['discount_amount'] ?? null;
                
                                if ($data->save()) {
                                    $response['status'] = true;
                                    $response['message'] = 'Cart added successfully.';
                
                                } else {
                                    $response['status'] = false;
                                    $response['message'] = 'Something went wrong, Please try again.';
                                }
                            }
                        }else{
                            Cart::where('user_id', $userId)->delete();
                            $data = new Cart();
                            $data->user_id = $userId;
                            $data->property_id = $input['property_id'];
                            $data->start_date = $input['start_date'];
                            $data->end_date = $input['end_date'];
                            $data->total_days = $input['total_days'];
                            $data->per_night_price = $input['per_night_price'];
                            $data->total_booking_amount = $input['total_booking_amount'];
                            $data->adultCount = $input['adultCount'] ?? 0;
                            $data->childCount = $input['childCount'] ?? 0;
                            $data->infantCount = $input['infantCount'] ?? 0;
                            $data->petCount = $input['petCount'] ?? 0;
                            $data->coupon_code = $input['coupon_code'] ?? null;
                            $data->discount_id = $input['discount_id'] ?? null;
                            $data->discount_amount = $input['discount_amount'] ?? null;
            
                            if ($data->save()) {
                                $response['status'] = true;
                                $response['message'] = 'Cart added successfully.';
            
                            } else {
                                $response['status'] = false;
                                $response['message'] = 'Something went wrong, Please try again.';
                            }
                        }
                    }
                }
                
            }
        } catch (\Exception $ex) {
            $response['status'] = false;
            $response['message'] = "Something went wrong!";
            $response['data'] = null;
        }
        return response()->json($response, 200);
    }

    public function mobileBooking(Request $request) {
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'total_days' => 'required',
            'adultCount' => 'required',

            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'book_for' => 'required',
            'is_agency' => 'required',
            'payment_method' => 'required',

            'policy_read' => 'required',
            'total_booking_amount' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors()->first();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {
            $propertyDetails = Property::where('id',$input['property_id'])->first();
            if(isset($propertyDetails) && !empty($propertyDetails)){
                $alreadybookingFrom = Booking::where('property_id', $input['property_id'])->where('booking_status', '!=', 'Cancelled-Booking')->whereBetween('from_date', [$input['start_date'], $input['end_date']])->pluck('from_date','to_date')->toArray();
                $alreadybookingTo = Booking::where('property_id', $input['property_id'])->where('booking_status', '!=', 'Cancelled-Booking')->whereBetween('to_date', [$input['start_date'], $input['end_date']])->pluck('from_date','to_date')->toArray();
    
                $alreadyBlockedDate = PropertyBlockDate::where('property_id', $input['property_id'])->whereBetween('block_date', [$input['start_date'], $input['end_date']])->pluck('block_date')->toArray();
                $propertyStatus = Property::where(['id'=> $input['property_id'], 'status' => 0])->pluck('id')->toArray();
                // dd($propertyStatus);
                if(!empty($alreadybookingFrom) || !empty($alreadybookingTo) || !empty($alreadyBlockedDate)){
                    $response['status'] = false;
                    $response['message'] = 'This accommodation is already booked for selected dates. Please choose another date.';
                }else{
                    if(!empty($propertyStatus)){
                        $response['status'] = false;
                        $response['message'] = 'This accommodation is no longer available.';
                    }else{
                        $total_guest = $input['adultCount'] + $input['childCount'] + $input['infantCount'];
                        $propertyMaxGuest = Property::where(['id'=> $input['property_id'], 'status' => 1])->pluck('max_guest')->first();
                        if(isset($propertyMaxGuest) && $propertyMaxGuest != 0){
                            if(isset($total_guest) && $total_guest  > 0){
                                if($total_guest > $propertyMaxGuest){
                                    $response['status'] = false;
                                    $response['message'] = 'You can select max guest '.$propertyMaxGuest.'.';
                                }else{
                                    $host_amount = $propertyDetails->price * $input['total_days'];
                                    $data = new Booking();
                        
                                    if (isset($input['discount_code']) && $input['discount_code']) {
                                        $checkCode = Discount::where('code', $input['discount_code'])->first();
                                        $data->influencer_id = $checkCode->influencer;
                                        $influencer_commission_percent = User::where('id',$checkCode->influencer)->pluck('commission')->first();
                                        if(isset($influencer_commission_percent) && !empty($influencer_commission_percent)){
                                            $data->influencer_amount = $host_amount * $influencer_commission_percent / 100;
                                        }
                                    }
                        
                                    $optional_service_amount = 0;
                        
                                    if (isset($input['selected_options']) && $input['selected_options']) {
                                        $optional_services = json_decode($input['selected_options']);
                                        if ($optional_services) {
                                            foreach ($optional_services as $key => $value) {
                                                $optional_service_amount = $optional_service_amount + $value->value;
                                            }
                                        }
                                    }
                        
                                    if(isset($input['redeem_royalty_points']) && $input['redeem_royalty_points'] == 'Yes'){
                                        $data->loyalty_points = $input['loyalty_points'] ?? null;
                                        $data->loyalty_amount = $input['loyalty_amount'] ?? null;
                                    }
                                    $admin_amount = 0;
                                    if(isset($adminCommission) && !empty($adminCommission)){
                                        $propertyData = DB::table('properties')->where(['id'=>$cardData->property_id])->pluck('price')->first();
                                        $admin_amount1 = $propertyData * $adminCommission / 100;
                                        $admin_amount = $admin_amount1 * $input['total_days'];
                                    }
                                    $admin_data = AdminSettings::first();
                                    $loyalty_percentage = isset($admin_data) && !empty($admin_data->loyalty_percentage) ? $admin_data->loyalty_percentage : '';

                                    if (isset($loyalty_percentage) && !empty($loyalty_percentage)) {
                                        $loyalty_point = $input['total_booking_amount'] * $loyalty_percentage / 100;
                                        $loyalty_point_round = intval(round( $loyalty_point ));
                                    }else{
                                        $loyalty_point_round = Null;
                                    }
                                    $data->new_loyalty_amount = $loyalty_point_round ?? null;
                                    $data->guest_id = $userId;
                                    $data->host_id = $propertyDetails->host;
                                    $data->property_id = $input['property_id'];
                                    $data->from_date = $input['start_date'];
                                    $data->to_date = $input['end_date'];
                                    $data->total_amount = $input['total_booking_amount'];
                                    $data->admin_amount = $admin_amount;
                                    $data->host_amount = $host_amount;
                                    $data->security_deposite = $propertyDetails->security_deposit_amount;
                                    if(isset($input['payment_method']) && $input['payment_method'] == 'card' ){
                                        $data->booking_status = 'Confirmed-by-Host';
                                    }else{
                                        $data->booking_status = 'Not-confirmed-by-Host';
                                    }
                                    // $data->booking_status = 'Not-confirmed-by-Host';
                                    $data->booking_type = 'Paid';
                                    $data->no_of_adult_guest = $input['adultCount'] ?? 0;
                                    $data->no_of_children_guest = $input['childCount'] ?? 0;
                                    $data->no_of_babies_guest = $input['infantCount'] ?? 0;
                                    $data->no_of_pet = $input['petCount'] ?? 0;
                                    $data->total_days = $input['total_days'];
                                    $data->per_night_price = $propertyDetails->price;
                                    $data->total_booking_amount = $input['total_booking_amount'];
                                    $data->coupon_code = $input['discount_code'] ?? null;
                                    $data->discount_id = $input['discount_id'] ?? null;
                                    $data->discount_amount = $input['discount_amount'] ?? null;
                                    $data->is_agency = $input['is_agency'] ?? null;
                                    $data->payment_method = $input['payment_method'] ?? null;
                                    $data->book_for = $input['book_for'] ?? null;
                                    $data->send_special_offer = $input['send_special_offer'] ?? null;
                                    $data->policy_read = $input['policy_read'] ?? null;
                                    $data->personal_first_name = $input['first_name'] ?? null;
                                    $data->personal_last_name = $input['last_name'] ?? null;
                                    $data->personal_address = $input['address'] ?? null;
                                    $data->personal_country_id = $input['country_id'] ?? null;
                                    $data->personal_city = $input['city'] ?? null;
                                    $data->personal_postal_code = $input['postal_code'] ?? null;
                                    $data->personal_phone_number = $input['phone_number'] ?? null;
                                    $data->personal_email = $input['email'] ?? null;
                                    $data->personal_comment = $input['comment'] ?? null;
                                    $data->guest_first_name = $input['guest_first_name'] ?? null;
                                    $data->guest_last_name = $input['guest_last_name'] ?? null;
                                    $data->guest_address = $input['guest_address'] ?? null;
                                    $data->guest_city = $input['guest_city'] ?? null;
                                    $data->guest_zipcode = $input['guest_zipcode'] ?? null;
                                    $data->guest_country_id = $input['guest_country_id'] ?? null;
                                    $data->guest_phone_number = $input['guest_phone_number'] ?? null;
                                    $data->guest_email = $input['guest_email'] ?? null;
                                    $data->card_holder_name = $input['card_holder_name'] ?? null;
                                    $data->card_number = $input['card_number'] ?? null;
                                    $data->month = $input['month'] ?? null;
                                    $data->year = $input['year'] ?? null;
                                    $data->cvv = $input['cvv'] ?? null;
                                    $data->selected_options = $input['selected_options'] ?? null;
                                    $data->optional_service_amount = $optional_service_amount;
                                    $data->booking_from = 'Mobile';
                        
                                    if($input['property_id']){
                                        $propertyDetail = DB::table('properties')->where('id',$input['property_id'])->first();
                                        if(isset($propertyDetail) && !empty($propertyDetail)){
                                            $price = $propertyDetail->price;
                                            $tax = $propertyDetail->tax;
                                            if(isset($price) && !empty($price)){
                        
                                                $commission = AdminSettings::pluck('commission')->first();
                                                if(isset($commission) && !empty($commission)){
                                                    $price1 = $price * $commission / 100;
                                                    $price = $price1 + $price;
                                                    if(isset($tax) && !empty($tax)){
                                                        $tax_price = $price * $tax / 100;
                                                        // $price = $tax_price + $price;
                                                    }else{
                                                        $tax_price = null;
                                                    }
                                                }else{
                                                    if(isset($tax) && !empty($tax)){
                                                        $tax_price = $price * $tax / 100;
                                                        // $tax_price = $tax_price + $price;
                                                    }else{
                                                        $tax_price = null;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if(isset($tax_price) && $tax_price != null){
                                        $data->tax_amount = $tax_price * $input['total_days'];
                                    }
                                    if ($data->save()) {
                                        DB::table('json')->insert([
                                            'json' => json_encode($input),
                                        ]);
                                        if(isset($input['booking_reserve']) && $input['booking_reserve'] == 'reserve' ){
                                            PropertyReserveRequest::where(['guest_id'=>$userId, 'property_id'=>$input['property_id'] ])->delete();
                                            if(isset($input['notification_id']) && !empty($input['notification_id'])){
                                                Notification::where('id',$input['notification_id'])->delete();
                                            }
                                        }
                                        $chat = new Chat;
                                        $chat->sender_id = $propertyDetails->host;
                                        $chat->receiver_id = $userId;
                                        $chat->booking_id = $data->id;
                                        $chat->save();
                        
                                        if(isset($input['property_id'])){
                                            $booking_token = Property::where('id',$input['property_id'])->pluck('code')->first();
                                        }else{
                                            $booking_token = null;
                                        }
                                        $booking_id = 'SHTRNTL'.$data->id.'-'.$booking_token;
                                        // dd(Crypt::encryptString($booking_id));
                                        // dd(encrypt($booking_id));
                                        $booking_token = Crypt::encryptString($booking_id);
                                        Booking::where('id', $data->id)->update(['booking_id' => $booking_id, 'booking_token' => $booking_token]);
                        
                                        $booking_data = Booking::where('id', $data->id)->with('getProperty')->first();
                        
                                        $admin_data = AdminSettings::first();
                                        $loyalty_percentage = isset($admin_data) && !empty($admin_data->loyalty_percentage) ? $admin_data->loyalty_percentage : '';
                        
                                        // $payment_amount = $booking_data->total_amount + $optional_service_amount;
                                        // if (isset($loyalty_percentage) && !empty($loyalty_percentage)) {
                                        //     $loyalty_point = $payment_amount * $loyalty_percentage / 100;
                                        //     $loyalty_point_round = intval(round( $loyalty_point ));
                        
                                        //     // dd($userId, $booking_data->id, $loyalty_point_round);
                                        //     $loyalty = new UserLoyaltyPoint;
                                        //     $loyalty->user_id = $userId;
                                        //     $loyalty->order_id = $booking_data->id;
                                        //     $loyalty->points = $loyalty_point_round;
                                        //     $loyalty->type = 'Credit';
                                        //     $loyalty->title = 'You have got '.$loyalty_point_round.' Loyalty points.';
                                        //     $loyalty->save();
                                        // }else{
                                        //     $loyalty_point_round = '';
                                        // }
                                        if(isset($input['redeem_royalty_points']) && $input['redeem_royalty_points'] == 'Yes'){
                                            if(isset($input['loyalty_points']) && !empty($input['loyalty_amount']) ){
                                                $loyalty = new UserLoyaltyPoint;
                                                $loyalty->user_id = $userId;
                                                $loyalty->order_id = $booking_data->id;
                                                $loyalty->points = $input['loyalty_points'];
                                                $loyalty->type = 'Debit';
                                                $loyalty->title = 'You have redeem '.$input['loyalty_points'].' in NGN '.$input['loyalty_amount'].'.';
                                                $loyalty->save();
                                            }
                                        }
                        
                                        // $admin_data = AdminSettings::first();
                                        // $loyalty_percentage = isset($admin_data) && !empty($admin_data->loyalty_percentage) ? $admin_data->loyalty_percentage : '';
                                        // if(isset($loyalty_percentage) && !empty($loyalty_percentage)){
                                        //     $loyalty_point = $payment_amount * $loyalty_percentage / 100;
                                        //     $loyalty_point_round = intval(round( $loyalty_point ));
                        
                                        //     // dd($userId, $booking_data->id, $loyalty_point_round);
                                        //     $loyalty = new UserLoyaltyPoint;
                                        //     $loyalty->user_id = $userId;
                                        //     $loyalty->order_id = $booking_data->id;
                                        //     $loyalty->points = $loyalty_point_round;
                                        //     $loyalty->type = 'Credit';
                                        //     $loyalty->title = 'You have got '.$loyalty_point_round.' Loyalty points.';
                                        //     $loyalty->save();
                                        // }else{
                                        //     $loyalty_point_round = '';
                                        // }
                                        // send email start
                                        $email = EmailTemplateLang::where('email_id', 12)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                                        $subject = $email->subject;
                                        $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                                        $record = (object)[];
                                        $record->description = $email->description;
                                        $record->footer = $email->footer;
                                        $record->username = $input['first_name'];
                                        $record->property_name = $booking_data->getProperty->title;
                                        $record->subject = $subject;
                                        $record->user_email = $input['email'];
                                        $record->cardData = $booking_data;
                                        $record->check_in_url = url('guest-area/checkin/search');
                        
                                        Mail::send('emails.checkin_form', compact('record'), function ($message) use ($input, $subject) {
                                            $message->to($input['email'], config('app.name'))->subject($subject);
                                            $message->from('customersupport@shortletrenrals.com', config('app.name'));
                                        });
                                        // send email end
                        
                                        if (isset($userId)) {
                                            $userdata = User::where('id',$userId)->first();
                                            if(isset($userdata) && $userdata->notification_on_off == 'On'){
                                                $notificationData = new Notification;
                                                $notificationData->user_type = $userdata->user_type;
                                                $notificationData->notification_type = 1;
                                                $notificationData->notification_for = 'Booking';
                                                $notificationData->title = 'Booking Received.';
                                                $notificationData->message = 'Your booking has been received - '.$booking_id;
                                                $notificationData->user_id = $userId;
                                                $notificationData->property_id = $booking_data->property_id;
                                                $notificationData->order_id = $data->id;
                                                $notificationData->save();
                                                send_notification(1, $userId, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                                            }

                                            $notificationData = new Notification;
                                            $notificationData->user_type = 1;
                                            $notificationData->notification_type = 1;
                                            $notificationData->notification_for = 'Booking';
                                            $notificationData->title = 'Booking Received.';
                                            $notificationData->message = 'New booking has been received - '.$booking_id;
                                            $notificationData->user_id = 1;
                                            $notificationData->property_id = $booking_data->property_id; 
                                            $notificationData->order_id = $data->id;
                                            $notificationData->save();
                                            send_notification(1, 1, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                                            if(isset($booking_data->influencer_id) && !empty($booking_data->influencer_id)){
                                                $userdata_influencer = User::where('id',$booking_data->influencer_id)->first();
                                                $notificationData = new Notification;
                                                $notificationData->user_type = $userdata_influencer->user_type;
                                                $notificationData->notification_type = 1;
                                                $notificationData->notification_for = 'Booking';
                                                $notificationData->title = 'Booking Received.';
                                                $notificationData->message = 'New booking has been received - '.$booking_id;
                                                $notificationData->user_id = $userdata_influencer->id;
                                                $notificationData->property_id = $booking_data->property_id;
                                                $notificationData->order_id = $data->id;
                                                $notificationData->save();
                                                send_notification(1, $userdata_influencer->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                                            }
                        
                                            // $propertyHost = Property::where('id',$booking_data->property_id)->pluck('host_id')->first();
                                            if(isset($booking_data->host_id) && !empty($booking_data->host_id)){
                                                $Hostdata = User::where('id',$booking_data->host_id)->first();
                        
                                                $notificationHost = new Notification;
                                                $notificationHost->user_type = $Hostdata->user_type;
                                                $notificationHost->notification_type = 1;
                                                $notificationHost->notification_for = 'Booking';
                                                $notificationHost->title = 'Booking Received.';
                                                $notificationHost->message = 'You have got a new Booking - '.$booking_id;
                                                $notificationHost->user_id = $Hostdata->id;
                                                $notificationHost->property_id = $booking_data->property_id;
                                                $notificationHost->order_id = $data->id;
                                                $notificationHost->save();
                                            }
                                        }
                        
                                        $response['status'] = true;
                                        $response['message'] = 'Booking added successfully.';
                        
                                    } else {
                                        $response['status'] = false;
                                        $response['message'] = 'Something went wrong, Please try again.';
                                    }
                                }
                            }else{
                                $response['status'] = false;
                                $response['message'] = 'Please select atleast one guest.';
                            }
                        }
                    }
                }
            }else{
                $response['status'] = false;
                $response['message'] = 'Accommodation not found.';
            }
            return response()->json($response, 200);
        }
    }

    public function mobileBookingReserve(Request $request) {
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'total_days' => 'required',
            'adultCount' => 'required',

            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postal_code' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'book_for' => 'required',
            'is_agency' => 'required',
            // 'payment_method' => 'required',
            'policy_read' => 'required',
            'total_booking_amount' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors()->first();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {
            $propertyDetails = Property::where(['id'=>$input['property_id'], 'status'=>1])->first();
            if(isset($propertyDetails) && !empty($propertyDetails)){
                $host_amount = $propertyDetails->price * $input['total_days'];
                $alreadybookingFrom = Booking::where('property_id', $input['property_id'])->where('booking_status', '!=', 'Cancelled-Booking')->whereBetween('from_date', [$input['start_date'], $input['end_date']])->pluck('from_date','to_date')->toArray();
                $alreadybookingTo = Booking::where('property_id', $input['property_id'])->where('booking_status', '!=', 'Cancelled-Booking')->whereBetween('to_date', [$input['start_date'], $input['end_date']])->pluck('from_date','to_date')->toArray();
    
                $alreadyBlockedDate = PropertyBlockDate::where('property_id', $input['property_id'])->whereBetween('block_date', [$input['start_date'], $input['end_date']])->pluck('block_date')->toArray();
                $propertyStatus = Property::where(['id'=> $input['property_id'], 'status' => 0])->pluck('id')->toArray();
                // dd($propertyStatus);
                if(!empty($alreadybookingFrom) || !empty($alreadybookingTo) || !empty($alreadyBlockedDate)){
                    $response['status'] = false;
                    $response['message'] = 'This accommodation is already booked for selected dates. Please choose another date.';
                }else{
                    if(!empty($propertyStatus)){
                        $response['status'] = false;
                        $response['message'] = 'This accommodation is no longer available.';
                    }else{
                        PropertyReserveRequest::where(['guest_id'=>$userId, 'property_id'=>$input['property_id']])->delete();
                        $data = new PropertyReserveRequest();

                        if (isset($input['discount_code']) && $input['discount_code']) {
                            $checkCode = Discount::where('code', $input['discount_code'])->first();
                            $data->influencer_id = $checkCode->influencer;
                            $influencer_commission_percent = User::where('id',$checkCode->influencer)->pluck('commission')->first();
                            if(isset($influencer_commission_percent) && !empty($influencer_commission_percent)){
                                $data->influencer_amount = $host_amount * $influencer_commission_percent / 100;
                            }
                        }
                        $optional_service_amount = 0;

                        if (isset($input['selected_options']) && $input['selected_options']) {
                            $optional_services = json_decode($input['selected_options']);

                            if ($optional_services) {
                                foreach ($optional_services as $key => $value) {
                                    $optional_service_amount = $optional_service_amount + $value->value;
                                }
                            }
                        }

                        $payment_email = $input['email'] ?? $userData->email;

                        $authorization_url = '';
                        $reference = '';
                        $access_code = '';

                        $data->guest_id = $userId;

                        $propertyData = DB::table('properties')->where(['id'=>$input['property_id']])->pluck('price')->first();
                        // dd($cardData->getProperty->price);
                        $adminCommission = AdminSettings::pluck('commission')->first();
                        $admin_amount = 0;
                        if(isset($adminCommission) && !empty($adminCommission)){
                            $admin_amount1 = $propertyData * $adminCommission / 100;
                            $admin_amount = $admin_amount1 * $input['total_days'];
                        }
                        $data->host_id = $propertyDetails->host;
                        $data->property_id = $input['property_id'];
                        $data->from_date = $input['start_date'];
                        $data->to_date = $input['end_date'];
                        $data->total_amount = $input['total_booking_amount'];
                        $data->admin_amount = $admin_amount;
                        $data->host_amount = $propertyData * $input['total_days'];
                        $data->security_deposite = $propertyDetails->security_deposit_amount;
                        if(isset($input['payment_method']) && $input['payment_method'] == 'card' ){
                            $data->booking_status = 'Confirmed-by-Host';
                        }else{
                            $data->booking_status = 'Not-confirmed-by-Host';
                        }
                        // $data->booking_status = 'Not-confirmed-by-Host';
                        $data->booking_type = 'Paid';
                        $data->no_of_adult_guest = $input['adultCount'] ?? 0;
                        $data->no_of_children_guest = $input['childCount'] ?? 0;
                        $data->no_of_babies_guest = $input['infantCount'] ?? 0;
                        $data->no_of_pet = $input['petCount'] ?? 0;
                        $data->total_days = $input['total_days'];
                        $data->per_night_price = $propertyDetails->price;
                        $data->total_booking_amount = $input['total_booking_amount'];
                        $data->coupon_code = $input['discount_code'] ?? null;
                        $data->discount_id = $input['discount_id'] ?? null;
                        $data->discount_amount = $input['discount_amount'] ?? null;
                        $data->is_agency = $input['is_agency'] ?? null;
                        // $data->payment_method = $input['payment_method'] ?? null;
                        $data->book_for = $input['book_for'] ?? null;
                        // $data->send_special_offer = $input['send_special_offer'] ?? null;
                        // $data->redeem_royalty_points = $input['redeem_royalty_points'] ?? null;
                        $data->policy_read = $input['policy_read'] ?? null;
                        $data->personal_first_name = $input['first_name'] ?? null;
                        $data->personal_last_name = $input['last_name'] ?? null;
                        $data->personal_address = $input['address'] ?? null;
                        $data->personal_country_id = $input['country_id'] ?? null;
                        $data->personal_city = $input['city'] ?? null;
                        $data->personal_postal_code = $input['postal_code'] ?? null;
                        $data->personal_phone_number = $input['phone_number'] ?? null;
                        $data->personal_email = $input['email'] ?? null;
                        $data->personal_comment = $input['comment'] ?? null;
                        $data->guest_first_name = $input['guest_first_name'] ?? null;
                        $data->guest_last_name = $input['guest_last_name'] ?? null;
                        $data->guest_address = $input['guest_address'] ?? null;
                        $data->guest_city = $input['guest_city'] ?? null;
                        $data->guest_zipcode = $input['guest_zipcode'] ?? null;
                        $data->guest_country_id = $input['guest_country_id'] ?? null;
                        $data->guest_phone_number = $input['guest_phone_number'] ?? null;
                        $data->guest_email = $input['guest_email'] ?? null;
                        // $data->card_holder_name = $input['card_holder_name'] ?? null;
                        // $data->card_number = $input['card_number'] ?? null;
                        // $data->month = $input['month'] ?? null;
                        // $data->year = $input['year'] ?? null;
                        // $data->cvv = $input['cvv'] ?? null;
                        $data->selected_options = $input['selected_options'] ?? null;
                        $data->optional_service_amount = $optional_service_amount;
                        $data->authorization_url = $authorization_url;
                        $data->reference = $reference;
                        $data->access_code = $access_code;
                        $data->booking_from = 'Mobile';

                        if($input['property_id']){
                            $propertyDetail = DB::table('properties')->where('id',$input['property_id'])->first();
                            if(isset($propertyDetail) && !empty($propertyDetail)){
                                $price = $propertyDetail->price;
                                $tax = $propertyDetail->tax;
                                if(isset($price) && !empty($price)){

                                    $commission = AdminSettings::pluck('commission')->first();
                                    if(isset($commission) && !empty($commission)){
                                        $price1 = $price * $commission / 100;
                                        $price = $price1 + $price;
                                        if(isset($tax) && !empty($tax)){
                                            $tax_price = $price * $tax / 100;
                                            // $price = $tax_price + $price;
                                        }else{
                                            $tax_price = null;
                                        }
                                    }else{
                                        if(isset($tax) && !empty($tax)){
                                            $tax_price = $price * $tax / 100;
                                            // $tax_price = $tax_price + $price;
                                        }else{
                                            $tax_price = null;
                                        }
                                    }
                                }
                            }
                        }
                        if(isset($tax_price) && $tax_price != null){
                            $data->tax_amount = $tax_price * $input['total_days'];
                        }

                        if ($data->save()) {
                            if(isset($input['property_id'])){
                                $booking_token = Property::where('id',$input['property_id'])->pluck('code')->first();
                            }else{
                                $booking_token = null;
                            }
                            $booking_id = 'SHTRNTL'.$data->id.'-'.$booking_token;
                            // dd(Crypt::encryptString($booking_id));
                            // dd(encrypt($booking_id));
                            $booking_token = Crypt::encryptString($booking_id);
                            // dd($booking_token);
                            PropertyReserveRequest::where('id', $data->id)->update(['booking_id' => $booking_id, 'booking_token' => $booking_token]);

                            //Remove Cart Data
                            $booking_data = PropertyReserveRequest::where('id', $data->id)->with('getProperty')->first();
                            
                            $response['status'] = true;
                            $response['message'] = 'Booking reserve added successfully.';
                            $response['data'] = $data;
                        } else {
                            $response['status'] = false;
                            $response['message'] = 'Something went wrong, Please try again.';
                        }
                    }
                }
            }else{
                $response['status'] = false;
                $response['message'] = 'Accommodation not found.';
            }
            return response()->json($response, 200);
        }
    }

    public function getMobileBookingReserve(Request $request){
        // dd('inn');
        $this->code = 200;
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;
        // dd($userId);
        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);
        } else {

            $propertyList = PropertyReserveRequest::where(['guest_id'=>$userId, 'property_id'=>$input['property_id'] ])->first();
            // $propertyList = Property::select('properties.*','users.name as host_name')->where(['properties.status'=>1])->with('getHostDetails','getPropertyAddress.getPropertyCity','getPropertyAddress.getPropertyArea','getPropertyCategory.getCategoryData','getAmenities.getAmenityData','getExtraService.getServiceData','getPropertyBedroom','getProPropertyBathroom','getPropertyBedding','getPropertyKitchen','getPropertyImages','getPropertyRating.getUser')->leftjoin('users','users.id','=','properties.host')->where('properties.id',$serachData['propertyid'])->first();
            if(isset($propertyList) && !empty($propertyList)){
                return $propertyList;
            }else{
                $response['status'] = false;
                $response['message'] = 'Accommodation not found.';
                return response()->json($response, 200);
            }
        }
    }

    public function addreserve(Request $request) {
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'total_days' => 'required',
            'per_night_price' => 'required',
            'total_booking_amount' => 'required',
            'adultCount' => 'required',
            'childCount' => 'required',
            'infantCount' => 'required',
            'petCount' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors()->first();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {
            //Delete Old Reserve
            // PropertyReserveRequest::where('user_id', $userId)->delete();

            $data = new PropertyReserveRequest();
            $data->user_id = $userId;
            $data->property_id = $input['property_id'];
            $data->start_date = $input['start_date'];
            $data->end_date = $input['end_date'];
            $data->total_days = $input['total_days'];
            $data->per_night_price = $input['per_night_price'];
            $data->total_booking_amount = $input['total_booking_amount'];
            $data->adultCount = $input['adultCount'] ?? 0;
            $data->childCount = $input['childCount'] ?? 0;
            $data->infantCount = $input['infantCount'] ?? 0;
            $data->petCount = $input['petCount'] ?? 0;
            $data->coupon_code = $input['coupon_code'] ?? null;
            $data->discount_id = $input['discount_id'] ?? null;
            $data->discount_amount = $input['discount_amount'] ?? null;

            if ($data->save()) {
                $response['status'] = true;
                $response['message'] = 'Reservation request sent successfully.';

            } else {
                $response['status'] = false;
                $response['message'] = 'Something went wrong, Please try again.';
            }
            return response()->json($response, 200);
        }
    }

    public function getResvBooking(Request $request)
    {
        $result = PropertyReserveRequest::where('id',$request->reserve_id)->with('getProperty.getPropertyAddress','getProperty.getPropertyAddress','getProperty.getPropertyAddress.getPropertyCity','getProperty.getPropertyAddress.getPropertyArea','getProperty.getPropertyAddress.getPropertyProvince','getProperty.getPropertyAddress.getPropertyCountry','getProperty.getExtraService.getServiceData')->first();

        if ($result) {

            $response['status'] = true;
            $response['data'] = $result;
            $response['message'] = 'Reservation get successfully.';

        } else {
            $response['status'] = false;
            $response['message'] = 'Something went wrong, Please try again.';
        }
        return response()->json($response, 200);
    }

    public function getCart(Request $request) {
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;        
        $cardData = Cart::where('user_id', $userId)->with('getProperty.getPropertyAddress','getProperty.getPropertyAddress','getProperty.getPropertyAddress.getPropertyCity','getProperty.getPropertyAddress.getPropertyArea','getProperty.getPropertyAddress.getPropertyProvince','getProperty.getPropertyAddress.getPropertyCountry','getProperty.getExtraService.getServiceData','getProperty.getUser')->first();
        $final_loyalty_points = '';

        $admin_data = AdminSettings::first();
        $loyalty_percentage = isset($admin_data) && !empty($admin_data->loyalty_percentage) ? $admin_data->loyalty_percentage : '';
        if(isset($loyalty_percentage) && !empty($loyalty_percentage)){
            // dd($userId);
            $total_debit = UserLoyaltyPoint::where(['user_id'=>$userId, 'type'=>'Debit'])->sum('points');
            // dd($total_debit);
            $total_credit = UserLoyaltyPoint::where(['user_id'=>$userId, 'type'=>'Credit'])->sum('points');
            if(isset($total_debit) && !empty($total_debit)){
                $loyalty_points = $total_credit - $total_debit;
                // dd($loyalty_points, $admin_data->royalty_point_equal_to);
                if($loyalty_points > $admin_data->royalty_point_equal_to){
                    $points_amount1 = floor($loyalty_points / $admin_data->royalty_point_equal_to);
                    $points_amount = $points_amount1 * $admin_data->second_royalty_amount;
                    $final_loyalty_points = $points_amount1 * $admin_data->royalty_point_equal_to;
                }else{
                    $final_loyalty_points = '';
                    $points_amount = '';
                }
            }else{
                $loyalty_points = $total_credit;
                if($loyalty_points > $admin_data->royalty_point_equal_to){
                    $points_amount1 = floor($loyalty_points / $admin_data->royalty_point_equal_to);
                    $points_amount = $points_amount1 * $admin_data->second_royalty_amount;
                    $final_loyalty_points = $points_amount1 * $admin_data->royalty_point_equal_to;
                }else{
                    $final_loyalty_points = '';
                    $points_amount = '';
                }
            }
        }else{
            $final_loyalty_points = '';
            $points_amount = '';
        }
        // dd($final_loyalty_points, $points_amount);
        if ($cardData) {
            $response['status'] = true;
            $response['message'] = 'Cart found successfully.';
            $response['data'] = $cardData;
            $response['loyalty_points'] = $final_loyalty_points;
            $response['points_amount'] = $points_amount;

        } else {
            $response['status'] = false;
            $response['message'] = 'Cart not found.';
        }
        return response()->json($response, 200);   
    }

    public function checkCouponCode(Request $request) {
        $input = $request->all();
        
        $userData = auth()->user();
        $userId =  $userData->id;
        $check_in_date = $input['check_in_date'];
        $check_out_date = $input['check_out_date'];
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors()->first();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {

            $checkCode = Discount::where('code', $input['coupon_code'])->first();

            if ($checkCode) {
                $start_date = date('Y-m-d',strtotime($checkCode->start_date));
                $end_date =  date('Y-m-d',strtotime($checkCode->end_date));
               //  $end_date = new DateTime(date('Y-m-d',strtotime($checkCode->end_date)));
               
               // $current_date = new DateTime(date('Y-m-d H:i:s'));
               
                if((strtotime($start_date) <=strtotime($check_in_date)) && (strtotime($end_date) >=strtotime($check_out_date))){

               // if($start_date <= $current_date && $current_date <= $end_date) {

                if($checkCode->all_categories == 'Yes'){
                    $response['status'] = true;
                    $response['message'] = 'Coupon code is valid.';
                    $response['data'] = $checkCode;
                }else{
                    $category_id = $checkCode->category_id;
                    if(isset($input['property_id']) && !empty($input['property_id']) ){
                        $property_categories = PropertyCategory::where('property_id',$input['property_id'] )->pluck('category_id')->toArray();
                        if(count($property_categories) > 0){
                            if (in_array($category_id, $property_categories)) {
                                $response['status'] = true;
                                $response['message'] = 'Coupon code is valid.';
                                $response['data'] = $checkCode;
                            } else {
                                $response['status'] = false;
                                $response['message'] = 'Invalid coupon code2.';
                            }
                        }else{
                            $response['status'] = false;
                            $response['message'] = 'Invalid coupon code1.';
                        }
                    }else{
                        $response['status'] = true;
                        $response['message'] = 'Coupon code is valid.';
                        $response['data'] = $checkCode;
                    }
                }
            }
            else
            {
                $response['status'] = false;
                $response['message'] = 'Invalid coupon code.';
            }

            } else {
                $response['status'] = false;
                $response['message'] = 'Invalid coupon code.';
            }
            return response()->json($response, 200);
        }
    }
   public function checkout(Request $request) {
        try {
            $input = $request->all();
            Log::info('Api Checkout Request',$input);
        
            $userData = auth()->user();
            $userId =  $userData->id ?? null;
        
            $validator = Validator::make($request->all(), [
                'cart_id' => 'required',
                'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            // 'postal_code' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            // 'comment' => 'required',
            'book_for' => 'required',
            'is_agency' => 'required',
            'payment_method' => 'required',

            'policy_read' => 'required',
            'per_night_price' => 'required',
            'total_booking_amount' => 'required',
            ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors()->first();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {



            if(isset($input['booking_type1']) && $input['booking_type1'] == 'Reserve'){
                $cardData = PropertyReserveRequest::where(['guest_id'=>$userId ])->with('getProperty')->first();
            }else{
                $cardData = Cart::where(['user_id'=>$userId, 'id'=>$input['cart_id']])->with('getProperty')->first();
            }
            $user = User::where('email',$input['email'])->first();
            if(isset($user))
            {
                if($user->id != $userId)
                {
                    $userData = $user;
                    $userId = $user->id;
                }
            }
            else if(isset($input['booking_type1']) && $input['booking_type1'] == 'Reserve'){
                $userId = $cardData->guest_id ?? null;
            }
            else{
                $userId = $cardData->user_id ?? null;
            }

            if ($cardData) {
                $data = new Booking();

                $propertyData = DB::table('properties')->where(['id'=>$cardData->property_id])->pluck('price')->first();
                $host_amount = $propertyData * $cardData->total_days;
                if (isset($input['discount_code']) && !empty($input['discount_code'])) {
                    $checkCode = Discount::where('code', $input['discount_code'])->first();
                    if($checkCode)
                    {
                        $data->influencer_id = $checkCode->influencer;
                        $influencer_commission_percent = User::where('id',$checkCode->influencer)->pluck('commission')->first();
                        if(isset($influencer_commission_percent) && !empty($influencer_commission_percent)){
                            $data->influencer_amount = $host_amount * $influencer_commission_percent / 100;
                        }
                    }

                }

                $optional_service_amount = 0;
               
                if (isset($input['selected_options']) &&  !empty($input['selected_options']) && $input['selected_options']!=null) {

                    $optional_services =json_decode($input['selected_options']);

                    if (!empty($optional_services)) {
                        foreach ($optional_services as $key => $value) {
                            $v_amount = $value->value ?? 0;
                            $optional_service_amount = $optional_service_amount + $v_amount;
                        }
                    }
                }

                $payment_email = $input['email'] ?? $userData->email;
                $payment_amount = $cardData->total_booking_amount + $optional_service_amount;

                if (isset($input['redeem_royalty_points']) && $input('redeem_royalty_points')!=null  && $input['redeem_royalty_points'] == 'Yes') {
                    $loyalty_points = $input['loyalty_points'] ?? null;
                    $loyalty_amount = $input['loyalty_amount'] ?? 0;
                    $payment_amount = $payment_amount - $loyalty_amount;
                }
               
                if(isset($input['discount_amount']) && !empty($input['discount_amount']) &&($input['discount_amount']!=null)  ){
                
                    $payment_amount = $payment_amount - $input['discount_amount'];
                }
               
                if(isset($input['payment_method']) && $input['payment_method'] == 'card' ){
                    //PaymentGate
                    $url = "https://api.paystack.co/transaction/initialize";
                    $fields = [
                    'email' => $payment_email,
                    'amount' => $payment_amount * 100,
                    ];
                
                    $fields_string = http_build_query($fields);
                
                    //open connection
                    $ch = curl_init();
                    
                    $live_key = 'sk_live_25064c14dee1f900fdc59be4fb6380ce54436ef1';
                    $test_key = 'sk_test_6bac424039ba02cccf32ada2a81812f1c63fe9a7';
                    
                    //set the url, number of POST vars, POST data
                    curl_setopt($ch,CURLOPT_URL, $url);
                    curl_setopt($ch,CURLOPT_POST, true);
                    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Authorization: Bearer ".$live_key,
                    "Cache-Control: no-cache",
                    ));
                    
                    //So that curl_exec returns the contents of the cURL; rather than echoing it
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
                    
                    //execute post
                    $result = curl_exec($ch);
                    // echo $result;
                    $result1_decode = json_decode($result);

                    if ($result1_decode->status == true) {
                        $authorization_url = $result1_decode->data->authorization_url;
                        $reference = $result1_decode->data->reference;
                        $access_code = $result1_decode->data->access_code;
                    } else {
                        $authorization_url = '';
                        $reference = '';
                        $access_code = '';
                    }
                } else {
                    $authorization_url = route('web.thank-you');
                    $reference = '';
                    $access_code = '';
                }

                $data->guest_id = $userId;
                if(isset($input['redeem_royalty_points']) && $input('redeem_royalty_points')!=null &&  $input['redeem_royalty_points'] == 'Yes'){
                    $data->loyalty_points = $input['loyalty_points'] ?? null;
                    $data->loyalty_amount = $input['loyalty_amount'] ?? null;
                }
                // dd($cardData->getProperty->price);
                $adminCommission = AdminSettings::pluck('commission')->first();
                $admin_amount = 0;
                if(isset($adminCommission) && !empty($adminCommission)){
                    $admin_amount1 = $propertyData * $adminCommission / 100;
                    $admin_amount = $admin_amount1 * $cardData->total_days;
                }
                $admin_data = AdminSettings::first();
                $loyalty_percentage = isset($admin_data) && !empty($admin_data->loyalty_percentage) ? $admin_data->loyalty_percentage : '';

                if (isset($loyalty_percentage) && !empty($loyalty_percentage)) {
                    $loyalty_point = $payment_amount * $loyalty_percentage / 100;
                    $loyalty_point_round = intval(round( $loyalty_point ));
                    
                }else{
                    $loyalty_point_round = Null;
                }
                $data->new_loyalty_amount = $loyalty_point_round ?? null;
                $data->host_id = $cardData->getProperty->host;
                $data->property_id = $cardData->property_id;
                $data->from_date = $cardData->start_date;
                $data->to_date = $cardData->end_date;
                $data->total_amount = $cardData->total_booking_amount;
                $data->admin_amount = $admin_amount;
                $data->host_amount = $propertyData * $cardData->total_days;
                $data->security_deposite = $cardData->getProperty->security_deposit_amount;
                // if(isset($input['payment_method']) && $input['payment_method'] == 'card' ){
             
                // }else{
                   
                // }

                $data->booking_status = 'Not-confirmed-by-Host';
                $data->booking_type = 'Pre-booking';
              
                $data->no_of_adult_guest = $cardData->adultCount ?? 0;
                $data->no_of_children_guest = $cardData->childCount ?? 0;
                $data->no_of_babies_guest = $cardData->infantCount ?? 0;
                $data->no_of_pet = $cardData->petCount ?? 0;
                $data->total_days = $cardData->total_days;
                $data->per_night_price = $cardData->per_night_price;
                $data->total_booking_amount = $cardData->total_booking_amount;
                $data->coupon_code = $input['discount_code'] ?? null;
                $data->discount_id = isset($input['discount_id']) ? $input['discount_id'] : 0;
                $data->discount_amount = $input['discount_amount'] ?? null;
                $data->is_agency = $input['is_agency'] ?? null;
                $data->payment_method = $input['payment_method'] ?? null;
                $data->book_for = $input['book_for'] ?? null;
                $data->send_special_offer = $input['send_special_offer'] ?? null;
                $data->redeem_royalty_points = $input['redeem_royalty_points'] ?? null;
                $data->policy_read = $input['policy_read'] ?? null;
                $data->personal_first_name = $input['first_name'] ?? null;
                $data->personal_last_name = $input['last_name'] ?? null;
                $data->personal_address = $input['address'] ?? null;


                $data->personal_country_code = $input['country_code'] ?? null;
                $data->guest_country_code = $input['guest_country_code'] ?? null;
                $data->personal_province = $input['province'] ?? null;                
                $data->guest_province = $input['guest_province'] ?? null;


                $data->latitude = $input['latitude'] ?? null;
                $data->longitude = $input['longitude'] ?? null;
                $data->guest_latitude = $input['guest_latitude  '] ?? null;
                $data->guest_longitude = $input['guest_longitude'] ?? null;
                
                $data->personal_country_id = $input['country_id'] ?? null;
                $data->personal_city = $input['city'] ?? null;
             
                $data->personal_postal_code = $input['postal_code'] ?? null;
                $data->personal_phone_number = $input['phone_number'] ?? null;
                $data->personal_email = $input['email'] ?? null;
                $data->personal_comment = $input['comment'] ?? null;
                $data->guest_first_name = $input['guest_first_name'] ?? null;
                $data->guest_last_name = $input['guest_last_name'] ?? null;
                $data->guest_address = $input['guest_address'] ?? null;
             
               
                $data->guest_city = $input['guest_city'] ?? null;
                $data->guest_zipcode = $input['guest_zipcode'] ?? null;
                $data->guest_country_id = $input['guest_country_id'] ?? null;
                $data->guest_phone_number = $input['guest_phone_number'] ?? null;
                $data->guest_email = $input['guest_email'] ?? null;
                $data->card_holder_name = $input['card_holder_name'] ?? null;
                $data->card_number = $input['card_number'] ?? null;
                $data->month = $input['month'] ?? null;
                $data->year = $input['year'] ?? null;
                $data->cvv = $input['cvv'] ?? null;
                //$data->selected_options = $input['selected_options'] ?? null;
               // $data->optional_service_amount = $optional_service_amount;
                $data->authorization_url = $authorization_url;
                $data->reference = $reference;
                $data->access_code = $access_code;
                $data->booking_from = $input['booking_from'] ?? 'Website';

                if($cardData->property_id){
                    $propertyDetail = DB::table('properties')->where('id',$cardData->property_id)->first();
                    if(isset($propertyDetail) && !empty($propertyDetail)){
                        $price = $propertyDetail->price;
                        $tax = $propertyDetail->tax;
                        if(isset($price) && !empty($price)){

                            $commission = AdminSettings::pluck('commission')->first();
                            if(isset($commission) && !empty($commission)){
                                $price1 = $price * $commission / 100;
                                $price = $price1 + $price;
                                if(isset($tax) && !empty($tax)){
                                    $tax_price = $price * $tax / 100;
                                    // $price = $tax_price + $price;
                                }else{
                                    $tax_price = null;
                                }
                            }else{
                                if(isset($tax) && !empty($tax)){
                                    $tax_price = $price * $tax / 100;
                                    // $tax_price = $tax_price + $price;
                                }else{
                                    $tax_price = null;
                                }
                            }
                        }
                    }
                }
                if(isset($tax_price) && $tax_price != null){
                    $data->tax_amount = $tax_price * $cardData->total_days;
                }

                if ($data->save()) {
                    $chat = new Chat;
                    $chat->sender_id = $cardData->getProperty->host;
                    $chat->receiver_id = $userId;
                    $chat->booking_id = $data->id;
                    $chat->save();

                    if(isset($cardData->property_id)){
                        $booking_token = Property::where('id',$cardData->property_id)->pluck('code')->first();
                    }else{
                        $booking_token = null;
                    }
                    $booking_id = 'SHTRNTL'.$data->id.'-'.$booking_token;
                    // dd(Crypt::encryptString($booking_id));
                    // dd(encrypt($booking_id));
                    $booking_token = Crypt::encryptString($booking_id);
                    Booking::where('id', $data->id)->update(['booking_id' => $booking_id, 'booking_token' => $booking_token]);

                    //Remove Cart Data
                    $cardData->delete();

                    $booking_data = Booking::where('id', $data->id)->with('getProperty')->first();
                    $booking_data->chat_id = $chat->id;
                    $booking_data->save();

                    $admin_data = AdminSettings::first();
                    // $loyalty_percentage = isset($admin_data) && !empty($admin_data->loyalty_percentage) ? $admin_data->loyalty_percentage : '';

                    if (isset($loyalty_percentage) && !empty($loyalty_percentage)) {
                        $loyalty_point = $payment_amount * $loyalty_percentage / 100;
                        $loyalty_point_round = intval(round( $loyalty_point ));
                        
                        // dd($userId, $booking_data->id, $loyalty_point_round);
                        $loyalty = new UserLoyaltyPoint;
                        $loyalty->user_id = $userId;
                        $loyalty->order_id = $booking_data->id;
                        $loyalty->points = $loyalty_point_round;
                        $loyalty->type = 'Credit';
                        $loyalty->title = 'You have got '.$loyalty_point_round.' Loyalty points.';
                        $loyalty->save();
                    }else{
                        $loyalty_point_round = '';
                    }
                    if(isset($input['redeem_royalty_points']) && $input['redeem_royalty_points'] == 'Yes'){
                        if(isset($input['loyalty_points']) && !empty($input['loyalty_amount']) ){
                            $loyalty = new UserLoyaltyPoint;
                            $loyalty->user_id = $userId;
                            $loyalty->order_id = $booking_data->id;
                            $loyalty->points = $input['loyalty_points'];
                            $loyalty->type = 'Debit';
                            $loyalty->title = 'You have redeem '.$input['loyalty_points'].' in NGN '.$input['loyalty_amount'].'.';
                            $loyalty->save();
                        }
                    }/*else{
                        $loyalty = new UserLoyaltyPoint;
                        $loyalty->user_id = $userId;
                        $loyalty->order_id = $booking_data->id;
                        $loyalty->points = $loyalty_point_round;
                        $loyalty->type = 'Credit';
                        $loyalty->title = 'You have got '.$loyalty_point_round.' Loyalty points.';
                        $loyalty->save();
                    }*/

                    // send email start
                    if(isset($input['payment_method']) && $input['payment_method'] == 'bank_account' ){
                        $email = EmailTemplateLang::where('email_id', 28)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                        $subject = $email->subject;
                        $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                        $record = (object)[];

                        $description = str_replace("[NAME]", ucwords($input['first_name']).' '.$input['last_name'],  $email->description);
                        $record->description = $description;
                        $record->name = $email->name;
                        $record->footer = $email->footer;
                        $record->username = $input['first_name'];
                        $record->property_name = $booking_data->getProperty->title;
                        $record->subject = $subject;
                        $record->user_email = $input['email'];
                        $record->cardData = $booking_data;
                        $record->removeAddress = 'No';
                        
                        $record->check_in_url = url('guest-area/checkin/search');

                        Mail::send('emails.booking_confirm_peyment_pending', compact('record'), function ($message) use ($input, $subject) {
                            $message->to($input['email'], config('app.name'))->subject($subject);
                            $message->from('customersupport@shortletrenrals.com', config('app.name'));
                        });
                    }
                    // send email end
          
                    if (isset($userId)) {
                        $userdata = User::where('id',$userId)->first();
                        if(isset($userdata) && !isset($userdata->name)) 
                        {
                            if($userdata->is_guest==1)
                            {
                                $userdata->name = $input['first_name'];
                                $userdata->surname = $input['last_name'];
                                
                                $userdata->email = $input['email'];          
                                $userdata->country_code = $input['country_code'];                    
                                $userdata->mobile = $input['phone_number'];                            
                                $userdata->address = $input['address'];
                                $userdata->postal_code = $input['postal_code'];  
                                $userdata->latitude = $input['latitude'] ?? null;
                                $userdata->longitude = $input['longitude'] ?? null;
                                $userdata->ip_address = null;  
                                 
                                $userdata->save();
                            }
                            else
                            {
                                $userdata->name = $input['first_name'];
                                $userdata->surname = $input['last_name'];
                                $userdata->address = $input['address'];
                                $userdata->postal_code = $input['postal_code'];   
                                $userdata->save();    
                            }
                        }
                        $notificationData = new Notification;
                        $notificationData->user_type = $userdata->user_type;
                        $notificationData->notification_type = 1;
                        $notificationData->notification_for = 'Booking';
                        $notificationData->title = 'Booking Received.';
                      
                        $notificationData->message = 'Your booking has been received - '.$booking_id;
                       
                        $notificationData->user_id = $userId;
                        $notificationData->property_id = $booking_data->property_id; 
                        $notificationData->order_id = $data->id;
                        $notificationData->save();
                        send_notification(1, $userId, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                        $notificationData = new Notification;
                        $notificationData->user_type = 1;
                        $notificationData->notification_type = 1;
                        $notificationData->notification_for = 'Booking';
                        $notificationData->title = 'Booking Received.';
                        $notificationData->message = 'New booking has been received - '.$booking_id;
                        $notificationData->user_id = 1;
                        $notificationData->property_id = $booking_data->property_id; 
                        $notificationData->order_id = $data->id;
                        $notificationData->save();
                        send_notification(1, 1, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                        if(isset($booking_data->influencer_id) && !empty($booking_data->influencer_id)){
                            $userdata_influencer = User::where('id',$booking_data->influencer_id)->first();
                            $notificationData = new Notification;
                            $notificationData->user_type = $userdata_influencer->user_type;
                            $notificationData->notification_type = 1;
                            $notificationData->notification_for = 'Booking';
                            $notificationData->title = 'Booking Received.';
                            $notificationData->message = 'New booking has been received - '.$booking_id;
                            $notificationData->user_id = $userdata_influencer->id;
                            $notificationData->property_id = $booking_data->property_id;
                            $notificationData->order_id = $data->id;
                            $notificationData->save();
                            send_notification(1, $userdata_influencer->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                        }

                        // $propertyHost = Property::where('id',$booking_data->property_id)->pluck('host_id')->first();
                        if(isset($booking_data->host_id) && !empty($booking_data->host_id)){
                            $Hostdata = User::where('id',$booking_data->host_id)->first();
                            if($Hostdata){
                                $notificationHost = new Notification;
                                $notificationHost->user_type = $Hostdata->user_type;
                                $notificationHost->notification_type = 1;
                                $notificationHost->notification_for = 'Booking';
                                $notificationHost->title = 'Booking Received.';
                                $notificationHost->message = 'You have got a new Booking - '.$booking_id;
                                $notificationHost->user_id = $Hostdata->id;
                                $notificationHost->property_id = $booking_data->property_id;
                                $notificationHost->order_id = $data->id;
                                $notificationHost->save();
                                send_notification(1, $Hostdata->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                            }
                        }
                    }
                    $response['status'] = true;
                    $response['message'] = 'Booking added successfully.';
                    $response['data'] = $booking_data;

                } else {
                    $response['status'] = false;
                    $response['message'] = 'Something went wrong, Please try again.';
                }
            } else {
                $response['status'] = false;
                $response['message'] = 'Invalid cart detail.';
            }
            return response()->json($response, 200);
        }
        } catch (\Exception $ex) {
          //  \Log::info('Api Checkout try catch',$ex);
            dd($ex);
            $response['status'] = false;
            $response['message'] = 'Somting went wrong.';

        }
    }

    public function checkout_from_reserve(Request $request) {
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'book_for' => 'required',
            'is_agency' => 'required',
            'payment_method' => 'required',

            'policy_read' => 'required',
            'per_night_price' => 'required',
            'total_booking_amount' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors()->first();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {


            $user = User::where('email',$input['email'])->first();
            if(isset($user))
            {
                if($user->id != $userId)
                {
                    $userData = $user;
                    $userId = $user->id;
                }
            }
            $cardData = PropertyReserveRequest::where(['guest_id'=>$userId ])->with('getProperty')->first();

            $userdata = User::where('id',$userId)->first();
            $userdata->address = $input['address'];
            $userdata->postal_code = $input['postal_code'];   
            $userdata->save();

            if ($cardData) {
                $data = new Booking();
                
                $propertyData = DB::table('properties')->where(['id'=>$cardData->property_id])->pluck('price')->first();
                $host_amount = $propertyData * $cardData->total_days;
                if (isset($cardData['coupon_code']) && $cardData['coupon_code']) {
                    $checkCode = Discount::where('code', $cardData['coupon_code'])->first();
                    $data->influencer_id = $checkCode->influencer;
                    $influencer_commission_percent = User::where('id',$checkCode->influencer)->pluck('commission')->first();
                    if(isset($influencer_commission_percent) && !empty($influencer_commission_percent)){
                        $data->influencer_amount = $host_amount * $influencer_commission_percent / 100;
                    }
                }

                $optional_service_amount = 0;
                // dd($cardData['selected_options'], $input);
                if (isset($cardData['selected_options']) && $cardData['selected_options']) {
                    $optional_services = json_decode($cardData['selected_options']);

                    if ($optional_services) {

                        foreach ($optional_services as $key => $value) {
                            $optional_service_amount = $optional_service_amount + $value->value;
                        }
                    }
                }

                $payment_email = $input['email'] ?? $userData->email;
                $payment_amount = $cardData->total_booking_amount + $optional_service_amount;

                
                if(isset($cardData['discount_amount']) && !empty($cardData['discount_amount']) ){
                    $payment_amount = $payment_amount - $cardData['discount_amount'];
                }else{
                    if (isset($input['redeem_royalty_points']) && $input['redeem_royalty_points'] == 'Yes') {
                        $loyalty_points = $input['loyalty_points'] ?? null;
                        $loyalty_amount = $input['loyalty_amount'] ?? 0;
                        $payment_amount = $payment_amount - $loyalty_amount;
                    }
                }
                // dd($payment_amount, '--payment_amount');
                if(isset($input['payment_method']) && $input['payment_method'] == 'card' ){
                    //PaymentGate
                    $url = "https://api.paystack.co/transaction/initialize";
                    $fields = [
                    'email' => $payment_email,
                    'amount' => $payment_amount * 100,
                    ];
                
                    $fields_string = http_build_query($fields);
                
                    //open connection
                    $ch = curl_init();

                    $live_key = 'sk_live_25064c14dee1f900fdc59be4fb6380ce54436ef1';
                    $test_key = 'sk_test_6bac424039ba02cccf32ada2a81812f1c63fe9a7';
                    
                    //set the url, number of POST vars, POST data
                    curl_setopt($ch,CURLOPT_URL, $url);
                    curl_setopt($ch,CURLOPT_POST, true);
                    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Authorization: Bearer ".$live_key,
                    "Cache-Control: no-cache",
                    ));
                    
                    //So that curl_exec returns the contents of the cURL; rather than echoing it
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
                    
                    //execute post
                    $result = curl_exec($ch);
                    // echo $result;
                    $result1_decode = json_decode($result);

                    if ($result1_decode->status == true) {
                        $authorization_url = $result1_decode->data->authorization_url;
                        $reference = $result1_decode->data->reference;
                        $access_code = $result1_decode->data->access_code;
                    } else {
                        $authorization_url = '';
                        $reference = '';
                        $access_code = '';
                    }
                } else {
                    $authorization_url = route('web.thank-you');
                    $reference = '';
                    $access_code = '';
                }

                $data->guest_id = $userId;
                if(isset($input['redeem_royalty_points']) && $input['redeem_royalty_points'] == 'Yes'){
                    $data->loyalty_points = $input['loyalty_points'] ?? null;
                    $data->loyalty_amount = $input['loyalty_amount'] ?? null;
                }
                // dd($cardData->getProperty->price);
                $adminCommission = AdminSettings::pluck('commission')->first();
                $admin_amount = 0;
                if(isset($adminCommission) && !empty($adminCommission)){
                    $admin_amount1 = $propertyData * $adminCommission / 100;
                    $admin_amount = $admin_amount1 * $cardData->total_days;
                }
                $data->host_id = $cardData->getProperty->host;
                $data->property_id = $cardData->property_id;
                $data->from_date = $cardData->from_date;
                $data->to_date = $cardData->to_date;
                $data->total_amount = $cardData->total_booking_amount;
                $data->admin_amount = $admin_amount;
                $data->host_amount = $propertyData * $cardData->total_days;
                $data->security_deposite = $cardData->security_deposite;

                /*if(isset($input['payment_method']) && $input['payment_method'] == 'card' ){
                    $data->booking_status = 'Confirmed-by-Host';
                    $data->booking_type = 'Paid';
                }else{
                    $data->booking_status = 'Not-confirmed-by-Host';
                    $data->booking_type = 'Pre-booking';
                }*/

                $data->booking_status = 'Not-confirmed-by-Host';
                $data->booking_type = 'Pre-booking';


                // $data->booking_status = 'Not-confirmed-by-Host';
                $data->no_of_adult_guest = $cardData->no_of_adult_guest ?? 0;
                $data->no_of_children_guest = $cardData->no_of_children_guest ?? 0;
                $data->no_of_babies_guest = $cardData->no_of_babies_guest ?? 0;
                $data->no_of_pet = $cardData->no_of_pet ?? 0;
                $data->total_days = $cardData->total_days;
                $data->per_night_price = $cardData->per_night_price;
                $data->total_booking_amount = $cardData->total_booking_amount;
                $data->coupon_code = $cardData['coupon_code'] ?? null;
                $data->discount_id = $cardData['discount_id'] ?? null;
                $data->discount_amount = $cardData['discount_amount'] ?? null;
                $data->is_agency = $input['is_agency'] ?? null;
                $data->payment_method = $input['payment_method'] ?? null;
                $data->book_for = $input['book_for'] ?? null;
                $data->send_special_offer = $cardData['send_special_offer'] ?? null;
                $data->redeem_royalty_points = $cardData['redeem_royalty_points'] ?? null;
                $data->policy_read = $input['policy_read'] ?? null;
                $data->personal_first_name = $input['first_name'] ?? null;
                $data->personal_last_name = $input['last_name'] ?? null;
                $data->personal_address = $input['address'] ?? null;
                $data->personal_country_id = $input['country_id'] ?? null;
                $data->personal_city = $input['city'] ?? null;
                $data->personal_postal_code = $input['postal_code'] ?? null;
                $data->personal_phone_number = $input['phone_number'] ?? null;
                $data->personal_email = $input['email'] ?? null;
                $data->personal_comment = $input['comment'] ?? null;
                $data->latitude = $input['latitude'] ?? null;
                $data->longitude = $input['longitude'] ?? null;
                $data->guest_latitude = $input['guest_latitude  '] ?? null;
                $data->guest_longitude = $input['guest_longitude'] ?? null;

                $data->personal_country_code = $input['country_code'] ?? null;
                $data->guest_country_code = $input['guest_country_code'] ?? null;
                $data->personal_province = $input['province'] ?? null;                
                $data->guest_province = $input['guest_province'] ?? null;

                $data->guest_first_name = $input['guest_first_name'] ?? null;
                $data->guest_last_name = $input['guest_last_name'] ?? null;
                $data->guest_address = $input['guest_address'] ?? null;
                $data->guest_city = $input['guest_city'] ?? null;
                $data->guest_zipcode = $input['guest_zipcode'] ?? null;
                $data->guest_country_id = $input['guest_country_id'] ?? null;
                $data->guest_phone_number = $input['guest_phone_number'] ?? null;
                $data->guest_email = $input['guest_email'] ?? null;
                $data->card_holder_name = $input['card_holder_name'] ?? null;
                $data->card_number = $input['card_number'] ?? null;
                $data->month = $input['month'] ?? null;
                $data->year = $input['year'] ?? null;
                $data->cvv = $input['cvv'] ?? null;
                $data->selected_options = $cardData['selected_options'] ?? null;
                $data->optional_service_amount = $optional_service_amount;
                $data->authorization_url = $authorization_url;
                $data->reference = $reference;
                $data->access_code = $access_code;

                if($cardData->property_id){
                    $propertyDetail = DB::table('properties')->where('id',$cardData->property_id)->first();
                    if(isset($propertyDetail) && !empty($propertyDetail)){
                        $price = $propertyDetail->price;
                        $tax = $propertyDetail->tax;
                        if(isset($price) && !empty($price)){

                            $commission = AdminSettings::pluck('commission')->first();
                            if(isset($commission) && !empty($commission)){
                                $price1 = $price * $commission / 100;
                                $price = $price1 + $price;
                                if(isset($tax) && !empty($tax)){
                                    $tax_price = $price * $tax / 100;
                                    // $price = $tax_price + $price;
                                }else{
                                    $tax_price = null;
                                }
                            }else{
                                if(isset($tax) && !empty($tax)){
                                    $tax_price = $price * $tax / 100;
                                    // $tax_price = $tax_price + $price;
                                }else{
                                    $tax_price = null;
                                }
                            }
                        }
                    }
                }
                if(isset($tax_price) && $tax_price != null){
                    $data->tax_amount = $tax_price * $cardData->total_days;
                }

                if ($data->save()) {
                    $chat = new Chat;
                    $chat->sender_id = $cardData->getProperty->host;
                    $chat->receiver_id = $userId;
                    $chat->booking_id = $data->id;
                    $chat->save();

                    if(isset($cardData->property_id)){
                        $booking_token = Property::where('id',$cardData->property_id)->pluck('code')->first();
                    }else{
                        $booking_token = null;
                    }
                    $booking_id = 'SHTRNTL'.$data->id.'-'.$booking_token;
                    // dd(Crypt::encryptString($booking_id));
                    // dd(encrypt($booking_id));
                    $booking_token = Crypt::encryptString($booking_id);
                    Booking::where('id', $data->id)->update(['booking_id' => $booking_id, 'booking_token' => $booking_token]);

                    //Remove Cart Data
                    $cardData->delete();

                    $booking_data = Booking::where('id', $data->id)->with('getProperty.getPropertyAddress.getPropertyCity')->first();
                    $booking_data->chat_id = $chat->id;
                    $booking_data->save();

                    $admin_data = AdminSettings::first();
                    $loyalty_percentage = isset($admin_data) && !empty($admin_data->loyalty_percentage) ? $admin_data->loyalty_percentage : '';

                    if (isset($loyalty_percentage) && !empty($loyalty_percentage)) {
                        $loyalty_point = $payment_amount * $loyalty_percentage / 100;
                        $loyalty_point_round = intval(round( $loyalty_point ));

                        // dd($userId, $booking_data->id, $loyalty_point_round);
                        $loyalty = new UserLoyaltyPoint;
                        $loyalty->user_id = $userId;
                        $loyalty->order_id = $booking_data->id;
                        $loyalty->points = $loyalty_point_round;
                        $loyalty->type = 'Credit';
                        $loyalty->title = 'You have got '.$loyalty_point_round.' Loyalty points.';
                        $loyalty->save();
                    }else{
                        $loyalty_point_round = '';
                    }
                    if(isset($input['redeem_royalty_points']) && $input['redeem_royalty_points'] == 'Yes'){
                        if(isset($input['loyalty_points']) && !empty($input['loyalty_amount']) ){
                            $loyalty = new UserLoyaltyPoint;
                            $loyalty->user_id = $userId;
                            $loyalty->order_id = $booking_data->id;
                            $loyalty->points = $input['loyalty_points'];
                            $loyalty->type = 'Debit';
                            $loyalty->title = 'You have redeem '.$input['loyalty_points'].' in NGN '.$input['loyalty_amount'].'.';
                            $loyalty->save();
                        }
                    }

                    // send email start
                

                    if(isset($input['payment_method']) && $input['payment_method'] == 'bank_account'){
                        $email = EmailTemplateLang::where('email_id', 28)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                        $subject = $email->subject;
                        $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                        $record = (object)[];
                        $description = $email->description;

                        $description = str_replace("[NAME]", ucwords($input['first_name']).' '.$input['last_name'],  $description);

                        $record->name = $email->name;
                        $record->description = $description;
                        $record->footer = $email->footer;
                        $record->username = $input['first_name'];
                        $record->property_name = $booking_data->getProperty->title;
                        $record->subject = $subject;
                        $record->user_email = $input['email'];
                        $record->cardData = $booking_data;
                        $record->check_in_url = url('guest-area/checkin/search');

                        Mail::send('emails.checkin_form_bank_transfer', compact('record'), function ($message) use ($input, $subject) {
                            $message->to($input['email'], config('app.name'))->subject($subject);
                            $message->from('customersupport@shortletrenrals.com', config('app.name'));
                        });



                    }

                    
                    // send email end

                    if (isset($userId)) {
                        if($userdata->is_guest==1)
                        {
                            $userdata->name = $input['first_name'];
                            $userdata->surname = $input['last_name'];
                            $userdata->email = $input['email'];                            
                            $userdata->mobile = $input['phone_number'];                            
                            $userdata->address = $input['address'];
                            $userdata->postal_code = $input['postal_code'];   
                            $userdata->ip_address = null;  
                            $userdata->save();
                        }
                        else
                        {
                            $userdata->name = $input['first_name'];
                            $userdata->surname = $input['last_name'];
                            $userdata->address = $input['address'];
                            $userdata->postal_code = $input['postal_code'];   
                            $userdata->save();    
                        }
                        $notificationData = new Notification;
                        $notificationData->user_type = $userdata->user_type;
                        $notificationData->notification_type = 1;
                        $notificationData->notification_for = 'Booking';
                        $notificationData->title = 'Booking Received.';
                        if(isset($loyalty_point_round) && !empty($loyalty_point_round)){
                            $notificationData->message = 'Your booking has been received - '.$booking_id.' and You have got '.$loyalty_point_round.' Loyalty points.';
                        }else{
                            $notificationData->message = 'Your booking has been received - '.$booking_id;
                        }
                        $notificationData->user_id = $userId;
                        $notificationData->property_id = $booking_data->property_id;
                        $notificationData->order_id = $data->id;
                        $notificationData->save();
                        send_notification(1, $userId, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                        $notificationData = new Notification;
                        $notificationData->user_type = 1;
                        $notificationData->notification_type = 1;
                        $notificationData->notification_for = 'Booking';
                        $notificationData->title = 'Booking Received.';
                        $notificationData->message = 'New booking has been received - '.$booking_id;
                        $notificationData->user_id = 1;
                        $notificationData->property_id = $booking_data->property_id; 
                        $notificationData->order_id = $data->id;
                        $notificationData->save();
                        send_notification(1, 1, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                        if(isset($booking_data->influencer_id) && !empty($booking_data->influencer_id)){
                            $userdata_influencer = User::where('id',$booking_data->influencer_id)->first();
                            $notificationData = new Notification;
                            $notificationData->user_type = $userdata_influencer->user_type;
                            $notificationData->notification_type = 1;
                            $notificationData->notification_for = 'Booking';
                            $notificationData->title = 'Booking Received.';
                            $notificationData->message = 'Your booking has been received - '.$booking_id;
                            $notificationData->user_id = $userdata_influencer->id;
                            $notificationData->property_id = $booking_data->property_id;
                            $notificationData->order_id = $data->id;
                            $notificationData->save();
                            send_notification(1, $userdata_influencer->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                        }

                        // $propertyHost = Property::where('id',$booking_data->property_id)->pluck('host_id')->first();
                        if(isset($booking_data->host_id) && !empty($booking_data->host_id)){
                            $Hostdata = User::where('id',$booking_data->host_id)->first();

                            $notificationHost = new Notification;
                            $notificationHost->user_type = $Hostdata->user_type;
                            $notificationHost->notification_type = 1;
                            $notificationHost->notification_for = 'Booking';
                            $notificationHost->title = 'Booking Received.';
                            $notificationHost->message = 'You have got a new Booking - '.$booking_id;
                            $notificationHost->user_id = $Hostdata->id;
                            $notificationHost->property_id = $booking_data->property_id;
                            $notificationHost->order_id = $data->id;
                            $notificationHost->save();
                        }
                    }
                    $response['status'] = true;
                    $response['message'] = 'Booking added successfully.';
                    $response['data'] = $booking_data;

                } else {
                    $response['status'] = false;
                    $response['message'] = 'Something went wrong, Please try again.';
                }
            } else {
                $response['status'] = false;
                $response['message'] = 'Invalid cart detail.';
            }
            return response()->json($response, 200);
        }
    }
    /*
    public function checkout_reserve(Request $request) {
 
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'book_for' => 'required',
            'is_agency' => 'required',
            'policy_read' => 'required',
            'per_night_price' => 'required',
            'total_booking_amount' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors()->first();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {
            $cardData = Cart::where(['user_id'=>$userId, 'id'=>$input['cart_id']])->with('getProperty')->first();

            $user = User::where('email',$input['email'])->first();
            if(isset($user))
            {
                if($user->id != $userId)
                {
                    $userData = $user;
                    $userId = $user->id;
                }
            }


            

            $userdata = User::where('id',$userId)->first();
            $userdata->address = $input['address'];
            $userdata->postal_code = $input['postal_code'];   
            $userdata->save();

            if ($cardData) {
                PropertyReserveRequest::where(['guest_id'=>$cardData->user_id, 'property_id'=>$cardData->property_id])->delete();
                $data = new PropertyReserveRequest();

                $propertyData = DB::table('properties')->where(['id'=>$cardData->property_id])->pluck('price')->first();
                $host_amount = $propertyData * $cardData->total_days;

                if (isset($input['discount_code']) && $input['discount_code']) {
                    $checkCode = Discount::where('code', $input['discount_code'])->first();
                    $data->influencer_id = $checkCode->influencer;
                    $influencer_commission_percent = User::where('id',$checkCode->influencer)->pluck('commission')->first();
                    if(isset($influencer_commission_percent) && !empty($influencer_commission_percent)){
                        $data->influencer_amount = $host_amount * $influencer_commission_percent / 100;
                    }
                }

                $optional_service_amount = 0;

                if (isset($input['selected_options']) && $input['selected_options']) {
                    $optional_services = json_decode($input['selected_options']);

                    if ($optional_services) {
                        foreach ($optional_services as $key => $value) {
                            $optional_service_amount = $optional_service_amount + $value->value;
                        }
                    }
                }

                $payment_email = $input['email'] ?? $userData->email;
                $payment_amount = $cardData->total_booking_amount + $optional_service_amount;

                if(isset($input['discount_amount']) && !empty($input['discount_amount']) ){
                    $payment_amount = $payment_amount - $input['discount_amount'];
                }
                $authorization_url = route('web.thank-you');
                $reference = '';
                $access_code = '';

                $data->guest_id = $userId;

                $adminCommission = AdminSettings::pluck('commission')->first();
                $admin_amount = 0;
                if(isset($adminCommission) && !empty($adminCommission)){
                    $admin_amount1 = $propertyData * $adminCommission / 100;
                    $admin_amount = $admin_amount1 * $cardData->total_days;
                }
                $data->host_id = $cardData->getProperty->host;
                $data->property_id = $cardData->property_id;
                $data->from_date = $cardData->start_date;
                $data->to_date = $cardData->end_date;
                $data->total_amount = $cardData->total_booking_amount;
                $data->admin_amount = $admin_amount;
                $data->host_amount = $propertyData * $cardData->total_days;
                $data->security_deposite = $cardData->getProperty->security_deposit_amount;
                if(isset($input['payment_method']) && $input['payment_method'] == 'card' ){
                    $data->booking_status = 'Confirmed-by-Host';
                }else{
                    $data->booking_status = 'Not-confirmed-by-Host';
                }
                // $data->booking_status = 'Not-confirmed-by-Host';
                $data->booking_type = 'Paid';
                $data->no_of_adult_guest = $cardData->adultCount ?? 0;
                $data->no_of_children_guest = $cardData->childCount ?? 0;
                $data->no_of_babies_guest = $cardData->infantCount ?? 0;
                $data->no_of_pet = $cardData->petCount ?? 0;
                $data->total_days = $cardData->total_days;
                $data->per_night_price = $cardData->per_night_price;
                $data->total_booking_amount = $cardData->total_booking_amount;
                $data->coupon_code = $input['discount_code'] ?? null;
                $data->discount_id = $input['discount_id'] ?? null;
                $data->discount_amount = $input['discount_amount'] ?? null;
                $data->is_agency = $input['is_agency'] ?? null;
                $data->payment_method = $input['payment_method'] ?? null;
                $data->book_for = $input['book_for'] ?? null;
                $data->send_special_offer = $input['send_special_offer'] ?? null;
                $data->redeem_royalty_points = $input['redeem_royalty_points'] ?? null;
                $data->policy_read = $input['policy_read'] ?? null;
                $data->personal_first_name = $input['first_name'] ?? null;
                $data->personal_last_name = $input['last_name'] ?? null;
                $data->personal_address = $input['address'] ?? null;
                $data->personal_country_id = $input['country_id'] ?? null;
                $data->personal_city = $input['city'] ?? null;
                $data->personal_postal_code = $input['postal_code'] ?? null;
                $data->personal_phone_number = $input['phone_number'] ?? null;
                $data->personal_email = $input['email'] ?? null;
                $data->personal_comment = $input['comment'] ?? null;
                $data->guest_first_name = $input['guest_first_name'] ?? null;
                $data->guest_last_name = $input['guest_last_name'] ?? null;
                $data->guest_address = $input['guest_address'] ?? null;

                $data->latitude = $input['latitude'] ?? null;
                $data->longitude = $input['longitude'] ?? null;
                $data->guest_latitude = $input['guest_latitude  '] ?? null;
                $data->guest_longitude = $input['guest_longitude'] ?? null;

                $data->personal_country_code = $input['country_code'] ?? null;
                $data->guest_country_code = $input['guest_country_code'] ?? null;
                $data->personal_province = $input['province'] ?? null;                
                $data->guest_province = $input['guest_province'] ?? null;

                $data->guest_city = $input['guest_city'] ?? null;
                $data->guest_zipcode = $input['guest_zipcode'] ?? null;
                $data->guest_country_id = $input['guest_country_id'] ?? null;
                $data->guest_phone_number = $input['guest_phone_number'] ?? null;
                $data->guest_email = $input['guest_email'] ?? null;
                $data->card_holder_name = $input['card_holder_name'] ?? null;
                $data->card_number = $input['card_number'] ?? null;
                $data->month = $input['month'] ?? null;
                $data->year = $input['year'] ?? null;
                $data->cvv = $input['cvv'] ?? null;
                $data->selected_options = $input['selected_options'] ?? null;
                $data->optional_service_amount = $optional_service_amount;
                $data->authorization_url = $authorization_url;
                $data->reference = $reference;
                $data->access_code = $access_code;
                $data->booking_from = $input['booking_from'] ?? 'Website';
                

                if($cardData->property_id){
                    $propertyDetail = DB::table('properties')->where('id',$cardData->property_id)->first();
                    if(isset($propertyDetail) && !empty($propertyDetail)){
                        $price = $propertyDetail->price;
                        $tax = $propertyDetail->tax;
                        if(isset($price) && !empty($price)){

                            $commission = AdminSettings::pluck('commission')->first();
                            if(isset($commission) && !empty($commission)){
                                $price1 = $price * $commission / 100;
                                $price = $price1 + $price;
                                if(isset($tax) && !empty($tax)){
                                    $tax_price = $price * $tax / 100;
                                    // $price = $tax_price + $price;
                                }else{
                                    $tax_price = null;
                                }
                            }else{
                                if(isset($tax) && !empty($tax)){
                                    $tax_price = $price * $tax / 100;
                                    // $tax_price = $tax_price + $price;
                                }else{
                                    $tax_price = null;
                                }
                            }
                        }
                    }
                }
                if(isset($tax_price) && $tax_price != null){
                    $data->tax_amount = $tax_price * $cardData->total_days;
                }

                if ($data->save()) {
                    if(isset($cardData->property_id)){
                        $booking_token = Property::where('id',$cardData->property_id)->pluck('code')->first();
                    }else{
                        $booking_token = null;
                    }
                    $booking_id = 'SHTRNTL'.$data->id.'-'.$booking_token;
                    // dd(Crypt::encryptString($booking_id));
                    // dd(encrypt($booking_id));
                    $booking_token = Crypt::encryptString($booking_id);
                    // dd($booking_token);
                    PropertyReserveRequest::where('id', $data->id)->update(['booking_id' => $booking_id, 'booking_token' => $booking_token]);

                    //Remove Cart Data
                    $cardData->delete();
                    $booking_data = PropertyReserveRequest::where('id', $data->id)->with('getProperty')->first();
                    
                    // send email start  //guest
                    $email = EmailTemplateLang::where('email_id',26)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                    $subject = $email->subject;
                    $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                    $record = (object)[];
                    $description = str_replace("[NAME]", ucfirst($input['first_name']).' '.ucfirst($input['last_name']), $email->description);
                    $record->description = $description;
                    $record->name = $email->name;
                    $record->footer = $email->footer;
                    $record->username = $input['first_name'];
                    $record->property_name = $booking_data->getProperty->title;
                    $record->subject = $subject;
                    $record->user_email = $input['email'];
                    $record->cardData = $booking_data;
                    $record->check_in_url = url('guest-area/checkin/search');
    
                    Mail::send('emails.booking_reves_request', compact('record'), function ($message) use ($input, $subject) {
                        $message->to($input['email'], config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                    });


                    // host//
                    $host  = User::where('id',$cardData->getProperty->host)->first();
                    $email = EmailTemplateLang::where('email_id',30)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                    $subject = $email->subject;
                    $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                    $record = (object)[];

                    $description = str_replace("[NAME]", ucfirst($host->name).' '.ucfirst($host->surname), $email->description);
                    $description = str_replace("[Number]", $booking_data->total_days, $description);
                    $record->description = $description;
                    $record->name = $email->name;
                    $record->footer = $email->footer;
                    $record->username = $host->first_name;
                    $record->property_name = $booking_data->getProperty->title;
                    $record->subject = $subject;
                    $record->user_email = $host->email;
                    $record->cardData = $booking_data;
                    $record->check_in_url = url('guest-area/checkin/search');
    
                    Mail::send('emails.booking_reves_request_host', compact('record'), function ($message) use ($host, $subject) {
                        $message->to($host->email, config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                    });


                    if (isset($userId)) {
                        if($userdata->is_guest==1)
                        {
                            $userdata->name = $input['first_name'];
                            $userdata->surname = $input['last_name'];
                            $userdata->email = $input['email'];      
                            $userdata->country_code = $input['country_code'];                        
                            $userdata->mobile = $input['phone_number'];                            
                            $userdata->address = $input['address'];
                            $userdata->postal_code = $input['postal_code'];   
                            $userdata->ip_address = null;  
                            $userdata->latitude = $input['latitude'] ?? null;
                            $userdata->longitude = $input['longitude'] ?? null;
                            $userdata->save();
                        }
                        else
                        {
                            $userdata->name = $input['first_name'];
                            $userdata->surname = $input['last_name'];
                            $userdata->address = $input['address'];
                            $userdata->postal_code = $input['postal_code'];   
                            $userdata->save();    
                        }
                        $notificationData = new Notification;
                        $notificationData->user_type = $userdata->user_type;
                        $notificationData->notification_type = 1;
                        $notificationData->notification_for = 'Reserve Booking';
                        $notificationData->title = 'Booking Received.';
                        if(isset($loyalty_point_round) && !empty($loyalty_point_round)){
                            $notificationData->message = 'Your Reserve booking has been received - '.$booking_id.' and You have got '.$loyalty_point_round.' Loyalty points.';
                        }else{
                            $notificationData->message = 'Your Reserve booking has been received - '.$booking_id;
                        }
                        $notificationData->user_id = $userId;
                        $notificationData->property_id = $booking_data->property_id;
                        $notificationData->order_id = $data->id;
                        $notificationData->save();
                        send_notification(1, $userId, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                        $notificationData = new Notification;
                        $notificationData->user_type = 1;
                        $notificationData->notification_type = 1;
                        $notificationData->notification_for = 'Reserve Booking';
                        $notificationData->title = 'Reserve Booking Received.';
                        $notificationData->message = 'New reserve booking has been received - '.$booking_id;
                        $notificationData->user_id = 1;
                        $notificationData->property_id = $booking_data->property_id; 
                        $notificationData->order_id = $data->id;
                        $notificationData->save();
                        send_notification(1, 1, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                        if(isset($booking_data->influencer_id) && !empty($booking_data->influencer_id)){
                            $userdata_influencer = User::where('id',$booking_data->influencer_id)->first();
                            $notificationData = new Notification;
                            $notificationData->user_type = $userdata_influencer->user_type;
                            $notificationData->notification_type = 1;
                            $notificationData->notification_for = 'Reserve Booking';
                            $notificationData->title = 'Reserve Booking Received.';
                            $notificationData->message = 'Your reserve booking has been received - '.$booking_id;
                            $notificationData->user_id = $userdata_influencer->id;
                            $notificationData->property_id = $booking_data->property_id;
                            $notificationData->order_id = $data->id;
                            $notificationData->save();
                            send_notification(1, $userdata_influencer->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                        }

                        // $propertyHost = Property::where('id',$booking_data->property_id)->pluck('host_id')->first();
                        if(isset($booking_data->host_id) && !empty($booking_data->host_id)){
                            $Hostdata = User::where('id',$booking_data->host_id)->first();

                            $notificationHost = new Notification;
                            $notificationHost->user_type = $Hostdata->user_type;
                            $notificationHost->notification_type = 1;
                            $notificationHost->notification_for = 'Reserve Booking';
                            $notificationHost->title = 'Reserve Booking Received.';
                            $notificationHost->message = 'You have got a new Reserve Booking - '.$booking_id;
                            $notificationHost->user_id = $Hostdata->id;
                            $notificationHost->property_id = $booking_data->property_id;
                            $notificationHost->order_id = $data->id;
                            $notificationHost->save();
                        }
                    }


                    
                    $response['status'] = true;
                    $response['message'] = 'Booking reserve added successfully.';
                    $response['data'] = $booking_data   ;
                } else {
                    $response['status'] = false;
                    $response['message'] = 'Something went wrong, Please try again.';
                }
            } else {
                $response['status'] = false;
                $response['message'] = 'Invalid cart detail.';
            }
            return response()->json($response, 200);
        }
    }*/
    public function checkout_reserve(Request $request) {
 
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'cart_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'book_for' => 'required',
            'is_agency' => 'required',
            'policy_read' => 'required',
            'per_night_price' => 'required',
            'total_booking_amount' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors()->first();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {
            $cardData = Cart::where(['user_id'=>$userId, 'id'=>$input['cart_id']])->with('getProperty')->first();

            $user = User::where('email',$input['email'])->first();
            if(isset($user))
            {
                if($user->id != $userId)
                {
                    $userData = $user;
                    $userId = $user->id;
                }
            }


            

            $userdata = User::where('id',$userId)->first();
            $userdata->address = $input['address'];
            $userdata->postal_code = $input['postal_code'];   
            $userdata->save();

            if ($cardData) {
                PropertyReserveRequest::where(['guest_id'=>$cardData->user_id, 'property_id'=>$cardData->property_id])->delete();
                $data = new PropertyReserveRequest();

                $propertyData = DB::table('properties')->where(['id'=>$cardData->property_id])->pluck('price')->first();
                $host_amount = $propertyData * $cardData->total_days;

                if (isset($input['discount_code']) && $input['discount_code']) {
                    $checkCode = Discount::where('code', $input['discount_code'])->first();
                    $data->influencer_id = $checkCode->influencer;
                    $influencer_commission_percent = User::where('id',$checkCode->influencer)->pluck('commission')->first();
                    if(isset($influencer_commission_percent) && !empty($influencer_commission_percent)){
                        $data->influencer_amount = $host_amount * $influencer_commission_percent / 100;
                    }
                }

                $optional_service_amount = 0;

                // if (isset($input['selected_options'])   && !empty($input['selected_options'])) {
                //     $optional_services = json_decode($input['selected_options']);

                //     if ($optional_services) {
                //         foreach ($optional_services as $key => $value) {
                //             $optional_service_amount = $optional_service_amount + $value->value;
                //         }
                //     }
                // }

                 if (isset($input['selected_options']) &&  !empty($input['selected_options'])) {

                    $optional_services =json_decode($input['selected_options']);

                    if (!empty($optional_services)) {
                        foreach ($optional_services as $key => $value) {
                            $v_amount = $value->value ??0;
                            $optional_service_amount = $optional_service_amount + $v_amount;
                        }
                    }
                }

                $payment_email = $input['email'] ?? $userData->email;
                $payment_amount = $cardData->total_booking_amount + $optional_service_amount;

                if(isset($input['discount_amount']) && !empty($input['discount_amount']) ){
                    $payment_amount = $payment_amount - $input['discount_amount'];
                }
                $authorization_url = route('web.thank-you');
                $reference = '';
                $access_code = '';

                $data->guest_id = $userId;

                $adminCommission = AdminSettings::pluck('commission')->first();
                $admin_amount = 0;
                if(isset($adminCommission) && !empty($adminCommission)){
                    $admin_amount1 = $propertyData * $adminCommission / 100;
                    $admin_amount = $admin_amount1 * $cardData->total_days;
                }
                $data->host_id = $cardData->getProperty->host;
                $data->property_id = $cardData->property_id;
                $data->from_date = $cardData->start_date;
                $data->to_date = $cardData->end_date;
                $data->total_amount = $cardData->total_booking_amount;
                $data->admin_amount = $admin_amount;
                $data->host_amount = $propertyData * $cardData->total_days;
                $data->security_deposite = $cardData->getProperty->security_deposit_amount;
                if(isset($input['payment_method']) && $input['payment_method'] == 'card' ){
                    $data->booking_status = 'Confirmed-by-Host';
                }else{
                    $data->booking_status = 'Not-confirmed-by-Host';
                }
                // $data->booking_status = 'Not-confirmed-by-Host';
                $data->booking_type = 'Paid';
                $data->no_of_adult_guest = $cardData->adultCount ?? 0;
                $data->no_of_children_guest = $cardData->childCount ?? 0;
                $data->no_of_babies_guest = $cardData->infantCount ?? 0;
                $data->no_of_pet = $cardData->petCount ?? 0;
                $data->total_days = $cardData->total_days;
                $data->per_night_price = $cardData->per_night_price;
                $data->total_booking_amount = $cardData->total_booking_amount;
                $data->coupon_code = $input['discount_code'] ?? null;
                $data->discount_id = $input['discount_id'] ?? null;
                $data->discount_amount = $input['discount_amount'] ?? null;
                $data->is_agency = $input['is_agency'] ?? null;
                $data->payment_method = $input['payment_method'] ?? null;
                $data->book_for = $input['book_for'] ?? null;
                $data->send_special_offer = $input['send_special_offer'] ?? null;
                $data->redeem_royalty_points = $input['redeem_royalty_points'] ?? null;
                $data->policy_read = $input['policy_read'] ?? null;
                $data->personal_first_name = $input['first_name'] ?? null;
                $data->personal_last_name = $input['last_name'] ?? null;
                $data->personal_address = $input['address'] ?? null;
                $data->personal_country_id = $input['country_id'] ?? null;
                $data->personal_city = $input['city'] ?? null;
                $data->personal_postal_code = $input['postal_code'] ?? null;
                $data->personal_phone_number = $input['phone_number'] ?? null;
                $data->personal_email = $input['email'] ?? null;
                $data->personal_comment = $input['comment'] ?? null;
                $data->guest_first_name = $input['guest_first_name'] ?? null;
                $data->guest_last_name = $input['guest_last_name'] ?? null;
                $data->guest_address = $input['guest_address'] ?? null;

                $data->latitude = $input['latitude'] ?? null;
                $data->longitude = $input['longitude'] ?? null;
                $data->guest_latitude = $input['guest_latitude'] ?? null;
                $data->guest_longitude = $input['guest_longitude'] ?? null;

                $data->personal_country_code = $input['country_code'] ?? null;
                $data->guest_country_code = $input['guest_country_code'] ?? null;
                $data->personal_province = $input['province'] ?? null;                
                $data->guest_province = $input['guest_province'] ?? null;

                $data->guest_city = $input['guest_city'] ?? null;
                $data->guest_zipcode = $input['guest_zipcode'] ?? null;
                $data->guest_country_id = $input['guest_country_id'] ?? null;
                $data->guest_phone_number = $input['guest_phone_number'] ?? null;
                $data->guest_email = $input['guest_email'] ?? null;
                $data->card_holder_name = $input['card_holder_name'] ?? null;
                $data->card_number = $input['card_number'] ?? null;
                $data->month = $input['month'] ?? null;
                $data->year = $input['year'] ?? null;
                $data->cvv = $input['cvv'] ?? null;
                $data->selected_options = $input['selected_options'] ?? null;
                $data->optional_service_amount = $optional_service_amount;
                $data->authorization_url = $authorization_url;
                $data->reference = $reference;
                $data->access_code = $access_code;
                $data->booking_from = $input['booking_from'] ?? 'Website';
                

                if($cardData->property_id){
                    $propertyDetail = DB::table('properties')->where('id',$cardData->property_id)->first();
                    if(isset($propertyDetail) && !empty($propertyDetail)){
                        $price = $propertyDetail->price;
                        $tax = $propertyDetail->tax;
                        if(isset($price) && !empty($price)){

                            $commission = AdminSettings::pluck('commission')->first();
                            if(isset($commission) && !empty($commission)){
                                $price1 = $price * $commission / 100;
                                $price = $price1 + $price;
                                if(isset($tax) && !empty($tax)){
                                    $tax_price = $price * $tax / 100;
                                    // $price = $tax_price + $price;
                                }else{
                                    $tax_price = null;
                                }
                            }else{
                                if(isset($tax) && !empty($tax)){
                                    $tax_price = $price * $tax / 100;
                                    // $tax_price = $tax_price + $price;
                                }else{
                                    $tax_price = null;
                                }
                            }
                        }
                    }
                }
                if(isset($tax_price) && $tax_price != null){
                    $data->tax_amount = $tax_price * $cardData->total_days;
                }

                if ($data->save()) {
                    if(isset($cardData->property_id)){
                        $booking_token = Property::where('id',$cardData->property_id)->pluck('code')->first();
                    }else{
                        $booking_token = null;
                    }
                    $booking_id = 'SHTRNTL'.$data->id.'-'.$booking_token;
                    // dd(Crypt::encryptString($booking_id));
                    // dd(encrypt($booking_id));
                    $booking_token = Crypt::encryptString($booking_id);
                    // dd($booking_token);
                    PropertyReserveRequest::where('id', $data->id)->update(['booking_id' => $booking_id, 'booking_token' => $booking_token]);

                    //Remove Cart Data
                    $cardData->delete();
                    $booking_data = PropertyReserveRequest::where('id', $data->id)->with('getProperty')->first();
                    
                    // send email start  //guest
                    $email = EmailTemplateLang::where('email_id',26)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                    $subject = $email->subject;
                    $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                    $record = (object)[];
                    $description = str_replace("[NAME]", ucfirst($input['first_name']).' '.ucfirst($input['last_name']), $email->description);
                    $record->description = $description;
                    $record->name = $email->name;
                    $record->footer = $email->footer;
                    $record->username = $input['first_name'];
                    $record->property_name = $booking_data->getProperty->title;
                    $record->subject = $subject;
                    $record->user_email = $input['email'];
                    $record->cardData = $booking_data;
                    $record->check_in_url = url('guest-area/checkin/search');
    
                    Mail::send('emails.booking_reves_request', compact('record'), function ($message) use ($input, $subject) {
                        $message->to($input['email'], config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                    });


                    // host//
                    $host  = User::where('id',$cardData->getProperty->host)->first();
                    $email = EmailTemplateLang::where('email_id',30)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                    $subject = $email->subject;
                    $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                    $record = (object)[];

                    $description = str_replace("[NAME]", ucfirst($host->name).' '.ucfirst($host->surname), $email->description);
                    $description = str_replace("[Number]", $booking_data->total_days, $description);
                    $record->description = $description;
                    $record->name = $email->name;
                    $record->footer = $email->footer;
                    $record->username = $host->first_name;
                    $record->property_name = $booking_data->getProperty->title;
                    $record->subject = $subject;
                    $record->user_email = $host->email;
                    $record->cardData = $booking_data;
                    $record->check_in_url = url('guest-area/checkin/search');
    
                    Mail::send('emails.booking_reves_request_host', compact('record'), function ($message) use ($host, $subject) {
                        $message->to($host->email, config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                    });


                    if (isset($userId)) {
                        if($userdata->is_guest==1)
                        {
                            $userdata->name = $input['first_name'];
                            $userdata->surname = $input['last_name'];
                            $userdata->email = $input['email'];      
                            $userdata->country_code = $input['country_code'];                        
                            $userdata->mobile = $input['phone_number'];                            
                            $userdata->address = $input['address'];
                            $userdata->postal_code = $input['postal_code'];   
                            $userdata->ip_address = null;  
                            $userdata->latitude = $input['latitude'] ?? null;
                            $userdata->longitude = $input['longitude'] ?? null;
                            $userdata->save();
                        }
                        else
                        {
                            $userdata->name = $input['first_name'];
                            $userdata->surname = $input['last_name'];
                            $userdata->address = $input['address'];
                            $userdata->postal_code = $input['postal_code'];   
                            $userdata->save();    
                        }
                        $notificationData = new Notification;
                        $notificationData->user_type = $userdata->user_type;
                        $notificationData->notification_type = 1;
                        $notificationData->notification_for = 'Reserve Booking';
                        $notificationData->title = 'Booking Received.';
                        if(isset($loyalty_point_round) && !empty($loyalty_point_round)){
                            $notificationData->message = 'Your Reserve booking has been received - '.$booking_id.' and You have got '.$loyalty_point_round.' Loyalty points.';
                        }else{
                            $notificationData->message = 'Your Reserve booking has been received - '.$booking_id;
                        }
                        $notificationData->user_id = $userId;
                        $notificationData->property_id = $booking_data->property_id;
                        $notificationData->order_id = $data->id;
                        $notificationData->save();
                        send_notification(1, $userId, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                        $notificationData = new Notification;
                        $notificationData->user_type = 1;
                        $notificationData->notification_type = 1;
                        $notificationData->notification_for = 'Reserve Booking';
                        $notificationData->title = 'Reserve Booking Received.';
                        $notificationData->message = 'New reserve booking has been received - '.$booking_id;
                        $notificationData->user_id = 1;
                        $notificationData->property_id = $booking_data->property_id; 
                        $notificationData->order_id = $data->id;
                        $notificationData->save();
                        send_notification(1, 1, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );

                        if(isset($booking_data->influencer_id) && !empty($booking_data->influencer_id)){
                            $userdata_influencer = User::where('id',$booking_data->influencer_id)->first();
                            $notificationData = new Notification;
                            $notificationData->user_type = $userdata_influencer->user_type;
                            $notificationData->notification_type = 1;
                            $notificationData->notification_for = 'Reserve Booking';
                            $notificationData->title = 'Reserve Booking Received.';
                            $notificationData->message = 'Your reserve booking has been received - '.$booking_id;
                            $notificationData->user_id = $userdata_influencer->id;
                            $notificationData->property_id = $booking_data->property_id;
                            $notificationData->order_id = $data->id;
                            $notificationData->save();
                            send_notification(1, $userdata_influencer->id, $notificationData->title, array('title'=>$notificationData->title,'message'=>$notificationData->message) );
                        }

                        // $propertyHost = Property::where('id',$booking_data->property_id)->pluck('host_id')->first();
                        if(isset($booking_data->host_id) && !empty($booking_data->host_id)){
                            $Hostdata = User::where('id',$booking_data->host_id)->first();

                            $notificationHost = new Notification;
                            $notificationHost->user_type = $Hostdata->user_type;
                            $notificationHost->notification_type = 1;
                            $notificationHost->notification_for = 'Reserve Booking';
                            $notificationHost->title = 'Reserve Booking Received.';
                            $notificationHost->message = 'You have got a new Reserve Booking - '.$booking_id;
                            $notificationHost->user_id = $Hostdata->id;
                            $notificationHost->property_id = $booking_data->property_id;
                            $notificationHost->order_id = $data->id;
                            $notificationHost->save();
                        }
                    }


                    
                    $response['status'] = true;
                    $response['message'] = 'Booking reserve added successfully.';
                    $response['data'] = $booking_data   ;
                } else {
                    $response['status'] = false;
                    $response['message'] = 'Something went wrong, Please try again.';
                }
            } else {
                $response['status'] = false;
                $response['message'] = 'Invalid cart detail.';
            }
            return response()->json($response, 200);
        }
    }

    public function booking_verify(Request $request){
        try{
            if ($request->reference) {
                $live_key = 'sk_live_25064c14dee1f900fdc59be4fb6380ce54436ef1';
                $test_key = 'sk_test_6bac424039ba02cccf32ada2a81812f1c63fe9a7';
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$request->reference,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$test_key,
                    "Cache-Control: no-cache",
                ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {              
                    $response_json = json_decode($response);
                
                    if ($getBookingData = Booking::with('getProperty.getPropertyAddress.getPropertyCity')->where('booking_id', $request->booking_id)->first()) {
                        //dd($getBookingData);
                        $getBookingData->payment_json = $response;
                        $getBookingData->booking_status = 'Confirmed-by-Host';
                        $getBookingData->booking_type = 'Paid';
                        $getBookingData->reference = $request->reference;
                        
                        $getBookingData->save();

                        $booking_data =  $getBookingData;                
                        $email = EmailTemplateLang::where('email_id', 12)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();

                        $user = User::where('id',$booking_data->guest_id)->first();
                        $subject = $email->subject;
                    
                        $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                        $record = (object)[];
                        $description = $email->description;
                        $description = str_replace("[NAME]", ucwords($user->name).' '.$user->surname,  $email->description);
                        $description = str_replace("[City]", $booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name,  $description);
                        $record->description = $description;    
                        $record->name = $email->name;
                        $record->footer = $email->footer;
                        $record->username = $user->name;
                        $record->property_name = $booking_data->getProperty->title;
                        $record->subject = $subject;
                        $record->user_email = $user->email;
                        $record->cardData = $booking_data;
                        $record->check_in_url = url('guest-area/checkin/search');
                    
                        Mail::send('emails.booking_confirm', compact('record'), function ($message) use ($user, $subject) {
                            $message->to($user->email, config('app.name'))->subject($subject);
                            $message->from('customersupport@shortletrenrals.com', config('app.name'));
                        });

                        $hostdata = User::where('id',$booking_data->host_id)->first();
                        $viewPage = 'emails.booking_confimed_host';
                        
                        $email = EmailTemplateLang::where('email_id', 29)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
            
                        $subject = $email->subject;  
                        $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);   
                        $description = $email->description;
                        $description = str_replace("[PROPERTY_NAME]",$booking_data->getProperty->title, $description);  
                        $description = str_replace("[City]",$booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name, $description);  
                        $description = str_replace("[GUEST_NAME]",$user->name.' '.$user->surname, $description);
                        $description = str_replace("[NAME]",$hostdata->name.' '.$hostdata->surname, $description);
        
                        $record->description = $description;        
                        $record->footer = $email->footer;
                        $record->name = $email->name;
                        $record->username = $hostdata->name.' '.$hostdata->surname;
                        $record->property_name = $booking_data->getProperty->title;
                        $record->subject = $subject;
                        $record->user_email = $hostdata->email;
                        $record->cardData = $booking_data;
                        $record->check_in_url = url('guest-area/checkin/search');
                               
                        Mail::send($viewPage, compact('record'), function ($message) use ($hostdata, $subject) {
                            $message->to($hostdata->email, config('app.name'))->subject($subject);
                            $message->from('customersupport@shortletrenrals.com', config('app.name'));
                        });
                        $result['status'] = true;
                        $result['message'] = 'Booking found successfully.';
                        $result['data'] = $getBookingData;

                    } else {
                        $result['status'] = false;
                        $result['message'] = 'Booking not found.';
                        $result['data'] = null;
                    }

                    $is_guest = Session::get('is_guest');
                    $response = array();
                }
            } else {
               $result['status'] = false;
                $result['message'] = "Something went wrong!";
                $result['data'] = null;
            }
        } catch (\Exception $ex) {
            $result['status'] = false;
            $result['message'] = "Something went wrong!";
            $result['data'] = null;
        }
        return response()->json($result, 200);   
    }

    public function getbookings(Request $request) {
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;
        $bookingData = Booking::where('guest_id', $userId)->with('getProperty.getPropertyAddress','getProperty.getHostDetails','getProperty.getPropertyAddress','getProperty.getExtraService.getServiceData','getLoyaltyPoints');

        if (isset($input['status']) && $input['status']) {

            if ($input['status'] == 'Not-confirmed-by-Host') {
                $bookingData = $bookingData->where(function($query) use ($input){
                    $query->where('booking_status', 'Confirmed-by-Host')->orWhere('booking_status', 'Ongoing-Booking')->orWhere('booking_status', 'Not-confirmed-by-Host');
                });
            } else {
                $bookingData = $bookingData->where('booking_status', $input['status']);
            }
        }
        if(array_key_exists("order",$input) && !empty($input['order'])){
            $bookingData = $bookingData->orderBy('id', $input['order']);
        }

        $bookingData = $bookingData->get();

        if (count($bookingData)) {
            $response['status'] = true;
            $response['message'] = 'Booking found successfully.';
            $response['data'] = $bookingData;

        } else {
            $response['status'] = false;
            $response['message'] = 'Booking not found.';
        }
        return response()->json($response, 200);   
    }

    public function getreservations(Request $request) {
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;
        $bookingData = PropertyReserveRequest::where('guest_id', $userId)->with('getProperty.getPropertyAddress','getProperty.getHostDetails','getProperty.getPropertyAddress','getProperty.getExtraService.getServiceData');

        if (isset($input['status']) && $input['status']) {

            if ($input['status'] == 'Not-confirmed-by-Host') {
                $bookingData = $bookingData->where(function($query) use ($input){
                    $query->where('booking_status', 'Confirmed-by-Host')->orWhere('booking_status', 'Ongoing-Booking')->orWhere('booking_status', 'Not-confirmed-by-Host');
                });
            } else {
                $bookingData = $bookingData->where('booking_status', $input['status']);
            }
        }
        if(array_key_exists("order",$input) && !empty($input['order'])){
            $bookingData = $bookingData->orderBy('id', $input['order']);
        }

        $bookingData = $bookingData->get();

        if (count($bookingData)) {
            $response['status'] = true;
            $response['message'] = 'Reservation found successfully.';
            $response['data'] = $bookingData;

        } else {
            $response['status'] = false;
            $response['message'] = 'Reservation not found.';
        }
        return response()->json($response, 200);   
    }

    public function bookingDetail(Request $request) {
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;
        $bookingData = Booking::where(['id'=>$input['booking_id']])->with('getProperty.getPropertyAddress','getProperty.getPropertyAddress','getProperty.getExtraService.getServiceData')->first();

        if (isset($bookingData)) {
            $response['status'] = true;
            $response['message'] = 'Booking found successfully.';
            $response['data'] = $bookingData;

        } else {
            $response['status'] = false;
            $response['message'] = 'Booking not found.';
        }
        return response()->json($response, 200);   
    }

    public function submitRate(Request $request) {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'star' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors()->first();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {
            $userData = auth()->user();
            $userId =  $userData->id;
            $bookingData = Booking::where('id', $input['order_id'])->first();

            if ($bookingData) {
                $checkAlready = Rating::where(['order_id'=>$input['order_id'], 'user_id'=>$userId])->first();

                if (!$checkAlready) {
                    //submit rating here.........
                    $data = new Rating();
                    $data->user_id = $userId;
                    $data->order_id = $input['order_id'];
                    $data->property_id = $bookingData->property_id;
                    $data->rate = $input['star'];
                    $data->review = $input['review'] ?? null;




                    if ($data->save()) {
                        $totalRate = Rating::select([DB::raw('COUNT(*) AS total_rating'),DB::raw('SUM(rate) AS sum_rate')])->where('property_id', $bookingData->property_id)->first();
                        $avg_rating = $totalRate->sum_rate / $totalRate->total_rating;
                        Property::where('id', $bookingData->property_id)->update(['avg_rating' => $avg_rating, 'total_rating' => $totalRate->total_rating]);
                       
                       
                        $booking = Booking::with('getProperty')->findOrFail($input['order_id']);
                        $emailTemplate = EmailTemplateLang::where('email_id',19)->where('lang','en')->first();

                        $rating = Rating::where('order_id',$input['order_id'])->where('user_id',$userId)->first();
                        $data = ['data'=>$emailTemplate,'booking'=>$booking,'rating'=>$rating];

                        Mail::send('emails.review',$data, function ($message) use ($input, $emailTemplate) {
                            $message->to('shortlet@yopmail.com', config('app.name'))->subject($emailTemplate->subject);
                            $message->from('customersupport@shortletrenrals.com', config('app.name'));
                        });

                        //echo  view('emails.review',$data);
                        
                       
                        $response['status'] = true;
                        $response['message'] = 'Thank you for your rating.';

                    } else {
                        $response['status'] = false;
                        $response['message'] = 'Something went wrong, Please try again.';
                    }

                } else {
                    $response['status'] = false;
                    $response['message'] = 'You already submitted your feedback.';
                }

            } else {
                $response['status'] = false;
                $response['message'] = 'Booking not found.';
            }
            return response()->json($response, 200);   
        }
    }

    public function cancelBooking(Request $request) {
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors()->first();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {
            $bookingData = Booking::where('id', $input['order_id'])->first();
            $property = Property::where('id',$bookingData->property_id)->first();

            $userInfo = User::where('id',$bookingData->guest_id)->first();
            if ($bookingData) {
                $from_date = $bookingData->from_date;
                $current_date = Carbon::now()->format('Y-m-d h:i:s');

                $datetime1 = new DateTime($from_date);
                $datetime2 = new DateTime($current_date);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->format('%a');
                if($property->refund == 'YES')
                {
                    if(isset($days) && $days >= 14){
                        $total_amount = $bookingData->total_amount;

                        if(isset($bookingData->access_code))
                        {

                            $live_key = 'sk_live_25064c14dee1f900fdc59be4fb6380ce54436ef1';
                            $test_key = 'sk_test_6bac424039ba02cccf32ada2a81812f1c63fe9a7';


                        
                            $url = "https://api.paystack.co/refund";
                            $fields = [                                
                                'transaction' => $bookingData->access_code,
                                'amount' => $total_amount,
                            ];
                            $fields_string = http_build_query($fields);
                            //open connection
                            $ch = curl_init();                            
                            //set the url, number of POST vars, POST data
                            curl_setopt($ch,CURLOPT_URL, $url);
                            curl_setopt($ch,CURLOPT_POST, true);
                            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                "Authorization: Bearer ".$live_key,
                                "Cache-Control: no-cache",
                            ));                            
                            //So that curl_exec returns the contents of the cURL; rather than echoing it
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
                                    
                            $result = curl_exec($ch);
                            // echo $result;
                            $result1_decode = json_decode($result);

                            if ($result1_decode->status == true) {
                                $authorization_url = $result1_decode->data->authorization_url;
                                $reference = $result1_decode->data->reference;
                                $access_code = $result1_decode->data->access_code;
                            } else {
                                $authorization_url = '';
                                $reference = '';
                                $access_code = '';
                            }
                            /*Payment Api*/
                        }

                        $bookingData->refund_amount = $total_amount;
                        $bookingData->booking_status = 'Cancelled-Booking';
                        $bookingData->save();
                    }else if($days < 14 && $days > 3 ){
                        $total_amount = $bookingData->total_amount / 2;
                        if(isset($bookingData->access_code))
                        {
                            $url = "https://api.paystack.co/refund";
                            $fields = [                                
                                'transaction' => $bookingData->access_code,
                                'amount' => $total_amount,
                            ];
                            $fields_string = http_build_query($fields);

                            $live_key = 'sk_live_25064c14dee1f900fdc59be4fb6380ce54436ef1';
                            $test_key = 'sk_test_6bac424039ba02cccf32ada2a81812f1c63fe9a7';
                            //open connection
                            $ch = curl_init();                            
                            //set the url, number of POST vars, POST data
                            curl_setopt($ch,CURLOPT_URL, $url);
                            curl_setopt($ch,CURLOPT_POST, true);
                            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                "Authorization: Bearer ".$live_key,
                                "Cache-Control: no-cache",
                            ));                            
                            //So that curl_exec returns the contents of the cURL; rather than echoing it
                            curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 
                                    
                            $result = curl_exec($ch);
                            // echo $result;
                            $result1_decode = json_decode($result);

                            if ($result1_decode->status == true) {
                                $authorization_url = $result1_decode->data->authorization_url;
                                $reference = $result1_decode->data->reference;
                                $access_code = $result1_decode->data->access_code;
                            } else {
                                $authorization_url = '';
                                $reference = '';
                                $access_code = '';
                            }
                        }
                        /*Payment Api*/

                        $bookingData->refund_amount = $total_amount;
                        $bookingData->booking_status = 'Cancelled-Booking';
                        $bookingData->save();
                    }else if($days < 3 ){
                        $bookingData->refund_amount = 0;
                        $bookingData->booking_status = 'Cancelled-Booking';
                        $bookingData->save();                  
                    }
                    else{
                        $bookingData->refund_amount = 0;
                        $bookingData->booking_status = 'Cancelled-Booking';
                        $bookingData->save();  
                    }
                }
                else{
                    $bookingData->refund_amount = 0;
                    $bookingData->booking_status = 'Cancelled-Booking';
                    $bookingData->save();  
                }

                $booking_data = $bookingData;
                $record = (object)[];
                $userId = $booking_data->guest_id;
                $hostId = $booking_data->host_id;
                if ($userdata = User::where('id',$userId)->first()) {
                    $viewPage = 'emails.booking_cancel';
                    $email = EmailTemplateLang::where('email_id', 24)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                
                    $subject = $email->subject;  
                    $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);
                    $description = $email->description;
                    $description = str_replace("[NAME]", ucwords($userdata->name).' '.$userdata->surname,  $description);
                    $description = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title,  $description);
                    $record->description = str_replace("[Booking_Id]",$booking_data->booking_id, $description);
                    $description = str_replace("[City]", $booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name,  $description);
                    $checkout = '<br><br><b><a href="'.url('booking-rating-form/'.$booking_data->booking_id).'" style="border: 1px dashed #000; padding: 5px;margin: 22px;background: #F1592A;color: #fff;font-size: unset;font-weight: 800;"> Rate your stay</a></b><br><br>';
                    $description = str_replace("[Rate_your_stay]", $checkout, $description);
                    $record->description = $description;    
                    $record->name = $email->name;
                    $record->footer = $email->footer;
                    $record->name = $email->name;
                    $record->username = $userdata->name.' '.$userdata->surname;
                    $record->property_name = $booking_data->getProperty->title;
                    $record->subject = $subject;
                    $record->user_email = $userdata->email;
                    $record->cardData = $booking_data;
                    $record->check_in_url = url('guest-area/checkin/search');
        
                    Mail::send($viewPage, compact('record'), function ($message) use ($userdata, $subject) {
                        $message->to($userdata->email, config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                    });
                }
                
                
                // Host
                ;
                if ($hostdata = User::where('id',$hostId)->first()) {
                    $viewPage = 'emails.booking_confimed_host';
    
                    $email = EmailTemplateLang::where('email_id', 34)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
        
                    $subject = $email->subject;  
                    $subject = str_replace("[PROPERTY_NAME]", $booking_data->getProperty->title, $subject);   
                    $description = $email->description;
                    $description = str_replace("[PROPERTY_NAME]",$booking_data->getProperty->title, $description);  
                    $description = str_replace("[City]",$booking_data->getProperty->getPropertyAddress[0]->getPropertyCity->name, $description);  
                    $description = str_replace("[GUEST_NAME]",$hostdata->name.' '.$hostdata->surname, $description);
                    $description = str_replace("[NAME]",$hostdata->name.' '.$hostdata->surname, $description);
        
        
                    $record->description = $description;
        
                    $record->footer = $email->footer;
                    $record->name = $email->name;
                    $record->username = $hostdata->name.' '.$hostdata->surname;
                    $record->property_name = $booking_data->getProperty->title;
                    $record->subject = $subject;
                    $record->user_email = $hostdata->email;
                    $record->cardData = $booking_data;
                    $record->check_in_url = url('guest-area/checkin/search');
        
                    Mail::send($viewPage, compact('record'), function ($message) use ($hostdata, $subject) {
                        $message->to($hostdata->email, config('app.name'))->subject($subject);
                        $message->from('customersupport@shortletrenrals.com', config('app.name'));
                    });
                }
                

                
                // dd($from_date, $current_date, $interval, $days);
                // $bookingData->booking_status = 'Cancelled-Booking';
                // $bookingData->save();

                $response['status'] = true;
                $response['message'] = 'Booking cancelled successfully.';
                // $response['data'] = $bookingData;

            } else {
                $response['status'] = false;
                $response['message'] = 'Booking not found.';
            }
            return response()->json($response, 200);   
        }
    }

    public function reBooking(Request $request) {
        $input = $request->all();
        $userData = auth()->user();
        $userId =  $userData->id;

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors     =   $validator->errors()->first();
            $response['status'] = false;
            $response['message'] = $errors;
            return response()->json($response, 200);

        } else {
            $bookingData = Booking::where('id', $input['order_id'])->first();

            if ($bookingData) {
                //Cart-Data
                /*$data = new Cart();
                $data->user_id = $userId;
                $data->property_id = $input['property_id'];
                $data->start_date = $input['start_date'];
                $data->end_date = $input['end_date'];
                $data->total_days = $input['total_days'];
                $data->per_night_price = $input['per_night_price'];
                $data->total_booking_amount = $input['total_booking_amount'];
                $data->adultCount = $input['adultCount'] ?? 0;
                $data->childCount = $input['childCount'] ?? 0;
                $data->infantCount = $input['infantCount'] ?? 0;
                $data->petCount = $input['petCount'] ?? 0;
                $data->coupon_code = $input['coupon_code'] ?? null;
                $data->discount_id = $input['discount_id'] ?? null;
                $data->discount_amount = $input['discount_amount'] ?? null;

                if ($data->save()) {
                    $response['status'] = true;
                    $response['message'] = 'Cart added successfully.';

                } else {
                    $response['status'] = false;
                    $response['message'] = 'Something went wrong, Please try again.';
                }*/

                $response['status'] = true;
                $response['message'] = 'Re-Booking successfully.';
                $response['redirect'] = url('/checkout');
                // $response['data'] = $bookingData;

            } else {
                $response['status'] = false;
                $response['message'] = 'Booking not found.';
            }
            return response()->json($response, 200);   
        }
    }
}