<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Job extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['description', 'job_title', 'work_fields', 'pay_amount', 'posted_time', 'user_id', 'transaction_id', 'creation_confirmed', 'finished'];

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function hiredFreelancer(): HasOne
    {
        return $this->hasOne(HiredFreelancer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
