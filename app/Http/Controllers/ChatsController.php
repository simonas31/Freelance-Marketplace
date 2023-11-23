<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatsController extends Controller
{
    public function userChats()
    {
        $user = auth()->user();
        $user_id = $user->id;
        $role = $user->role;
        if($role == 1){
            $chats = Chat::where('user_id', $user_id)
                ->with('freelancer_receiver.profile')
                ->get()
                ->toArray();
        }else if ($role == 2){
            $chats = Chat::where('receiver', $user_id)
                ->with('client_receiver.profile')
                ->get()
                ->toArray();
        }

        return Inertia::render('UserChats', [
            'chats' => $chats
        ]);
    }
}
