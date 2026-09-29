<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
// use App\User;
use App\Models\PropertyAddress;
use App\Models\PanelNotifications;
use App\Models\Country;
use App\Models\Province;
use App\Models\Orders;
use App\Models\Tax;
use App\Models\User;
use App\Models\DeliveryPrice;
use App\Models\Commission;
use App\Models\Language;
use App\Models\Notification;
use App\Models\NotificationLang;
use App\Models\EmailTemplateLang;
use App\Models\PanelNotificationLang;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

function getCountryList() {
    // $propertyCountries = PropertyAddress::select('country_id', DB::raw('count(*) as total'))->groupBy('country_id')->get();
    $propertyCountries = PropertyAddress::select('property_address.province_id', DB::raw('count(province_id) as total') )->join('properties', 'properties.id', '=', 'property_address.property_id')->join('provinces', 'provinces.id', '=', 'property_address.province_id')->whereNotNull('provinces.id')->where('properties.status', 1)->groupBy('province_id')->get();
    // dd($propertyCountries);
    if (count($propertyCountries)) {
        foreach ($propertyCountries as $key => $value) {
            $provinceData = Province::where('id', $value->province_id)->first();
            // if(isset($provinceData) && !empty($provinceData)){
                $value->name = $provinceData->name ?? '';
            // }
        }
    }
    // dd($propertyCountries);
    return $propertyCountries;
}

if (!function_exists('file_checker')) {
    function file_checker($file, $type = null)
    {
        if (isset($file) && !empty($file) && file_exists(base_path() . '/' . $file)) {
            $images = url($file);
        } else {
            $images = url('public/img/' . $type . '.png');
        }
        return $images;
    }
}
if (!function_exists('notificationUserType')) {

    function notificationUserType()
    {
        $data = array('All' => '1', 'User' => '2', "Celebrity" => '3');
        return $data;
    }
}

if (!function_exists('notificationType')) {

    function notificationType()
    {
        $data = array('Email' => '1', 'SMS' => '2', "PushNotification" => '3', 'All' => '4');
        return $data;
    }
}

function calculateDimensions($width, $height, $maxwidth, $maxheight)
{

    if ($width != $height) {
        if ($width > $height) {
            $t_width = $maxwidth;
            $t_height = (($t_width * $height) / $width);
            //fix height
            if ($t_height > $maxheight) {
                $t_height = $maxheight;
                $t_width = (($width * $t_height) / $height);
            }
        } else {
            $t_height = $maxheight;
            $t_width = (($width * $t_height) / $height);
            //fix width
            if ($t_width > $maxwidth) {
                $t_width = $maxwidth;
                $t_height = (($t_width * $height) / $width);
            }
        }
    } else
        $t_width = $t_height = min($maxheight, $maxwidth);

    return array('height' => (int) $t_height, 'width' => (int) $t_width);
}
if (!function_exists('image_upload')) {
    function image_upload($file, $pathName, $multipalImageName = null)
    {
        $path = public_path() . '/uploads/' . $pathName . '/';
        $newFolder = strtoupper(date('M') . date('Y')) . '/';
        $folderPath = $path . $newFolder;
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true);
        }
        // $imgsize = getimagesize($file);
        // $width = $imgsize[0];
        // $height = $imgsize[1];
        // // dd($imgsize);
        // $imgre = calculateDimensions($width, $height, 450, 450);
        $image = $file;
        $extension = $image->getClientOriginalExtension();
        //$orignalname = $file->getClientOriginalName();       
        if ($multipalImageName == null) {
            $fileName = base64_encode(microtime()) . '-' . $pathName . '.' . $extension;
        } else {
            $fileName = base64_encode(microtime()) . '-' . $pathName . '-' . $multipalImageName . '.' . $extension;
        }
        // dd($extension);
        if (in_array($extension, ['jpeg', 'jpg', 'JPG', 'JPEG', 'png', 'PNG', 'gif', 'GIF', 'svg', 'webp'])) {
            $image->move($folderPath, $fileName);
            return array(true, $newFolder . $fileName, $extension, $fileName);
        } else {
            return array(false, "file should be in jpeg, jpg,png,gif,svg and webp format / double extension not allow.", '');
        }
    }
}
if (!function_exists('document_image_upload')) {
    function document_image_upload($file, $pathName, $multipalImageName = null)
    {
        $path = public_path() . '/uploads/' . $pathName . '/';
        $newFolder = strtoupper(date('M') . date('Y')) . '/';
        $folderPath = $path . $newFolder;
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true);
        }
        // $imgsize = getimagesize($file);
        // $width = $imgsize[0];
        // $height = $imgsize[1];
        // // dd($imgsize);
        // $imgre = calculateDimensions($width, $height, 450, 450);
        $image = $file;
        $extension = $image->getClientOriginalExtension();
        $orignalname = $file->getClientOriginalName();       
        if ($multipalImageName == null) {
            $fileName = base64_encode(microtime()) . '-' . $pathName.'-' . $orignalname;
        } else {
            $fileName = base64_encode(microtime()) . '-' . $pathName . '-' . $multipalImageName.'-' .$orignalname;
        }
        // dd($extension);
        if (in_array($extension, ['jpeg', 'jpg', 'JPG', 'JPEG', 'png', 'PNG', 'gif', 'GIF', 'svg', 'webp'])) {
            $image->move($folderPath, $fileName);
            return array(true, $newFolder . $fileName, $extension, $fileName);
        } else {
            return array(false, "file should be in jpeg, jpg,png,gif,svg and webp format / double extension not allow.", '');
        }
    }
}

function file_upload($file, $pathName, $multipalImageName = null)
{
    //print_r($file); die;
    $path =  public_path('storage') . '/' . $pathName . '/';
    $newFolder = strtoupper(date('M') . date('Y')) . '/';
    $folderPath         =   $path . $newFolder;

    if (!File::exists($folderPath)) {
        File::makeDirectory($folderPath, 0777, true);
    }

    $image = $file;
    $extension = $image->getClientOriginalExtension();
    // dd($extension);
    if ($multipalImageName == null) {
        $fileName = time() . '-' . $pathName . '.' . $extension;
    } else {
        $fileName = time() . '-' . $pathName . '-' . $multipalImageName . '.' . $extension;
    }
    $savePath  = 'storage/' . $pathName . '/' . $newFolder;

    if (in_array($extension, ['jpeg', 'jpg', 'JPG', 'JPEG', 'png', 'PNG', 'gif', 'GIF', 'pdf', 'docx', 'zip', 'xlsx', 'xls', 'mp4', 'MP4'])) {
        $image->move($folderPath, $fileName);
        return array(true,  $savePath . $fileName, $extension, $fileName);
    } else {
        return array(false, "file should be in jpeg, jpg,png,gif, pdf, docx, xlsx format / double extension not allow.", '');
    }
}

function send_notification_add($form_id, $user_type = 0, $notification_type = null, $notification_for = null, $order_id = null, $title = null, $message = null)
{
    $lang = App::getLocale();
   
     App::SetLocale('en');
    $lang = App::getLocale();

    $notification_title = __('api.notification_court_book_title');
    $message_lang = __('api.'.$message);
    $title_lang = __('api.'.$title);

    $notificationData = new Notification();
    $notificationData->user_id = $form_id;
    $notificationData->user_type = $user_type;
    $notificationData->notification_type = $notification_type;
    $notificationData->notification_for = $notification_for;
    $notificationData->order_id = $order_id;
    $notificationData->title = $title_lang;
    $notificationData->message = $message_lang;
    $notificationData->lang = $lang;
    $notificationData->save();
    // insert notification lang data
    if(isset($notificationData)){
     $notificationLang = new NotificationLang();
     $notificationLang->notification_id = $notificationData->id;
     $notificationLang->title = $title_lang;
     $notificationLang->message = $message_lang;
     $notificationLang->lang = $lang;
     $notificationLang->save();
        // insert AR lang
          App::SetLocale('ar');
        $lang = App::getLocale();
        $message_lang = __('api.'.$message);
        $title_lang = __('api.'.$title);
     $notificationLang = new NotificationLang();
     $notificationLang->notification_id = $notificationData->id;
     $notificationLang->title = $title_lang;
     $notificationLang->message = $message_lang;
     $notificationLang->lang = $lang;
     $notificationLang->save();
    }
}

function add_player_notification_by_admin($form_id, $user_type = 0, $notification_type = null, $notification_for = null, $order_id = null, $title = null, $message = null){
//   dd($form_id, $user_type, $notification_type, $notification_for, $order_id, $title, $message);
  $lang = Language::pluck('lang')->toArray();
//   dd($lang,$title,$message);
  foreach ($lang as $lang) {
      if ($lang == 'en') {
        $notificationData = new Notification();
        $notificationData->user_id = $form_id;
        $notificationData->user_type = $user_type;
        $notificationData->notification_type = $notification_type;
        $notificationData->notification_for = $notification_for;
        $notificationData->order_id = $order_id;
        $notificationData->title = $title[$lang];
        $notificationData->message = $message[$lang];
        $notificationData->lang = $lang;
        $notificationData->save();
      }
      $notificationLang = new NotificationLang();
      $notificationLang->notification_id = $notificationData->id;
      $notificationLang->title = $title[$lang];
      $notificationLang->message = $message[$lang];
      $notificationLang->lang = $lang;
      $notificationLang->save();
  }
}
function addNotificationByAdminForOwner($user_type = 1, $notification_type=1, $notification_for='admin_notification', $title, $message, $user_id , $order_id = 1){
    // dd($user_type = 1, $notification_type=1, $notification_for='admin_notification', $titles, $messages, $user_id , $order_id = 1);
    $lang = Language::pluck('lang')->toArray();
  foreach ($lang as $lang) {
      if ($lang == 'en') {
        $notificationData = new PanelNotifications();
        $notificationData->user_id = $user_id;
        $notificationData->user_type = $user_type;
        $notificationData->notification_type = $notification_type;
        $notificationData->notification_for = $notification_for;
        $notificationData->order_id = $order_id;
        $notificationData->title = $title[$lang];
        $notificationData->message = $message[$lang];
        $notificationData->save();
      }
        $notificationLang = new PanelNotificationLang();
        $notificationLang->panel_notification_id = $notificationData->id;
        $notificationLang->title = $title[$lang];
        $notificationLang->message = $message[$lang];
        $notificationLang->lang = $lang;
        $notificationLang->save();
  }
}

if (!function_exists('send_notification')) {
    function send_notification($form_id, $user_id = 0, $title = null, $body = array())
    {
        $arrNotification = array();
        $arrNotification["body"]  = $body;
        $arrNotification["title"] = $title;

        if (!$form_id) {
            $arrNotification["content-available"] = 1;
            $arrNotification["slient_notification"] = 'Yes';
        } else {
            $arrNotification["content-available"] = 0;
            $arrNotification["slient_notification"] = 'No';
        }
        $arrNotification["sound"] = "default";
        $arrNotification["type"] = 1;
        $user = User::where('id', $user_id)->with('devices')->first();

        if (isset($user->devices[0])) {
            foreach ($user->devices as $device) {
                $device_type = $device->device_type;
                if ($device_type != 'Android') {
                    $arrNotification["body"]  = $body['message'];
                } else {
                    $arrNotification["body"]  = $body['message'];
                }
                $user_id = $device->user_id;
                $device_id = $device->device_token;
                $result = push_notification($device_id, $arrNotification, $device_type, $form_id);
            }
        }
    }

    if (!function_exists('push_notification')) {
        function push_notification($registatoin_ids, $notification, $device_type, $form_id)
        {
            $url = 'https://fcm.googleapis.com/fcm/send';
            if ($device_type == "Android") {
                // dd('ddd',$device_type);
                $fields = array(
                    'to' => $registatoin_ids,
                    'notification' => $notification
                );
            } else {

                if (!$form_id) {
                    $fields = array(
                        'to' => $registatoin_ids,
                        'content-available' => 1,
                        'notification' => $notification
                    );
                } else {
                    $fields = array(
                        'to' => $registatoin_ids,
                        'notification' => $notification
                    );
                }
            }
            //Firebase API Key

            if ($device_type == 'Android') {
                $headers = array('Authorization:key=AAAAsFZozIE:APA91bEUelzUuXClWthodfdf7uAPpfe10O5bMpOMFN4xiBLlR9hZeBtTOBO8IogVldI0liYWf-EmjOCPr0tYfP6rt_OH-fMcUZJt_tdJ_KZu9_r_Ek-DP1cncGpD_xfxLJYNv7hyhu_z', 'Content-Type:application/json');
            } else {
                $headers = array('Authorization:key=AAAAsFZozIE:APA91bEUelzUuXClWthodfdf7uAPpfe10O5bMpOMFN4xiBLlR9hZeBtTOBO8IogVldI0liYWf-EmjOCPr0tYfP6rt_OH-fMcUZJt_tdJ_KZu9_r_Ek-DP1cncGpD_xfxLJYNv7hyhu_z', 'Content-Type:application/json');
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
           // dd($result);

            if ($result === false) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
        }
    }
}

function getNotificationList($user_id = null, $user_type = null)
{
    $notificationData = Notification::select('notifications.*');
    $count = 0;

    if (isset($user_id)) {
        $count = Notification::where('notifications.is_read', 0)->where('notifications.user_id', $user_id)->count();
        $notificationData = $notificationData->where('notifications.user_id', $user_id)->orderBy('notifications.id', 'desc')->get();
        // dd($notificationData,'dd',$count);
        
    } else {
        $count = Notification::where('notifications.is_read', 0)->count();
        $notificationData = $notificationData->orderBy('notifications.id', 'desc')->get();
    }

    $data['count'] = $count;
    $data['notificationData'] = $notificationData;
    return $data;
}
function getNotificationList_old($user_id = null, $user_type = null)
{
    $notificationData = PanelNotifications::select('panel_notifications.*');
    $count = 0;

    if (isset($user_id)) {
        $count = PanelNotifications::where('panel_notifications.is_read', 0)->where('panel_notifications.user_id', $user_id)->count();
        $notificationData = $notificationData->where('panel_notifications.user_id', $user_id)->orderBy('panel_notifications.id', 'desc')->get();
        // dd($notificationData,'dd',$count);
        
    } else {
        $count = PanelNotifications::where('panel_notifications.is_read', 0)->count();
        $notificationData = $notificationData->orderBy('panel_notifications.id', 'desc')->get();
    }

    $data['count'] = $count;
    $data['notificationData'] = $notificationData;
    return $data;
}

function getNotificationPlayerList($user_id = null, $user_type = null)
{
    $notificationData =Notification::select('notifications.*');
    $count = 0;

    if ($user_id) {
        $count =Notification::where('notifications.is_read', 0)->where('notifications.user_id', $user_id)->count();
        $notificationData = $notificationData->where('notifications.user_id', $user_id)->orderBy('notifications.id', 'desc')->get();
    } else {
        $count =Notification::where('notifications.is_read', 0)->count();
        $notificationData = $notificationData->orderBy('notifications.id', 'desc')->get();
    }

    $data['count'] = $count;
    $data['notificationData'] = $notificationData;
    return $data;
}

function getSettingData($field)
{

    if ($field == 'ALL') {
        $settingData = DeliveryPrice::select('*')->first();
        return $settingData;
    } else {
        $settingData = DeliveryPrice::select($field)->first();
        return $settingData->$field;
    }
}

function getCountryTaxByLatLong($lat, $long)
{
    $geolocation = $lat . ',' . $long;
    $request = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $geolocation . '&sensor=false&libraries=places&key=AIzaSyCKh3SzciMxOlZd0KiZoVtI1a-tbVF1yxY';
    $file_contents = file_get_contents($request);
    $json_decode = json_decode($file_contents);

    // dd($json_decode->results[0]);

    if (isset($json_decode->results[0])) {
        $response = array();
        $responseShortName = array();
        foreach ($json_decode->results[0]->address_components as $addressComponet) {
            if (in_array('political', $addressComponet->types)) {
                $response[] = $addressComponet->long_name;
            }
            $responseShortName[] = $addressComponet->short_name;
        }

        if (isset($response[0])) {
            $first  =  $response[0];
        } else {
            $first  = 'null';
        }
        if (isset($response[1])) {
            $second =  $response[1];
        } else {
            $second = 'null';
        }
        if (isset($response[2])) {
            $third  =  $response[2];
        } else {
            $third  = 'null';
        }
        if (isset($response[3])) {
            $fourth =  $response[3];
        } else {
            $fourth = 'null';
        }
        if (isset($response[4])) {
            $fifth  =  $response[4];
        } else {
            $fifth  = 'null';
        }

        /*if( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth != 'null' ) {
            echo "<br/>Address:: ".$first;
            echo "<br/>City:: ".$second;
            echo "<br/>State:: ".$fourth;
            echo "<br/>Country:: ".$fifth;
        }
        else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth == 'null'  ) {
            echo "<br/>Address:: ".$first;
            echo "<br/>City:: ".$second;
            echo "<br/>State:: ".$third;
            echo "<br/>Country:: ".$fourth;
        }
        else if ( $first != 'null' && $second != 'null' && $third != 'null' && $fourth == 'null' && $fifth == 'null' ) {
            echo "<br/>City:: ".$first;
            echo "<br/>State:: ".$second;
            echo "<br/>Country:: ".$third;
        }
        else if ( $first != 'null' && $second != 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
            echo "<br/>State:: ".$first;
            echo "<br/>Country:: ".$second;
        }
        else if ( $first != 'null' && $second == 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null'  ) {
            echo "<br/>Country:: ".$first;
        }*/

        // dd($responseShortName);

        if (isset($responseShortName[3]) && $responseShortName[3]) {
            $countryData = Country::where(['sortname' => $responseShortName[3]])->first();

            if ($countryData) {
                $taxData = Tax::where(['country_id' => $countryData->id])->first();

                if ($taxData) {
                    return $taxData->tax;
                } else {
                    return 1;
                }
            } else {
                return 1;
            }
        } else {
            return 1;
        }
    } else {
        return 1;
    }
}

function getCountryIdByLatLong($lat, $long)
{
    $geolocation = $lat . ',' . $long;
    $request = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $geolocation . '&sensor=false&libraries=places&key=AIzaSyCKh3SzciMxOlZd0KiZoVtI1a-tbVF1yxY';
    $file_contents = file_get_contents($request);
    $json_decode = json_decode($file_contents);
    $country = '';

    if (isset($json_decode->results[0])) {
        $response = array();
        $responseShortName = array();
        /*foreach ($json_decode->results[0]->address_components as $addressComponet) {
            if (in_array('political', $addressComponet->types)) {
                $response[] = $addressComponet->long_name;
            }
            $responseShortName[] = $addressComponet->short_name;
        }*/

        for($j=0;$j<count($json_decode->results[0]->address_components);$j++){

            $cn=array($json_decode->results[0]->address_components[$j]->types[0]);

            if(in_array("country", $cn)){
                $country = $json_decode->results[0]->address_components[$j]->long_name;
            }
        }

        /*if (isset($response[0])) {
            $first  =  $response[0];
        } else {
            $first  = 'null';
        }
        if (isset($response[1])) {
            $second =  $response[1];
        } else {
            $second = 'null';
        }
        if (isset($response[2])) {
            $third  =  $response[2];
        } else {
            $third  = 'null';
        }
        if (isset($response[3])) {
            $fourth =  $response[3];
        } else {
            $fourth = 'null';
        }
        if (isset($response[4])) {
            $fifth  =  $response[4];
        } else {
            $fifth  = 'null';
        }

        if ($first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth != 'null') {
            $country = $fifth;
        } else if ($first != 'null' && $second != 'null' && $third != 'null' && $fourth != 'null' && $fifth == 'null') {
            $country = $fourth;
        } else if ($first != 'null' && $second != 'null' && $third != 'null' && $fourth == 'null' && $fifth == 'null') {
            $country = $third;
        } else if ($first != 'null' && $second != 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null') {
            $country = $second;
        } else if ($first != 'null' && $second == 'null' && $third == 'null' && $fourth == 'null' && $fifth == 'null') {
            $country = $first;
        }*/

        if (isset($country) && $country) {
            $countryData = Country::where(['name' => $country])->first();

            if ($countryData) {
                return $countryData->id;
            } else {
                return '';
            }
        } else {
            return '';
        }
    } else {
        return '';
    }
}

function getLatLongByCountry($address)
{
    // $address = 'Jaipur, Rajasthan, India';
    $array = array();
    // $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key=AIzaSyCKh3SzciMxOlZd0KiZoVtI1a-tbVF1yxY');
    $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&key=AIzaSyCKh3SzciMxOlZd0KiZoVtI1a-tbVF1yxY');

    // We convert the JSON to an array
    $geo = json_decode($geo, true);
    // If everything is cool
    if ($geo['status'] = 'OK') {
        $latitude = $geo['results'][0]['geometry']['location']['lat'];
        $longitude = $geo['results'][0]['geometry']['location']['lng'];
        $array = array('lat'=> $latitude ,'lng'=>$longitude);
    }
    // dd($array);
    return $array;
}

function switchAccount($userId)
{
    auth()->logout();
    $user = User::find($userId);
    Auth::login($user);
}

function encryptPass($password)
{
    $sSalt = '20adeb83e85f03cfc84d0fb7e5f4d290';
    $sSalt = substr(hash('sha256', $sSalt, true), 0, 32);
    $method = 'aes-256-cbc';

    $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

    $encrypted = base64_encode(openssl_encrypt($password, $method, $sSalt, OPENSSL_RAW_DATA, $iv));
    return $encrypted;
}

function decryptPass($password)
{
    $sSalt = '20adeb83e85f03cfc84d0fb7e5f4d290';
    $sSalt = substr(hash('sha256', $sSalt, true), 0, 32);
    $method = 'aes-256-cbc';

    $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

    $decrypted = openssl_decrypt(base64_decode($password), $method, $sSalt, OPENSSL_RAW_DATA, $iv);
    return $decrypted;
}

function CallbackApis($url, $type)
{

    if ($type == 'Register') {
        $header = array();
    } else {
        $header = array();
    }

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => $header,
    ));
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    // echo $response;

    if ($httpcode == 200) {
        return  json_decode($response);
    } else if ($httpcode == 401) {
        return  array('staus' => false, 'message' => 'unauthorized');
    }
}


function commonAuthUserId()
{
    $request = app('request');
    return $secret_key = explode('|~@#|', decryptPass($request->header('SECRET-KEY')));
}

function ApiCurlMethod($method, $parms, $type, $method_type = 'POST')
{
    $locale = App::getLocale();

    $currency = Session::get('currentCurrency') ?? 'NGN';
    if ($type == 'Normal') {
        $header = array(
            'Accept:application/json',
            'currency:' . $currency,
            'Accept-Language:' . $locale
        );
    } else {
        $userData =  Session::get('AuthUserData') ?? null;
        $access_token = $userData->token ?? null;
        // dd($userData);

        // dd($access_token);  
        $header = array(
            'Accept:application/json',
            'Authorization:Bearer ' . $access_token,
            'currency:' . $currency,
            'Accept-Language:' . $locale
        );
    }




    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => url('api/auth/') . '/' . $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 30,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method_type,
        CURLOPT_POSTFIELDS => $parms,
        CURLOPT_HTTPHEADER => $header,
    ));

    $response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);
    // echo "<pre>";
    // print_r($response);
    // die;
    // dd($response);
    if ($httpcode == 200) {

        return  json_decode($response);
    } else if ($httpcode == 401) {
        //dd($method,0401);
        Session::forget('AuthUserData');
        Session::forget('AuthUserData');

        $url = url('/');
        header("Location: ".$url);
        exit();
        //  dd(json_decode($response));
    } else if ($httpcode == 402) {
        dd($method, 0402);
        // Session::forget('AuthUserData');
        // Session::forget('AuthUserData');
        //  dd(json_decode($response));

    } else if ($httpcode == 404) {
        dd($method, 0404);
        //Session::forget('AuthUserData');
        //Session::forget('AuthUserData');
        //      dd($method);
        // dd(json_decode($response));
    } else if ($httpcode == 500) {
        dd($response, 500);
        //Session::forget('AuthUserData');
        // Session::forget('AuthUserData');
        //  dd(json_decode($response));
    }
}

function ApiAuthValid($method, $parms, $type, $method_type = 'POST')
{
    $locale = App::getLocale();

    $currency = Session::get('currentCurrency') ?? 'NGN';
    if ($type == 'Normal') {
        $header = array(
            'Accept:application/json',
            'currency:' . $currency,
            'Accept-Language:' . $locale
        );
    } else {
        $userData =  Session::get('AuthUserData') ?? null;
        $access_token = $userData->token ?? null;
        // dd($userData);

        // dd($access_token);  
        $header = array(
            'Accept:application/json',
            'Authorization:Bearer ' . $access_token,
            'currency:' . $currency,
            'Accept-Language:' . $locale
        );
    }


    // print_r($header);die;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => url('api/auth/') . '/' . $method,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 30,
        CURLOPT_TIMEOUT => 300,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method_type,
        CURLOPT_POSTFIELDS => $parms,
        CURLOPT_HTTPHEADER => $header,
    ));

    $response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    curl_close($curl);
    // echo "<pre>";
    // print_r($response);
    // die;
    // dd($response);
    $data['response'] = json_decode($response);
    $data['httpcode'] = $httpcode;
    return  $data;
}
function send_admin_notification($message='',$title='',$channel_name=''){
    // dd('dddddddddd');
    //Admin Notification//
		$publishKey ='pub-c-d7274ea7-836f-4faf-b396-bc4bc9e9f99e';
		$subscribeKey= 'sub-c-560305a8-8b03-11eb-83e5-b62f35940104';
	
		$curl_admin = curl_init();
		curl_setopt_array($curl_admin, array(
		  CURLOPT_URL => "https://ps.pndsn.com/publish/$publishKey/$subscribeKey/0/$channel_name/myCallback?store=0&uuid=db9c5e39-7c95-40f5-8d71-125765b6f561",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "{\n  \"message\": \"$message\", \"title\": \"$title\"}\n",
		  CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"content-type: application/json",
			"location: /publish/$publishKey/$subscribeKey/0/pubnub_onboarding_channel_admin_1/0",
			"postman-token: d536d8da-8709-14cb-3c6d-ee6e19bc9fe5"
		  ),
		));

		$responseNew = curl_exec($curl_admin);
		$err = curl_error($curl_admin);

		curl_close($curl_admin);

		if ($err) {
		//   echo "cURL Error #:" . $err;
		} else {
		//   echo $responseNew;
		}
		//Admin Notification End//
}
function add_admin_notification($user_type = '', $notification_type='', $notification_for='', $title='', $message='', $user_id='', $order_id=''){
    $lang = App::getLocale();
   
     App::SetLocale('en');
    $lang = App::getLocale();

    // $notification_title = __('api.notification_court_book_title');
    $message_lang = __('backend.'.$message);
    $title_lang = __('backend.'.$title);

    $notificationData = new PanelNotifications();
    $notificationData->user_id = $user_id;
    $notificationData->user_type = $user_type;
    $notificationData->notification_type = $notification_type;
    $notificationData->notification_for = $notification_for;
    $notificationData->order_id = $order_id;
    $notificationData->title = $title_lang;
    $notificationData->message = $message_lang;
    $notificationData->save();
    // insert notification lang data
    if(isset($notificationData)){
     $notificationLang = new PanelNotificationLang();
     $notificationLang->panel_notification_id = $notificationData->id;
     $notificationLang->title = $title_lang;
     $notificationLang->message = $message_lang;
     $notificationLang->lang = $lang;
     $notificationLang->save();
        // insert AR lang
          App::SetLocale('ar');
        $lang = App::getLocale();
        $message_lang = __('backend.'.$message);
        $title_lang = __('backend.'.$title);
     $notificationLang = new PanelNotificationLang();
     $notificationLang->panel_notification_id = $notificationData->id;
     $notificationLang->title = $title_lang;
     $notificationLang->message = $message_lang;
     $notificationLang->lang = $lang;
     $notificationLang->save();
    }
}

function customeRoute($route=null,$params=null){

    if(!empty(Auth::user())){
        return   route('admin.'.$route,$params);
    }else{
        return   route($route,$params);
    }
}

function customeRedirect($route=null,$params=null,$type,$message){
    if(!empty(Auth::user())){
    if(Auth::user()->role_id==1)
     {
        return  redirect()->route('admin.'.$route,$params)->with($type,$message);
     }
     else if(Auth::user()->role_id==2)
     {
        return  redirect()->route('subadmin.'.$route,$params)->with($type,$message);
     }
    }else{
        return  redirect()->route($route,$params)->with($type,$message);
    }
}

function createAction($data){
    
    return '<div class="d-flex order-actions" role="group" aria-label="Basic example">'.$data.'</div>';
     //return  '<div class="dropdown"><a href="#" class="dropdown-toggle card-drop" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-dots-horizontal font-size-18"></i></a><ul class="dropdown-menu dropdown-menu-right">'.$data.'</ul></div>';
}

function editAction($route,$parms){
    
    return '<a href="'.customeRoute($route,$parms).'" class=""><i class="bx bx-edit" ></i></a>';
}

function viewAction($route,$parms){
    return '<a href="'.customeRoute($route,$parms).'" class="ms-3"><i class="bx bx-show" ></i></a>';
}

function viewCalendar($route,$parms){
    return '<a href="'.customeRoute($route,$parms).'" class="ms-3"><i class="bx bx-calendar" ></i></a>';
}

function permissionAction($route,$parms){
    return '<a href="'.customeRoute($route,$parms).'" class="ms-3"><i class="bx bx-key" ></i></a>';
}

function deleteAction($route,$parms){
    return '<a href="javascript:;" data-path="'.customeRoute($route).'", data-id="'.$parms['id'].'"  class="btn btn-danger deleteAction"><i class="bx bx-trash"></i> Delete</a>';
}

function defaultAction($route,$parms,$icon,$class,$name){
    return '<a href="'.customeRoute($route,$parms).'" class="btn btn-'.$name.'"><i style=" color: #fff !important;" class="fas fa-'.$icon.' text-'.$class.' mr-1"></i></a></li>';
}

function defaultAjaxAction($route,$parms,$icon,$action,$class,$name){
    return '<a href="javascript:;" data-url="'.customeRoute($route,$parms).'" class="btn btn-'.$class.' '.$action.'"><i class="fas fa-'.$icon.'  mr-1"></i></a>';
}

function statusAction($status, $id, $array = null, $class = 'statusAction', $path = null,$placeholder = null) {
    if (!$array) {        
        $array = [0 => 'Pending',1 => 'Active', 2 => 'Inactive'];
    }
    return $html = Form::select('active', $array, $status, ['class' => 'form-control ' . $class,'style'=>'width:100px' ,'id' => $id, 'data-path' => customeRoute($path), 'data-value' => $status,'placeholder'=>$placeholder]);
}

function bookingStatus($status, $id, $array = null, $class = 'booking_status', $path = null,$placeholder = null) {
    if (!$array) {
        $array = ['Not-confirmed-by-Host'=>'Not confirmed by Host','Confirmed-by-Host'=>'Confirmed by Host','Ongoing-Booking'=>'Ongoing Booking','Completed-Booking'=>'Completed Booking','Cancelled-Booking'=>'Cancelled Booking'];
    }
    return $html = Form::select('booking_status', $array, $status, ['class' => 'form-control ' . $class,'style'=>'width:100px' ,'id' => $id, 'data-path' => customeRoute($path), 'data-value' => $status,'placeholder'=>$placeholder]);
}
function defaltStatus($status, $id, $array = null, $class = 'is_defalt', $path = null,$placeholder = null) {
    if (!$array) {
        $array = ['Yes'=>'Yes','No'=>'No'];
    }
    return $html = Form::select('is_defalt', $array, $status, ['class' => 'form-control ' . $class,'style'=>'width:100px' ,'id' => $id, 'data-path' => customeRoute($path), 'data-value' => $status,'placeholder'=>$placeholder]);
}
function bookingType($status, $id, $array = null, $class = 'booking_type', $path = null,$placeholder = null) {
    if (!$array) {
        $array = ['Pre-booking'=>'Pre booking','Confirmed'=>'Confirmed','Information_Request'=>'Information Request','Owner_Booking'=>'Owner Booking','Not_Available'=>'Not Available','Paid'=>'Paid'];
    }
    return $html = Form::select('booking_type', $array, $status, ['class' => 'form-control ' . $class,'style'=>'width:100px' ,'id' => $id, 'data-path' => customeRoute($path), 'data-value' => $status,'placeholder'=>$placeholder]);
}

function sattlementStatus($status, $id, $array = null, $class = 'sattlement_status', $path = null,$placeholder = null) {
    if (!$array) {
        $array = ['Done'=>'Done','Not_done'=>'Not done'];
    }
    return $html = Form::select('sattlement_status', $array, $status, ['class' => 'form-control ' . $class,'style'=>'width:100px' ,'id' => $id, 'data-path' => customeRoute($path), 'data-value' => $status,'placeholder'=>$placeholder]);
}

function checkContractExpire() {
    $getExpireDatas = Commission::whereDate('end_date', '<=', date('Y-m-d'))->where('is_expire_mail_sent', 'No')->get();
    $login_user_data = auth()->user();
    
    if (count($getExpireDatas)) {

        foreach ($getExpireDatas as $key => $value) {

            if ($value->all_hosts == 'No') {
                $value->is_expire_mail_sent = 'Yes';
                $value->save();

                $userData = User::where(['id'=>$value->host_id])->first();
                // send email start
                $email = EmailTemplateLang::where('email_id', 13)->where('lang', 'en')->select(['name', 'subject', 'description', 'footer'])->first();
                $subject = $email->subject;
                $record = (object)[];
                $record->description = $email->description;
                $record->footer = $email->footer;
                $record->username = $userData->name;
                $record->useremail = $userData->email;
                $record->admin_name = $login_user_data->name;
                $record->end_date = date('M d, Y', strtotime($value->end_date));
                $record->subject = $subject;
                $record->user_email = $userData->email;

                Mail::send('emails.contract', compact('record'), function ($message) use ($login_user_data, $subject) {
                    $message->to($login_user_data->email, config('app.name'))->subject($subject);
                    $message->from('customersupport@shortletrenrals.com', config('app.name'));
                });
                // send email end
            }
        }
    }
    return true;
}

function checkGuestLogin($ipAddresses) {
    $details = ip_details('122.160.94.19');
    $country_code = '234';

    if ($details && isset($details->country)) {
        $countryData = Country::where(['sortname' => $details->country])->first();

        if ($countryData) {
            $country_code = $countryData->phonecode;
        }
    }

    // echo $details->city.'<br/>';     // => Mountain View
    // echo $details->country.'<br/>';  // => US
    // echo $details->org.'<br/>';      // => AS15169 Google Inc.
    // echo $details->hostname.'<br/>'; // => google-public-dns-a.google.com

    $userData = User::where(['ip_address'=>$ipAddresses])->first();

    if ($userData) {
        $userData->name =null;// $ipAddresses;
        $userData->email = $ipAddresses.'@mailinator.com';
        $userData->password = Hash::make($ipAddresses);
        $userData->country_code = $country_code;
        $userData->mobile = $ipAddresses;
        $userData->ip_address = $ipAddresses;
        $userData->is_guest = 1;
        $userData->save();

        $credentials = ['country_code' => $country_code, 'mobile' => $ipAddresses, 'password' => $ipAddresses];

        if (!$token = JWTAuth::attempt($credentials)) {
            $response['status'] = false;
            $response['message'] = __("api.invalid_user_login");
            return response()->json($response, 200);
        }
        $response['status'] = true;
        $response['token'] = $token;
        $response['data'] = new UserResource($userData);;
        $response['message'] = 'User register successfully.';
        return json_encode($response);

    } else {
        $newUserData = new User();
        $unique_id = random_int(10000000, 99999999);
        $newUserData->unique_id = $unique_id;
        $newUserData->name =null; //$ipAddresses;
        $newUserData->email = $ipAddresses.'@mailinator.com';
        $newUserData->password = Hash::make($ipAddresses);
        $newUserData->country_code = $country_code;
        $newUserData->mobile = $ipAddresses;
        $newUserData->status = 1;
        $newUserData->user_type = 4;
        $newUserData->is_guest = 1;
        $newUserData->ip_address = $ipAddresses;
        $newUserData->save();

        if ($newUserData) {
            $credentials = ['country_code' => $country_code, 'mobile' => $ipAddresses, 'password' => $ipAddresses];

            if (!$token = JWTAuth::attempt($credentials)) {
                $response['status'] = false;
                $response['message'] = __("api.invalid_user_login");
                return json_encode($response);
            }
            $response['status'] = true;
            $response['token'] = $token;
            $response['data'] = new UserResource($newUserData);;
            $response['message'] = 'User register successfully.';
            return json_encode($response);

        } else {
            $response['status'] = false;
            $response['message'] = __("Technical error, please try again.");
            return json_encode($response);
        }
    }
}

function ip_details($ip) {
    $json = file_get_contents("http://ipinfo.io/{$ip}");
    $details = json_decode($json);
    return $details;
}

if (!function_exists('validation_error_response')) {
    function validation_error_response($errors)
    {
        $response = [];
        $counter = 0;
        foreach ($errors as $key => $value) {
            if ($counter > 0) {
                break;
            }

            $errorMessage = $value[0];
        }

        $response['message'] = $errorMessage;
        $response['success'] = FALSE;
        $response['status'] = 200;
        return $response;
    }
}
function time_Ago($time) {
    // Calculate difference between current
    // time and given timestamp in seconds
    $diff = time() - $time;
    // Time difference in seconds
    $sec = $diff;
    // Convert time difference in minutes
    $min = round($diff / 60 );
    // Convert time difference in hours
    $hrs = round($diff / 3600);
    // Convert time difference in days
    $days = round($diff / 86400 );
    // Convert time difference in weeks
    $weeks = round($diff / 604800);
    // Convert time difference in months
    $mnths = round($diff / 2600640 );
    // Convert time difference in years
    $yrs = round($diff / 31207680 );
      
    // Check for seconds
    if($sec <= 60) {
        echo "$sec seconds ago";
    }

    // Check for minutes
    else if($min <= 60) {
        if($min==1) {
            echo "one minute ago";
        }
        else {
            echo "$min minutes ago";
        }
    }
      
    // Check for hours
    else if($hrs <= 24) {
        if($hrs == 1) { 
            echo "an hour ago";
        }
        else {
            echo "$hrs hours ago";
        }
    }
      
    // Check for days
    else if($days <= 7) {
        if($days == 1) {
            echo "Yesterday";
        }
        else {
            echo "$days days ago";
        }
    }
      
    // Check for weeks
    else if($weeks <= 4.3) {
        if($weeks == 1) {
            echo "a week ago";
        }
        else {
            echo "$weeks weeks ago";
        }
    }
      
    // Check for months
    else if($mnths <= 12) {
        if($mnths == 1) {
            echo "a month ago";
        }
        else {
            echo "$mnths months ago";
        }
    }
      
    // Check for years
    else {
        if($yrs == 1) {
            echo "one year ago";
        }
        else {
            echo "$yrs years ago";
        }
    }
}

function fileUrl($type='s3',$value,$model='property/')
{
    if($type=='s3'){
        //return 'https://shortletrental.s3.amazonaws.com/' . $value;
        return 'https://d1o88e3pxnk9ri.cloudfront.net/uploads/'.$model . str_replace('uploads/','',str_replace($model,'',$value));
        
    }
}

function fileUploads($platform,$file,$path,$url=false){
    $data = array(); 
    $data['file'] =  null;
    $data['thumb'] = null;
    $org_path = 'uploads/';  
    if(isset($file))
    {   

        $filename = str_replace(' ','',str_replace('.','',microtime())).'-'.str_replace('/','-',$path);
      
        $data['extension'] = $extension = $file->getClientOriginalExtension();
        $file_name = $filename.'.'.$extension;

        $file_name_path = $org_path.$path.'/'.$file_name;
      
        if(in_array(strtolower($extension),['apng','avif','gif','jpeg','jfif','jpg','pjpeg','pjp','png','svg','webp']))
        {
            $data['type'] = 'IMAGE';                
        }
        elseif(in_array(strtolower($extension),['mp4','mov','wmv','avi','avchd','flv','mkv','webm','mpeg-2']))
        {
            $data['type'] = 'VIDEO';
            $thumb_path = public_path('storage').'/'.$path.'/';
        }
        elseif(in_array(strtolower($extension),['pdf','doc','docx','psd','xls','xlsx','ppt','csv','json','zip']))
        {
            $data['type'] = 'DOCUMENT';
        }
        else
        {
            $data['status'] = false;  
            $data['message'] = 'Invalid file.';
        }


        if($platform == 's3')
        {   
            $data['status'] = Storage::disk('s3')->put($file_name_path, file_get_contents($file));
            $data['message'] = 'success.';
            $data['file'] =  $file_name_path;
            $data['file_name'] =  $file_name;
            if($data['type'] == 'VIDEO')
            {
                $file->move($thumb_path, $file_name);
                $video = $thumb_path.$file_name; 
                $thumb_image = str_replace('mp4','jpg',strtolower($file_name));      
                $thumb_image = str_replace('mov','jpg',$thumb_image);
                $thumb_image = str_replace('wmv','jpg',$thumb_image);
                $thumb_image = str_replace('avi','jpg',$thumb_image);
                $thumb_image = str_replace('avchd','jpg',$thumb_image);
                $thumb_image = str_replace('fly','jpg',$thumb_image);   
                $thumb_image = str_replace('mkv','jpg',$thumb_image);   
                $thumb_image = str_replace('webm','jpg',$thumb_image);                 
                $thumb_image = str_replace('mpeg-2','jpg',$thumb_image);                    
                $thumbnail = $thumb_path.$thumb_image;
                shell_exec("ffmpeg -i $video -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1");    
                shell_exec("fmpeg -r 1/5 -i $thumbnail -c:v libx264 -vf fps=25 -pix_fmt yuv420p $video");
                $thumb = Storage::disk('s3')->put($org_path.$path.'/'.$thumb_image, file_get_contents($thumbnail));
                $data['thumb'] =  $org_path.$path.'/'.$thumb_image;
                unlink($video);
                unlink($thumbnail);
            }
        }
        else
        {
            $data['status'] = false;            
            $data['message'] = 'platform not found.';
        }
    }
    else{
        $data['status'] = false;
        $data['message'] = 'file not found.';
    }
    return $data;   
}


function fileUploadsCompress($platform,$file,$path,$url=false){
    $data = array(); 
    $data['file'] =  null;
    $data['thumb'] = null;
    $org_path = 'uploads/';  
    if(isset($file))
    {   

        $filename = str_replace(' ','',str_replace('.','',microtime())).'-'.str_replace('/','-',$path);
      
    
        $file_name = $filename.'.jpg';

        $file_name_path = $org_path.$path.'/'.$file_name;
      
        
        $data['type'] = 'IMAGE';                
       


        if($platform == 's3')
        {   
            $data['status'] = Storage::disk('s3')->put($file_name_path, $file);
            $data['message'] = 'success.';
            $data['file'] =  $file_name_path;
            $data['file_name'] =  $file_name;
        }
        else
        {
            $data['status'] = false;            
            $data['message'] = 'platform not found.';
        }
    }
    else{
        $data['status'] = false;
        $data['message'] = 'file not found.';
    }
    return $data;   
}