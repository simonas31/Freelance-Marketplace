<?php

namespace Tests\Unit;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_user_has_many_chats(): void
    {
        $user = User::factory()->create();
        $chat = Chat::factory(3)->create(['user_id' => $user->id]);
        
        $this->assertCount(3, $user->chats);
    }
}
