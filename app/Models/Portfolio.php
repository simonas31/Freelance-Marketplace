<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['resume', 'user_id', 'work_fields', 'posted_time'];
    public function user(){
        return $this->hasOne(User::class, 'id');
    }
}
