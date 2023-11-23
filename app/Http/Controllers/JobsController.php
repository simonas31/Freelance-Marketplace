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

    public function confirmJobs()
    {
        $jobs = Job::with(['user' => function ($query) {
            $query->select('id', 'name', 'surname');
        }])->where('creation_confirmed', 0)->get()->toArray();
        
        return Inertia::render('ConfirmJobs', [
            'jobs' => $jobs
        ]);
    }

    public function editJob($id)
    {
        $job = Job::find($id)->first();
        return Inertia::render('EditJob', [
            'job' => $job
        ]);
    }
}
