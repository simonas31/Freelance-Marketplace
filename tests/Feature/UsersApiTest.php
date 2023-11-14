<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_login_success(): void
    {
        //create user
        $response = $this->post('/api/login', ['username' => 'asd', 'password' => 'asdasd1']);

        $response->assertStatus(200);
    }

    public function test_user_login_fail(): void
    {
        $username = $this->faker()->unique()->userName;
        $password = $this->faker()->unique()->password;
        $response = $this->post('/api/login', ['username' => $username, 'password' => $password]);
        
        $response->assertStatus(401);
    }

    public function test_user_login_fail_with_wrong_credentials(): void
    {
        $response = $this->post('/api/login', ['username' => 'asd1', 'password' => 'asdasd1']);

        $response->assertStatus(401);
    }

    public function test_register_success_with_image(): void
    {
        $randomImage = UploadedFile::fake()->image('random_image.jpg');
        $response = $this->post('/api/users', [
            'name' => $this->faker()->name,
            'surname' => $this->faker()->lastName,
            'username' => $this->faker()->unique()->userName,
            'password' => $this->faker()->password,
            'picture' => $randomImage,
            'role' => 1
        ]);

        $response->assertStatus(200);
    }

    public function test_register_success_with_no_image(): void
    {
        $response = $this->post('/api/users', [
            'name' => $this->faker()->name,
            'surname' => $this->faker()->lastName,
            'username' => $this->faker()->unique()->userName,
            'password' => $this->faker()->password,
            'role' => 2
        ]);

        $response->assertStatus(200);
    }

    public function test_register_no_input(): void
    {
        $response = $this->post('/api/users');

        $response->assertStatus(406);
    }

    public function test_register_username_exists(): void
    {
        $username = User::find(1)->first()->username;
        $response = $this->post('/api/users', [
            'name' => $this->faker()->name,
            'surname' => $this->faker()->lastName,
            'username' => $username,
            'password' => 'testtest',
            'role' => 1
        ]);

        $response->assertStatus(400);
    }

    public function test_get_specific_user(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1');

        $response->assertStatus(200)->assertJson(
            ["id" => 1,
            "username" => "asd1",
            "password" => '$2y$04$Ju6LMVIpC2osscZJ4nXOCuOTAvEvwpvAIsqB9OGojYtuVjwwpHmZ6',
            "name" => "test1",
            "surname" => "test1",
            "role" => 1,
            "rating" => null,
            "confirmed_registration" => 0]
        );
    }

    public function test_get_specific_user_fail(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/0');

        $response->assertStatus(404);
    }

    public function test_auth_user(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/auth');

        $response->assertStatus(200);
    }

    public function test_get_freelancers_with_additional_input(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)
                ->json('GET', '/api/freelancers', [
                    'selectedWorkFields' => ['IT' => 'IT'],
                    'selectedExperience' => 4
                ]);

        $response->assertStatus(200);
    }

    public function test_get_freelancers_no_additional_input(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/freelancers');

        $response->assertStatus(200);
    }


    public function test_update_user_rating(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/users/1');

        $response->assertStatus(200);
    }

    public function test_update_user_rating_fail(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/users/0');

        $response->assertStatus(400);
    }

    public function test_list_users(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users');

        $response->assertStatus(200);
    }

    public function test_delete_user(): void
    {
        $user = User::create([
            'username' => 'asd12',
            'name' => 'test1',
            'surname' => 'test1',
            'password' => Hash::make('asdasd1'),
            'confirmed_registration' => 0,
            'role' => 1
        ]);
        $user->save();
        $chat = Chat::create([
            'user_id' => $user->id,
            'receiver' => $user->id,
        ]);
        $chat->save();
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/'.$user->id);

        $response->assertStatus(200);
    }

    public function test_delete_user_fail(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/0');

        $response->assertStatus(400);
    }

    public function test_confirm_user(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PATCH', '/api/users/1');

        $response->assertStatus(200);
    }

    public function test_logout_user(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/logout');

        $response->assertStatus(200);
    }

    public function test_refresh_user_token(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/refresh');

        $response->assertStatus(200);
    }
}
