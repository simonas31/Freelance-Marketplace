<?php

namespace App\Http\Controllers\Api;

use App\Events\Message as EventsMessage;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
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
    public function store(Request $request, $user_id, $chat_id)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        if(User::find($user_id)?->get() == null)
            return response()->json('Could not find user', 404);

        if(Chat::find($chat_id)?->get() == null)
            return response()->json('Could not find user chat', 404);
        
        event(new EventsMessage($user_id, $chat_id, $request->input('text')));
        Message::create([
            'sender' => $user_id,
            'chat_id' => $chat_id,
            'text' => $request->input('text'),
            'send_time' => Date::now()
        ]);

        return response()->json(['Message created successfully']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user_id, $chat_id, $message_id)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
        ]);

        if ($validator->fails())
            return response()->json(['Check if input data is filled'], 406);

        if(User::find($user_id)?->get() == null)
            return response()->json('Could not find user', 404);

        if(Chat::find($chat_id)?->get() == null)
            return response()->json('Could not find user chat', 404);
            
        if(Message::where('id', $message_id)?->first() == null)
            return response()->json('Could not find message', 404);

        Message::where('id', $message_id)
            ->update([
                'text' => $request->input('text'),
            ]);

        return response()->json(['updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($user_id, $chat_id, $message_id)
    {
        if(User::find($user_id)?->first() == null)
            return response()->json('Could not find user.', 404);

        if(empty(Chat::where(['id' => $chat_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user chat.', 404);
            
        $message = Message::where([
            'id' => $message_id,
            'sender' => $user_id,
            'chat_id' => $chat_id
        ])?->first();

        if($message == null)
            return response()->json(['Could not find message'], 404);

        if ($message->delete()) {
            return response()->json(['deleted successfully']);
        }
    }

    public function UserChatMessage($user_id, $chat_id, $message_id)
    {
        if(User::find($user_id)?->first() == null)
            return response()->json('Could not find user.', 404);

        if(empty(Chat::where(['id' => $chat_id, 'user_id' => $user_id])?->first()))
            return response()->json('Could not find user chat.', 404);

        $message = Message::where([
            'id' => $message_id,
            'sender' => $user_id,
            'chat_id' => $chat_id
        ])?->first();

        if($message == null)
            return response()->json('Could not find user chat message.', 404);

        return response()->json($message);
    }

    public function listUserChatMessages($user_id, $chat_id){
        if(User::find($user_id)?->first() == null)
            return response()->json('User doesnt exist', 404);
        
        $chat = Chat::find($chat_id)?->first();
        if($chat == null)
            return response()->json('User does not have any chats', 404);

        $messages = Message::where([
            ['chat_id', '=', $chat_id]
        ])->orderBy('send_time', 'asc')->get()->toArray();

        if(count($messages) > 0)
        {
            $response = ['messages' => $messages];
        }else{
            $response = ['chat_id' => $chat->id];
        }
        return response()->json($response);
    }
}
