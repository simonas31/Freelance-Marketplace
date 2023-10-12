<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HiredFreelancer extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = ['freelancer_id', 'client_id', 'job_id', 'hire_date'];
}
