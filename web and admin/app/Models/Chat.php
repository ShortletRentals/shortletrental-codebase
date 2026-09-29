<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class Chat extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public function sender_detail()
    {
        return $this->belongsTo('App\Models\User', 'sender_id');
    }

    public function receiver_detail()
    {
        return $this->belongsTo('App\Models\User', 'receiver_id');
    }

    public function last_message()
    {
        return $this->hasOne('App\Models\Message', 'chat_id')->orderBy('created_at', 'DESC');
    }

    public function first_message()
    {
        return $this->hasOne('App\Models\Message', 'chat_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message', 'chat_id');
    }

    public function getUnseenMessageCountAttribute()
    {
        $userId = \Auth::user()->id ?? 0;
        return Message::where(['chat_id' => $this->id])
            ->where('sent_by', '!=', $userId)
            ->where('is_read', '0')
            ->count();
    }
}
