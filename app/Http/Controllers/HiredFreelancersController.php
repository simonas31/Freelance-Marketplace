<?php

namespace App\Http\Controllers;

use App\Models\HiredFreelancer;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HiredFreelancersController extends Controller
{
    public function userHiredFreelancers(){
        $freelancers = HiredFreelancer::with(['freelancer.profile', 'freelancer.portfolio', 'freelancer.ratings', 'freelancer.freelancerJob'])
            ->leftJoin('jobs', 'hired_freelancers.job_id', '=', 'jobs.id')
            ->select(
                'hired_freelancers.*',
                'jobs.id as job_id',
                'jobs.job_title as job_title',
                'jobs.pay_amount as pay_amount',
                'jobs.transaction_id as transaction_id')    
            ->where('confirmed', '=', 1)
            ->get()
            ->toArray();

        foreach ($freelancers as &$freelancer) {
            if (count($freelancer['freelancer']['ratings']) > 0) {
                $averageRating = $this->avgRating($freelancer['freelancer']['ratings']);
                $freelancer['freelancer']['rating'] = $averageRating;
            } else {
                $freelancer['freelancer']['rating'] = null;
            }
        }

        return Inertia::render('HiredFreelancers', [
            'hiredFreelancers' => $freelancers,
        ]);
    }

    public function appliedFreelancers()
    {
        return Inertia::render('AppliedFreelancers');
    }

    public function avgRating($ratings)
    {
        $sum = 0;
        foreach ($ratings as $rating){
            $sum += $rating['rating'];
        }
        return $sum / count($ratings);
    }
}
