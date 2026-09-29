<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public function chat_detail()
    {
        return $this->belongsTo('App\Models\Chat', 'chat_id');
    }

    public function sent_by_detail()
    {
        return $this->belongsTo('App\Models\User', 'sent_by');
    }

    public function getFileAttribute($value = "")
    {
        if (!empty($value)) {
            if ($this->file_type == 'image') {
                return asset('uploads/images/' . $value);
            } elseif ($this->file_type == 'video') {
                return asset('uploads/videos/' . $value);
            } else {
                return asset('uploads/images/' . $value);
            }
        }
        return "";
    }
}
