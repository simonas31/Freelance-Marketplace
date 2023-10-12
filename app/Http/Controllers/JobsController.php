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
        $job = Job::with('user')->first();
        return Inertia::render('Job', [
            'job' => $job
        ]);
    }
}
