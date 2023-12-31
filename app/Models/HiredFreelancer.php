<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HiredFreelancer extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = ['freelancer_id', 'client_id', 'job_id', 'hire_date', 'confirmed'];

    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
