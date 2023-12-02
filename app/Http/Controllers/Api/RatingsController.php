<?php

namespace App\Http\Controllers\Api;

use App\Models\Rating;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RatingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return response()->json(Rating::all()->toArray());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|integer',
            'freelancer_id' => 'required|integer',
            'rating' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        $found = Rating::where([
            'client_id' => $request->input('client_id'),
            'freelancer_id' => $request->input('freelancer_id'),
        ])->first();

        if($found != null)
        {
            $found->update(['rating' => $request->input('rating')]);
            $found->save();
            return response()->json(['Rating updated successfuly.']);
        }

        Rating::create([
            'client_id' => $request->input('client_id'),
            'freelancer_id' => $request->input('freelancer_id'),
            'rating' => $request->input('rating')
        ]);

        return response()->json(['Rating created successfuly.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $freelancer_id)
    {
        //
        $avg_rating = Rating::where('freelancer_id', $freelancer_id)?->avg('rating');

        return response()->json(round($avg_rating ?? 0, 1));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $freelancer_id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        $rating = Rating::where('freelancer_id', $freelancer_id)->first();

        if ($rating == null)
            return response()->json(['Rating not found'], 404);

        Rating::where('freelancer_id', $freelancer_id)
            ?->update([
                'rating' => $request->input('rating')
            ]);

        return response()->json(['Freelancer rating updated successfuly.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($rating_id)
    {
        if (Rating::find($rating_id)?->delete()) {
            return response()->json(['Deleted successfully']);
        }
        return response()->json(['Could not find rating'], 404);
    }

    public function clientFreelancerRating(Request $request, $freelancer_id, $client_id)
    {
        $rating = Rating::where([
            'client_id' => $client_id,
            'freelancer_id' => $freelancer_id,
        ])->first()?->rating;

        return response()->json($rating ?? 0);
    }
}
