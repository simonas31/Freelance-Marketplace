<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessagesApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_show_messages(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/show_messages/0');

        $response->assertStatus(200);
    }

    public function test_store_message_fail_validation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/1/chats/1/messages', []);

        $response->assertStatus(406);
    }

    public function test_store_messages_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/100/chats/1/messages', [
            'text' => $this->faker->paragraph,
        ]);

        $response->assertStatus(404);
    }

    public function test_store_messages_chat_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/1/chats/0/messages', [
            'text' => $this->faker->paragraph,
        ]);

        $response->assertStatus(404);
    }

    public function test_store_messages_chat_success(): void
    {
        $chat = Chat::create([
            'user_id' => 1,
            'receiver' => 2,
        ]);
        $chat->save();
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/1/chats/' . $chat->id . '/messages', [
            'text' => $this->faker->paragraph,
        ]);

        $response->assertStatus(200);
    }

    public function test_update_message_fail_validation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/1/chats/1/messages/1', []);

        $response->assertStatus(406);
    }

    public function test_update_messages_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/100/chats/1/messages/1', [
            'text' => $this->faker->paragraph,
        ]);

        $response->assertStatus(404);
    }

    public function test_update_messages_chat_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/1/chats/0/messages/1', [
            'text' => $this->faker->paragraph,
        ]);

        $response->assertStatus(404);
    }

    public function test_update_messages_messages_not_found(): void
    {
        $chat = Chat::all()->first();
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/1/chats/' . $chat->id . '/messages/0', [
            'text' => $this->faker->paragraph,
        ]);

        $response->assertStatus(404);
    }

    public function test_update_messages_success(): void
    {
        $chat = Chat::all()->first();
        $message = Message::where('chat_id', $chat->id)->first();
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/1/chats/' . $chat->id . '/messages/' . $message->id, [
            'text' => $this->faker->paragraph,
        ]);

        $response->assertStatus(200);
    }

    public function test_get_user_chat_message_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/0/chats/1/messages/1');

        $response->assertStatus(404);
    }

    public function test_get_user_chat_message_chat_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/chats/0/messages/1');

        $response->assertStatus(404);
    }

    public function test_get_user_chat_message_message_not_found(): void
    {
        $chat = Chat::all()->first();
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/chats/'. $chat->id. '/messages/0');

        $response->assertStatus(404);
    }

    public function test_get_user_chat_message_success(): void
    {
        $chat = Chat::all()->first();
        $message = Message::where('chat_id', $chat->id)->first();
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/chats/'. $chat->id. '/messages/'. $message->id);

        $response->assertStatus(200);
    }

    public function test_list_user_chat_messages_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/0/chats/1/messages');

        $response->assertStatus(404);
    }

    public function test_list_user_chat_messages_chat_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/chats/0/messages');

        $response->assertStatus(404);
    }

    public function test_list_user_chat_messages_success(): void
    {
        $chat = Chat::all()->first();
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/chats/'. $chat->id. '/messages');

        $response->assertStatus(200);
    }

    public function test_list_user_chat_messages_messages_not_found(): void
    {
        $chat = Chat::all()->first();
        Message::truncate();
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/chats/'. $chat->id. '/messages');

        $response->assertStatus(200);
    }


    public function test_delete_user_chat_messages_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/0/chats/1/messages/1');

        $response->assertStatus(404);
    }

    public function test_delete_user_chat_messages_chat_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/1/chats/0/messages/1');

        $response->assertStatus(404);
    }

    public function test_delete_user_chat_messages_messages_not_found(): void
    {
        $chat = Chat::all()->first();
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/1/chats/'. $chat->id. '/messages/0');

        $response->assertStatus(404);
    }

    public function test_delete_user_chat_message_success(): void
    {
        $chat = Chat::all()->first();
        $message = Message::create([
            'sender' => 1,
            'chat_id' => $chat->id,
            'text' => 'test',
            'send_time' => now()
        ]);
        $message->save();
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/1/chats/'. $chat->id. '/messages/'. $message->id);

        $response->assertStatus(200);
    }
}
