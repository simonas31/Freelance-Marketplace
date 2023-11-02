<?php

namespace Tests\Feature;

use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProfilesApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_get_all_profiles(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/profiles');

        $response->assertStatus(200);
    }

    public function test_show_profile_profile_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/profiles/0');

        $response->assertStatus(404);
    }

    public function test_show_profile(): void
    {
        $profile = Profile::create([
            'user_id' => '1',
            'country' => 'US',
            'address' => 'Test',
            'iban' => 'LT123123123',
        ]);
        $profile->save();
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/profiles/' . $profile->user_id);

        $response->assertStatus(200);
    }

    public function test_update_profile_fail_validation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/profiles/1', []);

        $response->assertStatus(406);
    }

    public function test_update_profile_profile_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/profiles/0', [
            'country' => 'US',
            'address' => 'Test',
            'iban' => 'LTtesttest'
        ]);

        $response->assertStatus(404);
    }

    public function test_update_profile_with_picture(): void
    {
        $profile = Profile::create([
            'user_id' => '1',
            'country' => 'US',
            'address' => 'Test',
            'iban' => 'LT123123123',
        ]);
        $profile->save();
        $randomImage = UploadedFile::fake()->image('random_image.jpg');
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/profiles/' . $profile->user_id, [
            'country' => 'US',
            'address' => 'Test',
            'iban' => 'LTtesttest',
            'picture' => $randomImage
        ]);

        $response->assertStatus(200);
    }

    public function test_update_profile_with_no_picture(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/profiles/1', [
            'country' => 'US',
            'address' => 'Test',
            'iban' => 'LTtesttest'
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_profile(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('DELETE', '/api/profiles/1');

        $response->assertStatus(200);
    }
}
