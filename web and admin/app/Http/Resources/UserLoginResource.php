<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\MotherCompany;
class UserLoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        $motherCompany  = MotherCompany::where('user_id',$this->id)->first();
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'business_name' => $this->business_name,
            'username' => $this->username,
            'ar_name' => $this->ar_name,
            'email' => $this->email,
            'descriptions' => $this->descriptions,
            'country_code' => $this->country_code,
            'mobile' => $this->mobile,
            'profile_image' => $this->profile_image,
            'career_id' => $this->career_id,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,            
            'activity_id' => $this->activity_id,
            'mother_id'=>$this->mother_id,
            'street'=>$this->street,
            'zip_code'=>$this->zip_code,            
            'website'=>$this->website,            
            'license_number'=>$this->license_number,            
            'referral_code'=>$this->referral_code,            
            'status'=>$this->status,            
            'user_type' => $this->user_type,
            'is_subscriptions' => $this->is_subscriptions,
            'subscription_type' => $this->subscription_type,
            'subscription_end_date' => $this->subscription_end_date,     
            'currency'=>$this->currency,
            'country_name'=>$this->country_name,
            'city_name'=>$this->city_name,
            'career_name'=>$this->career_name,
            'mother_name'=>$this->mother_name,
            'activity_name'=>$this->activity_name,
            'agreementId' =>$this->agreementId,
            'motherCompany'=> $motherCompany ?? (object)[],
            'messageCounter'=>counterNotification($this->id),
            'followAdsCount'=>followCount($this->id)
        ];
    }
}
