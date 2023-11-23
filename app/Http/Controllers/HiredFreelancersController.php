<?php

namespace App\Http\Controllers;

use App\Models\HiredFreelancer;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HiredFreelancersController extends Controller
{
    public function userHiredFreelancers(){
        $freelancers = HiredFreelancer::with('freelancer')
            ->join('jobs', 'hired_freelancers.job_id', '=', 'jobs.id')
            ->join('profiles', 'hired_freelancers.freelancer_id', '=', 'profiles.user_id')
            ->join('portfolios', 'hired_freelancers.freelancer_id', '=', 'portfolios.user_id')
            ->join('ratings', 'hired_freelancers.freelancer_id', '=', 'ratings.freelancer_id')
            ->where('hired_freelancers.confirmed', '=', 1)->get()->toArray();
            
        return Inertia::render('HiredFreelancers', [
            'hiredFreelancers' => $freelancers,
        ]);
    }

    public function appliedFreelancers()
    {
        return Inertia::render('AppliedFreelancers');
    }
}
