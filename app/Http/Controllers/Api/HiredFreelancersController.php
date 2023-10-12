<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HiredFreelancer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HiredFreelancersController extends Controller
{
    /**
     * Display a listing of the resource. For admin.
     */
    public function index()
    {
        return response()->json(HiredFreelancer::all());
    }

    /**
     * Store a new resource.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'freelancer_id' => 'required|integer',
            'client_id' => 'required|integer',
            'job_id' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        HiredFreelancer::create([
            'freelancer_id' => $request->input('freelancer_id'),
            'client_id' => $request->input('client_id'),
            'job_id' => $request->input('job_id'),
            'hire_date' => now()
        ]);

        return response()->json(['entity created successfully']);
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
    public function update(Request $request, $hired_freelancer_id)
    {
        if(HiredFreelancer::where('id', $hired_freelancer_id)->first() == null)
            return response()->json(['Could not find hired freelancer']);

        HiredFreelancer::where('id', $hired_freelancer_id)
            ->update([
                'hire_date' => now()
            ]);

        return response()->json(['updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($hired_freelancer_id)
    {
        if (HiredFreelancer::find($hired_freelancer_id)?->delete()) {
            return response()->json(['deleted successfully']);
        } else {
            return response()->json(['could not delete hired freelancer'], 400);
        }
    }
}
