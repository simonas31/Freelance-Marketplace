<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show_messages($chat_id)
    {
        return response()->json(Message::where('chat_id', $chat_id)->get());
    }

    /**
     * Store a new resource.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'chat_id' => 'required|integer',
            'text' => 'required|string',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        //return response()->json($request->all());
        Message::create([
            'user_id' => $request->input('user_id'),
            'chat_id' => $request->input('chat_id'),
            'text' => $request->input('text'),
            'send_time' => now()
        ]);

        return response()->json(['Message created successfully']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $message_id)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        if(Message::where('id', $message_id)->first() == null)
            return response()->json(['Could not find message'], 404);

        Message::where('id', $message_id)
            ->update([
                'text' => $request->input('text'),
            ]);

        return response()->json(['updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($message_id)
    {
        if (Message::find($message_id)?->delete()) {
            return response()->json(['deleted successfully']);
        } else {
            return response()->json(['could not delete message'], 400);
        }
    }

    public function hUsersChatsMessages($user_id, $chat_id, $message_id)
    {
        $user = User::find($user_id)?->get();
        $chat = Chat::find($chat_id)?->get();
        $message = Message::find($message_id)?->get();

        if($user == null)
            return response()->json(['Could not find user.'], 404);
        
        if($chat == null)
            return response()->json(['Could not find chat.'], 404);

        if($message == null)
            return response()->json(['Could not find message.'], 404);

        $chat = Chat::where(['user_id' => $user_id, 'id' => $chat_id])?->first();

        return response()->json(Message::where(['chat_id' => $chat->id, 'id' => $message_id])?->first());
    }
}
