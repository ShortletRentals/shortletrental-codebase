<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Auth;

class ContactUs extends Model
{    
    protected $table = 'contact_us';
    protected $fillable = [
        'name','first_name','last_name','city','state','email','mobile','country_code','message','status'
    ];

   
}
