<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    public function message()
    {
        return $this->hasMany(Message::class);
    }

    public function chatUser()
    {
        return $this->hasMany(ChatUser::class);
    }
}
