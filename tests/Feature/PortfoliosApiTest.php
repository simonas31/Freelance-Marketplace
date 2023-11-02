<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PortfoliosApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_show_all_proftolios(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/portfolios');

        $response->assertStatus(200);
    }

    public function test_store_portfolio_fail_validation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/portfolios', []);

        $response->assertStatus(406);
    }

    public function test_store_portfolio_success(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/portfolios', [
            'resume' => $this->faker()->text,
            'work_fields' => $this->faker()->text,
            'user_id' => 1
        ]);

        $response->assertStatus(200);
    }

    public function test_show_specific_portfolio_portfolio_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/portfolios/0');

        $response->assertStatus(404);
    }

    public function test_show_specific_portfolio(): void
    {
        $porfolio = Portfolio::all()->first();
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/portfolios/' . $porfolio->user_id);

        $response->assertStatus(200);
    }

    public function test_update_portfolio_fail_validation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/portfolios/0', []);

        $response->assertStatus(406);
    }

    public function test_update_portfolio_portfolio_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/portfolios/0', [
            'resume' => $this->faker()->text,
            'selectedWorkFields' => ['IT' => 'IT'],
            'selectedExperience' => 1
        ]);

        $response->assertStatus(404);
    }

    public function test_update_portfolio_success(): void
    {
        $porfolio = Portfolio::all()->first();
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/portfolios/' . $porfolio->user_id, [
            'resume' => $this->faker()->text,
            'selectedWorkFields' => ['IT' => 'IT'],
            'selectedExperience' => 1
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_portfolio_success(): void
    {
        $porfolio = Portfolio::all()->first();
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/portfolios/'. $porfolio->user_id);

        $response->assertStatus(200);
    }
}
