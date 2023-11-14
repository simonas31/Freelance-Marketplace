<?php

namespace Tests\Feature;

use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JobsApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_get_jobs_with_params_no_data_found(): void
    {
        Job::truncate();
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/jobs', []);

        $response->assertStatus(200);
    }

    public function test_get_jobs_with_params_data_found(): void
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
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/jobs', [
            'payFrom' => 1,
            'payTo' => 100000000,
            'selectedWorkFields' => ['IT' => 'IT']
        ]);

        $response->assertStatus(200);
    }

    public function test_store_job_fail_validation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/1/jobs', []);

        $response->assertStatus(406);
    }

    public function test_store_job_success(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/1/jobs', [
            'description' => $this->faker()->text(),
            'work_fields' => $this->faker()->text(),
            'job_title' => $this->faker()->text(100),
            'pay_amount' => $this->faker()->numberBetween(100, 10000),
            'posted_time' => now(),
            'user_id' => 1,
        ]);

        $response->assertStatus(200);
    }

    public function test_show_user_job_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/show_job/0');

        $response->assertStatus(404);
    }

    public function test_show_user_job_job_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/show_job/1');

        $response->assertStatus(200)->assertJson([
            'user_id' => 1,
            'transaction_id' => -1,
            'creation_confirmed' => 0,
            'finished' => 0
        ]);
    }

    public function test_update_job_fail_validation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/1/jobs/1', []);

        $response->assertStatus(406);
    }

    public function test_update_job_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/0/jobs/1', [
            'transaction_id' => 1
        ]);

        $response->assertStatus(404);
    }

    public function test_update_job_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/1/jobs/0', [
            'transaction_id' => 1
        ]);

        $response->assertStatus(404);
    }

    public function test_update_job_success(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/1/jobs/1', [
            'transaction_id' => 1
        ]);

        $response->assertStatus(200);
    }

    public function test_user_job_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/0/jobs/1');

        $response->assertStatus(404);
    }

    public function test_user_job_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/1/jobs/0');

        $response->assertStatus(404);
    }

    public function test_user_job_job_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/1/jobs/1');

        $response->assertStatus(200);
    }

    public function test_list_user_jobs_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/0/jobs');

        $response->assertStatus(404);
    }

    public function test_list_user_jobs_job_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/1/jobs');

        $response->assertStatus(200);
    }

    public function test_confirm_job_creation(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('PATCH', '/api/jobs/1');

        $response->assertStatus(200);
    }

    public function test_list_user_jobs_job_not_found(): void
    {
        Job::truncate();
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/1/jobs');

        $response->assertStatus(404);
    }

    public function test_delete_job_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/0/jobs/1');

        $response->assertStatus(404);
    }

    public function test_delete_job_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/1/jobs/0');

        $response->assertStatus(404);
    }

    public function test_delete_job_success(): void
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
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/1/jobs/' . $job->id);

        $response->assertStatus(200);
    }
}
