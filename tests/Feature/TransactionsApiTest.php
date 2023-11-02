<?php

namespace Tests\Feature;

use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionsApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_store_user_job_transaction_fail_validation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/1/jobs/1/transactions', []);

        $response->assertStatus(406);
    }

    public function test_store_user_job_transaction_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/0/jobs/1/transactions', [
            'amount' => 1000,
            'receiver' => 1
        ]);

        $response->assertStatus(404);
    }

    public function test_store_user_job_transaction_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/1/jobs/0/transactions', [
            'amount' => 1000,
            'receiver' => 1
        ]);

        $response->assertStatus(404);
    }

    public function test_store_user_job_transaction_success(): void
    {
        $job = Job::create([
            'description' => $this->faker()->text(),
            'work_fields' => $this->faker()->text(),
            'job_title' => $this->faker()->text(100),
            'pay_amount' => $this->faker()->numberBetween(100, 10000),
            'posted_time' => now(),
            'user_id' => 1,
        ]);
        $job->save();
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/1/jobs/' . $job->id . '/transactions', [
            'amount' => 1000,
            'receiver' => 1
        ]);

        $response->assertStatus(200);
    }

    public function test_list_user_job_transactions_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/0/jobs/1/transactions');

        $response->assertStatus(404);
    }

    public function test_list_user_job_transactions_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/1/jobs/0/transactions');

        $response->assertStatus(404);
    }

    public function test_list_user_job_transactions_success(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/1/jobs/2/transactions');

        $response->assertStatus(200);
    }

    public function test_show_user_job_transaction_success(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/show_transaction/1');

        $response->assertStatus(200);
    }

    public function test_update_user_job_transaction_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/users/0/jobs/1/transactions/1');

        $response->assertStatus(404);
    }

    public function test_update_user_job_transaction_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/users/1/jobs/0/transactions/1');

        $response->assertStatus(404);
    }

    public function test_update_user_job_transaction_tx_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/users/1/jobs/2/transactions/0');

        $response->assertStatus(404);
    }

    public function test_update_user_job_transaction_success(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PUT', '/api/users/1/jobs/2/transactions/1');

        $response->assertStatus(200);
    }

    public function test_get_user_job_transaction_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/0/jobs/1/transactions/1');

        $response->assertStatus(404);
    }

    public function test_get_user_job_transaction_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/jobs/0/transactions/1');

        $response->assertStatus(404);
    }

    public function test_get_user_job_transaction_tx_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/jobs/2/transactions/0');

        $response->assertStatus(404);
    }

    public function test_get_user_job_transaction_success(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/jobs/2/transactions/1');

        $response->assertStatus(200);
    }

    public function test_delete_user_job_transaction_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/0/jobs/1/transactions/1');

        $response->assertStatus(404);
    }

    public function test_delete_user_job_transaction_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/1/jobs/0/transactions/1');

        $response->assertStatus(404);
    }

    public function test_delete_user_job_transaction_tx_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/1/jobs/2/transactions/0');
        
        $response->assertStatus(404);
    }

    public function test_delete_user_job_transaction_success(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/1/jobs/2/transactions/1');

        $response->assertStatus(200);
    }
}
