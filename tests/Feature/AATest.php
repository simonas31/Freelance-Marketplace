<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AATest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_initialize_jwt_token_and_other_variables(): void
    {
        $response = $this->get('/');
        $user = User::create([
            'username' => 'asd1',
            'name' => 'test1',
            'surname' => 'test1',
            'password' => Hash::make('asdasd1'),
            'confirmed_registration' => 0,
            'rating' => null,
            'role' => 1
        ]);
        $user->save();
        $user = User::create([
            'username' => 'asd',
            'name' => 'asd',
            'surname' => 'asd',
            'password' => Hash::make('asdasd1'),
            'confirmed_registration' => 1,
            'role' => 2
        ]);
        $user->save();
        $token = JWTAuth::fromUser($user);
        define('JWT_TOKEN', $token);
        $response->assertStatus(200);
    }
}
