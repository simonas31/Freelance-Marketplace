<?php

namespace Tests\Feature;

use App\Models\Chat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_chats_all(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/chats_index');

        $response->assertStatus(200);
    }

    public function test_store_chat_success(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('POST', '/api/users/1/2/chats', [
            'user_id' => 1,
            'receiver' => 2
        ]);

        $response->assertStatus(200);
    }

    public function test_store_chat_found_chat(): void
    {
        $chat = Chat::create([
            'user_id' => 1,
            'receiver' => 2
        ]);
        $chat->save();
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('POST', '/api/users/1/2/chats');

        $response->assertStatus(200);
    }

    public function test_store_chat_no_user_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('POST', '/api/users/0/2/chats');

        $response->assertStatus(404);
    }

    public function test_show_specific_chat(): void 
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/show_chat/1');

        $response->assertStatus(200);
    }

    public function test_update_chat_success(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/users/1/chats/1');

        $response->assertStatus(200);
    }

    public function test_update_chat_no_user_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/users/0/chats/1');

        $response->assertStatus(404);
    }

    public function test_update_chat_no_chat_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/users/1/chats/0');

        $response->assertStatus(404);
    }

    public function test_find_user_chat(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/chats/1');

        $response->assertStatus(200);
    }

    public function test_find_user_chat_no_user_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/0/chats/1');

        $response->assertStatus(404);
    }

    public function test_find_user_chat_no_chat_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/chats/0');

        $response->assertStatus(404);
    }

    public function test_list_user_chats(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/chats');

        $response->assertStatus(200);
    }

    public function test_list_user_chats_no_user_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/0/chats');

        $response->assertStatus(404);
    }

    public function test_list_user_chats_no_chats_found(): void
    {
        Chat::where('user_id', 1)->delete();
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/chats');

        $response->assertStatus(404);
    }

    public function test_delete_chat_no_user_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/0/chats/1');

        $response->assertStatus(404);
    }

    public function test_delete_chat_no_chat_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/1/chats/0');

        $response->assertStatus(400);
    }

    public function test_delete_chat_success(): void
    {
        $chat = Chat::create([
            'user_id' => 1,
            'receiver' => 2
        ]);
        $chat->save();
        $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/users/1/chats/' . $chat->id);
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/1/chats/' . $chat->id);

        $response->assertStatus(200);
    }
}
