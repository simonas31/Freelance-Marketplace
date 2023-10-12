<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PortfoliosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //find all freelancers name, surname, country, work fields, experience, rating.
        return response()->json([Portfolio::all()]);
    }

    /**
     * Store a new resource.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'resume' => 'required|string',
            'work_fields' => 'required|string',
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        Portfolio::create([
            'resume' => $request->input('resume'),
            'work_fields' => $request->input('work_fields'),
            'user_id' => $request->input('user_id'),
            'posted_time' => now()
        ]);

        return response()->json(['Portfolio created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        //
        $profile = Portfolio::where('user_id', $user_id)->first();
        if($profile == null)
            return response()->json(['Could not find portfolio with that user.']);
        return response()->json($profile);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'resume' => 'required|string',
            'selectedWorkFields' => 'required|array',
            'selectedExperience' => 'required|int'
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        if( Portfolio::where('user_id', $user_id)->first() == null)
            return response()->json(['Could not find portfolio.']);

        Portfolio::where('user_id', $user_id)
            ->update([
                'resume' => $request->input('resume'),
                'work_fields' => $request->input('selectedWorkFields'),
                'work_experience' => $request->input('selectedExperience'),
                'posted' => 1
            ]);

        return response()->json(['Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
        if (Portfolio::where('user_id', $user_id)?->delete()) {
            return response()->json(['Deleted successfully']);
        } else {
            return response()->json(['Could not delete portfolio'], 400);
        }
    }
}
