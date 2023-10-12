<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'amount', 'tax', 'receiver', 'job_id', 'completed'];

    public $timestamps = false;

    public function job(): HasOne
    {
        return $this->hasOne(Job::class);
    }
}
