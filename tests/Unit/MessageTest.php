<?php

namespace Tests\Unit;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_message_has_one_chat(): void
    {
        $user = User::factory()->create();
        $chat = Chat::factory()->create(['user_id' => $user->id]);
        $message = Message::factory()->create(['sender' => $user->id]);

        $retrievedChat = $message->chat;

        $this->assertInstanceOf(Chat::class, $retrievedChat);
        $this->assertEquals($user->id, $retrievedChat->user_id);
    }

    public function test_message_has_one_user(): void
    {
        $user = User::factory()->create();
        $message = Message::factory()->create(['sender' => $user->id]);
        
        $retrievedUser = $message->user;

        $this->assertInstanceOf(User::class, $retrievedUser);
        $this->assertEquals($user->id, $retrievedUser->id);
    }
}
