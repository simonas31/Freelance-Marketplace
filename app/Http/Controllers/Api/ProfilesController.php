<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Profile::all();
    }

    /**
     * Store a new resource.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'sender' => 'required',
            'receiver' => 'required',
            'job_id' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        Profile::create([
            'amount' => $request->input('amount'),
            'sender' => $request->input('sender'),
            'receiver' => $request->input('receiver'),
            'job_id' => $request->input('job_id')
        ]);

        return response()->json(['Profile created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        $profile = Profile::where('user_id', $user_id)->first();

        if(!$profile){
            return response()->json(['message' => 'Profile not found'], 404);
        }

        return response()->json([
            'country' => $profile->country,
            'address' => $profile->address,
            'iban' => $profile->iban,
            'profile_picture' => base64_encode($profile->profile_picture)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required|string',
            'address' => 'required|string',
            'iban' => 'required|string',
            //'picture' => 'required'
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        $profile = Profile::where('user_id', $user_id)->first();

        if($profile == null)
            return response()->json(['Profile not found'], 404);

        if ($request->hasFile('picture')) {
            $image = file_get_contents($request->file('picture'));

            Profile::where('user_id', $user_id)
            ?->update([
                'country' => $request->input('country'),
                'address' => $request->input('address'),
                'iban' => $request->input('iban'),
                'profile_picture' => $image,
                'posted' => 1
            ]);
        } else {
            Profile::where('user_id', $user_id)
            ?->update([
                'country' => $request->input('country'),
                'address' => $request->input('address'),
                'iban' => $request->input('iban'),
                'posted' => 1
            ]);
        }

        return response()->json(['Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($profile_id)
    {
        if (Profile::find($profile_id)?->delete()) {
            return response()->json(['Deleted successfully']);
        } else {
            return response()->json(['Could not delete profile'], 400);
        }
    }
}