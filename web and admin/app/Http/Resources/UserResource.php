<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
/*use App\Models\MotherCompany;
use App\Models\Product;
use App\Models\Category;*/
use App\Models\Country;
use App\Models\Province;
use App\Models\City;
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
           
        /*$category=Category::select('categories.id','categories.name')
        ->join('products', 'products.category_id', '=', 'categories.id')
        ->where('products.user_id',$this->id)
        ->where('products.is_purchase','no')
        ->where('categories.status','1')
        ->where('products.status',1)
        ->groupBy('categories.id')                      
        ->get(); //End Function
        if(count($category)){
            foreach($category as $key => $categorys){ 
                //Category Data
                $category[$key]->product_data=productByCategory($categorys->id,$this->id);
            } 
        }
        $motherCompany  = MotherCompany::where('user_id',$this->id)->first();*/
        if(isset($this->country_id) && !empty($this->country_id)){
            $country_name = Country::where('id',$this->country_id)->pluck('name')->first();
        }else{
            $country_name = '';
        }
        if(isset($this->province_id) && !empty($this->province_id)){
            $province_name = Province::where('id',$this->province_id)->pluck('name')->first();
        }else{
            $province_name = '';
        }
        if(isset($this->city_id) && !empty($this->city_id)){
            $city_name = City::where('id',$this->city_id)->pluck('name')->first();
        }else{
            $city_name = '';
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'surname' => $this->surname,
            'fullname' => $this->name.' '.$this->surname,
            'title' => $this->title,
            'remarks' => $this->remarks,
            'email' => $this->email,
            'country_code' => $this->country_code,
            'mobile' => $this->mobile,
            'secondary_email' => $this->secondary_email,
            'second_country_code' => $this->second_country_code,
            'second_mobile' => $this->second_mobile,
            'image' => $this->image,
            'user_type' => $this->user_type,
            'is_super_host' => $this->is_super_host,
            'is_chat_disabled_for_host' => $this->is_chat_disabled_for_host,
            'role' => $this->role,
            'street' => $this->street,
            'street_number' => $this->street_number,
            'number' => $this->number,
            'country_id' => $this->country_id,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'country_name' => $country_name,
            'province_name' => $province_name,
            'city_name' => $city_name,
            'postal_code' => $this->postal_code,
            'dob' => $this->dob,
            'address' => $this->address,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'document_number' => $this->document_number,
            'document_image' => $this->document_image,
            'id_card_image' => $this->id_card_image,
            'notification_on_off' => $this->notification_on_off,
            'language' => $this->language,
            'device_type' => $this->device_type,
            'is_guest' => $this->is_guest,
            'ip_address' => $this->ip_address,
        ];
    }
}
