<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'chat_id', 'user_id', 'send_time'];

    public $timestamps = false;

    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class, 'sender');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id');
    }
}
