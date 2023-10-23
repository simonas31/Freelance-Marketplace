<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobsController extends Controller
{
    public function index(){
        return Inertia::render('Jobs');
    }

    public function find($id){
        $job = Job::with('user')->where('id', '=', $id)->first();
        return Inertia::render('Job', [
            'job' => $job
        ]);
    }

    public function create(){
        $user_id = auth()->id();

        return Inertia::render('CreateJob', [
            'user_id' => $user_id
        ]);
    }

    public function userJobs(){
        $user_id = auth()->id();
        $jobs = Job::where('user_id', '=', $user_id)->get()->toArray();
        return Inertia::render('ClientJobs', [
            'jobs' => $jobs
        ]);
    }
}
