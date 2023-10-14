<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\User;
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
    public function store(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string',
            'work_fields' => 'required|string',
            'pay_amount' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        Job::create([
            'description' => $request->input('description'),
            'work_fields' => $request->input('work_fields'),
            'pay_amount' => $request->input('pay_amount'),
            'posted_time' => now(),
            'user_id' => $user_id,
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
            return response()->json(['Could not find job'], 404);

        return response()->json([$job]);
    }

    // ADD NEW UPDATE FUNCTION SO THAT WHEN USER GETS ASSIGNED TO TO JOB, JOB UPDATES.

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id, $job_id)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json('Check if input data is filled', 406);

        if(User::find($user_id)?->first() == null)
            return response()->json('Could not find user', 404);

        if(empty(Job::where(['id' => $job_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user job', 404);

        Job::where(['id' => $job_id, 'user_id' => $user_id])
            ->update([
                'transaction_id' => $request->input('transaction_id')
            ]);

        return response()->json(['updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id, $job_id)
    {
        if(User::find($user_id)?->first() == null)
        return response()->json('Could not find user');

        $job = Job::where(['id' => $job_id, 'user_id' => $user_id])?->first();
        if(empty($job))
            return response()->json('Could not find user job');

        if ($job?->delete()) {
            return response()->json(['deleted successfully']);
        } else {
            return response()->json(['could not delete job'], 400);
        }
    }

    public function UserJob($user_id, $job_id)
    {
        $user = User::find($user_id)?->get();
        $job = Job::find($job_id)?->get();

        if($user == null)
            return response()->json(['Could not find user.'], 404);
        
        if($job == null)
            return response()->json(['Could not find user job.'], 404);

        return response()->json(Job::where(['user_id' => $user_id, 'id' => $job_id])?->first());
    }

    public function listUserJobs($user_id){
        $user = User::find($user_id)?->first();

        if($user == null)
            return response()->json('User doesnt exist', 404);

        $chats = Job::where('user_id', $user_id)->get()->toArray();

        if(empty($chats))
            return response()->json('User does not have any jobs', 404);

        return response()->json($chats);
    }
}
