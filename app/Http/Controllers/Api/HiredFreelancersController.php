<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HiredFreelancer;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HiredFreelancersController extends Controller
{
    /**
     * Display a listing of the resource. For admin.
     */
    public function listUserJobHireFreelancers($user_id, $job_id)
    {
        if(User::find($user_id)?->first() == null)
            return response()->json('Could not find user', 404);

        if(empty(Job::where(['id' => $job_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user job', 404);
        
        $hfs = HiredFreelancer::where(['client_id' => $user_id, 'job_id' => $job_id])?->get()->toArray();
        if(empty($hfs))
            return response()->json('Could not find user job hired freelancers', 404);
        
        return response()->json($hfs);
    }

    /**
     * Store a new resource.
     */
    public function store(Request $request, $user_id, $job_id)
    {
        $validator = Validator::make($request->all(), [
            'freelancer_id' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        if(User::find($user_id)?->first() == null)
            return response()->json('Could not find user', 404);

        if(empty(Job::where(['id' => $job_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user job', 404);

        HiredFreelancer::create([
            'freelancer_id' => $request->input('freelancer_id'),
            'client_id' => $user_id,
            'job_id' => $job_id,
            'hire_date' => now()
        ]);

        return response()->json(['hired freelancer created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show($hired_freelancer_id)
    {
        //
        $hf = HiredFreelancer::where('id', $hired_freelancer_id)->first();

        if($hf == null)
            return response()->json(['could not find hired freelancer.']);
        
        return response()->json($hf);
    }

    // ADD NEW UPDATE FUNCTION SO THAT WHEN USER GETS ASSIGNED TO TO JOB, JOB UPDATES.

    /**
     * Update the specified resource in storage.
     */
    public function update($user_id, $job_id, $hired_freelancer_id)
    {
        if(User::find($user_id)?->first() == null)
            return response()->json('Could not find user', 404);

        if(empty(Job::where(['id' => $job_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user job', 404);
        
        $hf = HiredFreelancer::where(['job_id' => $job_id, 'id' => $hired_freelancer_id, 'client_id' => $user_id])?->first();
        if(empty($hf))
            return response()->json('Could not find user job hired freelancer.', 404);

        $hf->update([
            'hire_date' => now()
        ]);

        return response()->json(['updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id, $job_id, $hired_freelancer_id)
    {
        if(User::find($user_id)?->first() == null)
            return response()->json('Could not find user', 404);

        if(empty(Job::where(['id' => $job_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user job', 404);
        
        $hf = HiredFreelancer::where(['job_id' => $job_id, 'id' => $hired_freelancer_id, 'client_id' => $user_id])?->first();
        if(empty($hf))
            return response()->json('Could not find user job hired freelancer.', 404);
        
        if ($hf?->delete()) {
            return response()->json(['deleted successfully']);
        } else {
            return response()->json(['could not delete hired freelancer'], 400);
        }
    }

    public function UserJobHiredFreelancer($user_id, $job_id, $hired_freelancer_id)
    {
        if(User::find($user_id)?->first() == null)
            return response()->json('Could not find user', 404);

        if(empty(Job::where(['id' => $job_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user job', 404);

        $hf = HiredFreelancer::where(['job_id' => $job_id, 'id' => $hired_freelancer_id, 'client_id' => $user_id])?->first();
        if(empty($hf))
            return response()->json('Could not find user job hired freelancer.', 404);

        return response()->json($hf);
    }
}
