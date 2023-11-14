<?php

namespace App\Http\Controllers;

use App\Models\HiredFreelancer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HiredFreelancersController extends Controller
{
    public function userHiredFreelancers(){
        $userID = auth()->user()?->id;
        $hiredFreelancers = HiredFreelancer::where('client_id', $userID)->get()->toArray();
        return Inertia::render('HiredFreelancers', [
            'hiredFreelancers' => $hiredFreelancers
        ]);
    }
}
