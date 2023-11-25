<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HiredFreelancer;
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
        $newToken = auth()->refresh();
        $user_id = auth()->id();
        $cookie = cookie('jwt', $newToken, 60); // 1 hour

        $data = $request->all();
        $query = Job::with('user');

        $hfs = HiredFreelancer::where([
            ['freelancer_id', '=', $user_id]
        ])->select('job_id')->get()->toArray();
        $temp = [];

        foreach ($hfs as $key => $hf) {
            array_push($temp, $hf['job_id']);
        }

        if ($data == null) {
            return response()->json($query->where([['creation_confirmed', '=', 1], ['finished', '=', 0], ['freelancer_id', '=', -1]])->whereNotIn('id', $temp)->get())->withCookie($cookie);
        }

        if (isset($data['payFrom'])) {
            $query->where('pay_amount', '>=', $data['payFrom']);
        }

        if (isset($data['payTo']) && $data['payTo'] != null) {
            $query->where('pay_amount', '<=', $data['payTo']);
        }

        if (isset($data['selectedWorkFields']) && $data['selectedWorkFields'] != null) {
            $searchValues = $data['selectedWorkFields'];
            $query->where(function ($query) use ($searchValues) {
                foreach ($searchValues as $key => $value) {
                    $query->orWhereRaw("work_fields LIKE ?", ["%$value%"]);
                }
            });
        }

        $results = $query->where('creation_confirmed', '=', 1)->get();

        return response()->json($results)->withCookie($cookie);
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
            'job_title' => 'required|string'
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        Job::create([
            'description' => $request->input('description'),
            'work_fields' => $request->input('work_fields'),
            'pay_amount' => $request->input('pay_amount'),
            'job_title' => $request->input('job_title'),
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
        if ($job == null)
            return response()->json(['Could not find job'], 404);

        return response()->json([$job]);
    }

    // ADD NEW UPDATE FUNCTION SO THAT WHEN USER GETS ASSIGNED TO TO JOB, JOB UPDATES.

    public function updateJob(Request $request, $user_id, $job_id)
    {
        if (User::find($user_id)?->first() == null)
        return response()->json('Could not find user', 404);

        if (empty(Job::where(['id' => $job_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user job', 404);

        Job::where(['id' => $job_id, 'user_id' => $user_id])
            ->update([
                'description' => $request->input('description'),
                'work_fields' => $request->input('work_fields'),
                'pay_amount' => $request->input('pay_amount'),
                'job_title' => $request->input('job_title'),
            ]);

        return response()->json(['updated successfully']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id, $job_id)
    {
        dd('gelp');
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|integer',
        ]);
        if ($validator->fails())
            return response()->json('Check if input data is filled', 406);

        if (User::find($user_id)?->first() == null)
            return response()->json('Could not find user', 404);

        if (empty(Job::where(['id' => $job_id, 'user_id' => $user_id])?->first()))
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
        if (User::find($user_id)?->first() == null) {
            return response()->json('Could not find user', 404);
        }

        $job = Job::where(['id' => $job_id, 'user_id' => $user_id])?->first();
        if (empty($job)) {
            return response()->json('Could not find user job', 404);
        }

        HiredFreelancer::where('client_id', $user_id)?->delete();

        if ($job?->delete()) {
            return response()->json(['deleted successfully']);
        }
    }

    public function UserJob($user_id, $job_id)
    {
        $user = User::find($user_id)?->get();
        $job = Job::find($job_id)?->get();

        if ($user == null)
            return response()->json(['Could not find user.'], 404);

        if ($job == null)
            return response()->json(['Could not find user job.'], 404);

        return response()->json(Job::where(['user_id' => $user_id, 'id' => $job_id])?->first());
    }

    public function listUserJobs($user_id)
    {
        $user = User::find($user_id)?->first();

        if ($user == null)
            return response()->json('User doesnt exist', 404);

        $jobs = Job::where('user_id', $user_id)->get()->toArray();

        if (empty($jobs))
            return response()->json('User does not have any jobs', 404);

        return response()->json($jobs);
    }

    public function confirmCreation($job_id)
    {
        Job::where('id', $job_id)->update(['creation_confirmed' => 1]);

        return response()->json(['message' => 'Job creation confirmed.']);
    }
}
