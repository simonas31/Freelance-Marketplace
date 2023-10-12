<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $query = Job::with(['user' => function ($query) {
            $query->select('id', 'name', 'surname');
        }]);
        if($data == null){
            return $query->get();
        }
        
        if(isset($data['payFrom'])){
            $query->where('pay_amount', '>=', $data['payFrom']);
        }

        if(isset($data['payTo']) && $data['payTo'] != null){
            $query->where('pay_amount', '<=', $data['payTo']);
        }

        if(isset($data['selectedWorkFields']) && $data['selectedWorkFields'] != null){
            $searchValues = $data['selectedWorkFields'];
            $query->where(function ($query) use ($searchValues) {
                foreach ($searchValues as $key => $value) {
                    $query->orWhereRaw("work_fields LIKE ?", ["%$value%"]);
                }
            });
        }

        $results = $query->get();

        return response()->json($results);
    }

    /**
     * Store a new resource.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'work_fields' => 'required|string',
            'pay_amount' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        Job::create([
            'description' => $request->input('description'),
            'work_fields' => $request->input('work_fields'),
            'pay_amount' => $request->input('pay_amount'),
            'posted_time' => now(),
            'user_id' => $request->input('user_id'),
        ]);

        return response()->json(['Job created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        //
        $job = Job::where('user_id', $user_id)->first();
        if($job == null)
            return response()->json(['Could not find job']);

        return response()->json([$job]);
    }

    // ADD NEW UPDATE FUNCTION SO THAT WHEN USER GETS ASSIGNED TO TO JOB, JOB UPDATES.

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $job_id)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        if(Job::where('id', $job_id)->first() == null)
            return response()->json(['Could not find job']);

        Job::where('id', $job_id)
            ->update([
                'transaction_id' => $request->input('transaction_id')
            ]);

        return response()->json(['updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($job_id)
    {
        if (Job::find($job_id)?->delete()) {
            return response()->json(['deleted successfully']);
        } else {
            return response()->json(['could not delete job'], 400);
        }
    }
}
