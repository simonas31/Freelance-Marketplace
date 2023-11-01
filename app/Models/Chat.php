<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['user_id', 'receiver', 'deleted'];

    public function client_receiver()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function freelancer_receiver()
    {
        return $this->belongsTo(User::class, 'receiver');
    }
}
