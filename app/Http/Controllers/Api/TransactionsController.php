<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource. For admin.
     */
    public function index()
    {
        return response()->json(Transaction::all()->toArray());
    }

    /**
     * Store a new resource.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'user_id' => 'required',
            'receiver' => 'required',
            'job_id' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        Transaction::create([
            'tax' => 2,
            'amount' => $request->input('amount'),
            'user_id' => $request->input('user_id'),
            'receiver' => $request->input('receiver'),
            'job_id' => $request->input('job_id')
        ]);

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
    public function update(Request $request, $transaction_id)
    {
        Transaction::where('id', $transaction_id)
            ->update([
                'completed' => 1
            ]);

        return response()->json(['updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($transaction_id)
    {
        if (Transaction::find($transaction_id)?->delete()) {
            return response()->json(['deleted successfully']);
        } else {
            return response()->json(['could not delete transaction'], 404);
        }
    }
}
