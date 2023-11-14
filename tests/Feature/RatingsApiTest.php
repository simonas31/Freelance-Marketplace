<?php

namespace Tests\Feature;

use App\Models\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RatingsApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_ratings_index(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/ratings');

        $response->assertStatus(200);
    }

    public function test_store_rating_fail_validation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('POST', '/api/ratings', []);

        $response->assertStatus(406);
    }

    public function test_store_rating(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('POST', '/api/ratings', [
            'client_id' => 1,
            'freelancer_id' => 1,
            'rating' => 1,
        ]);

        $response->assertStatus(200);
    }

    public function test_store_rating_found_rating(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('POST', '/api/ratings', [
            'client_id' => 1,
            'freelancer_id' => 1,
            'rating' => 5,
        ]);

        $response->assertStatus(200);
    }

    public function test_show_rating(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/ratings/1');

        $this->assertEquals(5.0, $response->original, "Rated freelancer rating is not as expected");
        $response->assertStatus(200);
    }

    public function test_update_freelancer_rating_fail_validation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/ratings/1', []);

        $response->assertStatus(406);
    }

    public function test_update_freelancer_rating_rating_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/ratings/0', [
            'rating' => 1,
        ]);

        $response->assertStatus(404);
    }

    public function test_update_freelancer_rating_success(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/ratings/1', [
            'rating' => 1,
        ]);

        $response->assertStatus(200);
    }

    public function test_client_freelancer_rating(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/ratings/1/1');

        $response->assertStatus(200);
    }

    public function test_delete_rating_success(): void
    {
        $rating = Rating::create([
            'client_id' => 1,
            'freelancer_id' => 1,
            'rating' => 1,
        ]);
        $rating->save();
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/ratings/' . $rating->id);

        $response->assertStatus(200);
    }
}