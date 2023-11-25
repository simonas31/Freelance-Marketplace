<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource. For admin.
     */
    public function listUserJobTransactions($user_id, $job_id)
    {
        if(User::find($user_id)?->first() == null)
            return response()->json('Could not find user', 404);

        if(empty(Job::where(['id' => $job_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user job', 404);

        $txs = Transaction::where(['user_id' => $user_id, 'job_id' => $job_id])?->get()->toArray();
        
        return response()->json($txs);
    }

    /**
     * Store a new resource.
     */
    public function store(Request $request, $user_id, $job_id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'receiver' => 'required',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        $receiver = $request->input('receiver');

        if(User::find($user_id)?->first() == null)
            return response()->json('Could not find user', 404);

        if(empty(Job::where(['id' => $job_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user job', 404);

        $transaction = Transaction::create([
            'tax' => 2,
            'amount' => $request->input('amount'),
            'user_id' => $user_id,
            'receiver' => $receiver,
            'job_id' => $job_id
        ]);

        $job = Job::where(['id' => $job_id])->first();
        $job->transaction_id = $transaction->id;
        $job->finished = 1;
        $job->save();

        return response()->json(['Transaction created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show($transaction_id)
    {
        //
        $transaction = Transaction::where('id', $transaction_id)->first();
        return response()->json([$transaction]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($user_id, $job_id, $transaction_id)
    {       
        if(User::find($user_id)?->first() == null)
            return response()->json('Could not find user', 404);

        if(empty(Job::where(['id' => $job_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user job', 404);

        if(empty(Transaction::where(['id' => $transaction_id, 'user_id' => $user_id, 'job_id' => $job_id])?->first()))
            return response()->json('Could not find user job transaction', 404);
        
        Transaction::where('id', $transaction_id)
            ->update([
                'completed' => 1
            ]);

        return response()->json(['updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id, $job_id, $transaction_id)
    {
        if(User::find($user_id)?->first() == null)
            return response()->json('Could not find user', 404);

        if(empty(Job::where(['id' => $job_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user job', 404);

        $tx = Transaction::where(['id' => $transaction_id, 'user_id' => $user_id, 'job_id' => $job_id])?->first();
        if(empty($tx))
            return response()->json('Could not find user job transaction', 404);
        
        if ($tx?->delete()) {
            return response()->json(['deleted successfully']);
        }
    }

    public function UserJobTransaction($user_id, $job_id, $transaction_id)
    {
        $user = User::find($user_id)?->get();
        $job = Job::find($job_id)?->get();
        $tx = Transaction::find($transaction_id)?->get();

        if($user == null)
            return response()->json(['Could not find user.'], 404);
        
        if($job == null)
            return response()->json(['Could not find user job.'], 404);

        if($tx == null)
            return response()->json(['Could not find user job transaction.'], 404);

        $job = Job::where(['user_id' => $user_id, 'id' => $job_id])?->first();

        return response()->json(Transaction::where(['job_id' => $job->id, 'id' => $transaction_id])?->first());
    }
}
