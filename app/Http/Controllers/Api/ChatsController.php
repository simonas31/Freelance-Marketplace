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
    public function store(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'receiver' => 'required|integer',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        $user = User::find($user_id)?->get();

        if($user == null)
            return response()->json('Could not find user', 404);

        Chat::create([
            'user_id' => $user_id,
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
    public function update(Request $request, $user_id, $chat_id)
    {
        $user = User::find($user_id)?->get();

        if($user == null)
            return response()->json('Could not find user', 404);

        $chat = Chat::find($chat_id)?->get();

        if($chat == null)
            return response()->json('Could not find user chat', 404);

        Chat::where(['user_id' => $user_id, 'id' => $chat_id])
            ->update([
                'deleted' => 1
            ]);

        return response()->json(['updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id, $chat_id)
    {
        $user = User::find($user_id)?->get();

        if($user == null)
            return response()->json('could not find user', 404);

        $chat = Chat::find($chat_id)?->first();

        if ($chat != null && $chat->deleted && $chat->delete()) {
            return response()->json(['deleted successfully']);
        } else {
            return response()->json(['could not delete chat'], 400);
        }
    }

    public function UserChat($user_id, $chat_id)
    {
        $user = User::find($user_id)?->get();
        $chat = Chat::find($chat_id)?->get();

        if($user == null)
            return response()->json(['Could not find user.'], 404);
        
        if($chat == null)
            return response()->json(['Could not find chat.'], 404);

        return response()->json(Chat::where(['user_id' => $user_id, 'id' => $chat_id])?->first());
    }

    public function listUserChats($user_id){
        $user = User::find($user_id)?->first();

        if($user == null)
            return response()->json('User doesnt exist', 404);

        $chats = Chat::where('user_id', $user_id)->get()->toArray();

        if(empty($chats))
            return response()->json('User does not have any chats', 404);

        return response()->json($chats);
    }
}
