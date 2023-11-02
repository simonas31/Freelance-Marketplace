<?php

namespace Tests\Unit;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_chat_belongs_to_client(): void
    {
        $chat = Chat::factory()->create();
        $clientUser = User::factory()->create(['id' => $chat->user_id]);

        $retrievedUser = $chat->client_receiver;

        $this->assertInstanceOf(User::class, $retrievedUser);

        $this->assertEquals($clientUser->id, $retrievedUser->id);
    }

    public function test_chat_belongs_to_freelancer(): void
    {
        $chat = Chat::factory()->create();
        $receiverUser = User::factory()->create(['id' => $chat->receiver]);

        $retrievedUser = $chat->freelancer_receiver;

        $this->assertInstanceOf(User::class, $retrievedUser);

        $this->assertEquals($receiverUser->id, $retrievedUser->id);
    }
}
