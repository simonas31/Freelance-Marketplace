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

        return response()->json(Profile::select('country', 'address', 'iban', 'user_id', 'posted')->get());
    }

    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        $profile = Profile::where('user_id', $user_id)->first();

        if ($profile == null) {
            return response()->json(['message' => 'Profile not found'], 404);
        }

        $profilePic = $profile->profile_picture ?? null;
        if ($profilePic != null && base64_encode(base64_decode($profilePic, true)) !== $profilePic){
            $profilePic = base64_encode($profilePic);
        }
        
        return response()->json([
            'country' => $profile->country,
            'address' => $profile->address,
            'iban' => $profile->iban,
            'profile_picture' => $profilePic
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
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        $profile = Profile::where('user_id', $user_id)->first();

        if ($profile == null)
            return response()->json(['Profile not found'], 404);

        if ($request->hasFile('picture')) {
            $image = file_get_contents($request->file('picture'));

            Profile::where('user_id', $user_id)
                ?->update([
                    'country' => $request->input('country'),
                    'address' => $request->input('address'),
                    'iban' => $request->input('iban'),
                    'profile_picture' => base64_encode($image),
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
    public function destroy($user_id)
    {
        if (Profile::where('user_id', $user_id)?->delete()) {
            return response()->json(['Deleted successfully']);
        }
        return response()->json(['Could not find profile, or delete it.'], 404);
    }
}
