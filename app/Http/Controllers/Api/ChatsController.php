<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatsController extends Controller
{
    /**
     * Display a listing of the resource. For admin.
     */
    public function index()
    {
        return response()->json(Chat::all()->toArray());
    }

    /**
     * Store a new resource.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'receiver' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        Chat::create([
            'user_id' => $request->input('user_id'),
            'receiver' => $request->input('receiver'),
        ]);

        return response()->json(['chat created successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show($chat_id)
    {
        //
        $chat = Chat::where('id', $chat_id)->first();
        return response()->json([$chat]);
    }

    // ADD NEW UPDATE FUNCTION SO THAT WHEN USER GETS ASSIGNED TO TO JOB, JOB UPDATES.

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $chat_id)
    {
        Chat::where('id', $chat_id)
            ->update([
                'deleted' => 1
            ]);

        return response()->json(['updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($chat_id)
    {
        $chat = Chat::find($chat_id)?->first();
        if ($chat != null && $chat->deleted && $chat->delete()) {
            return response()->json(['deleted successfully']);
        } else {
            return response()->json(['could not delete chat'], 400);
        }
    }
}
