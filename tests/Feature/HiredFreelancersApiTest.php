<?php

namespace Tests\Feature;

use App\Models\HiredFreelancer;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HiredFreelancersApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_list_user_job_hired_freelancers_no_freelancer_found(): void
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
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/1/jobs/1/hiredfreelancers');

        $response->assertStatus(404);
    }

    public function test_list_user_job_hired_freelancers(): void
    {
        $hf = HiredFreelancer::create([
            'client_id' => 1,
            'job_id' => 1,
            'freelancer_id' => 2,
            'hire_date' => now()
        ]);
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/1/jobs/1/hiredfreelancers');

        $response->assertStatus(200);
    }

    public function test_list_user_job_hired_freelancers_no_user_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/0/jobs/1/hiredfreelancers');

        $response->assertStatus(404);
    }

    public function test_list_user_job_hired_freelancers_no_job_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/1/jobs/0/hiredfreelancers');

        $response->assertStatus(404);
    }

    public function test_store_hired_freelancers_fail_validator(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/1/jobs/1/hiredfreelancers', []);

        $response->assertStatus(406);
    }

    public function test_store_hired_freelancer_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/0/jobs/1/hiredfreelancers', [
            'freelancer_id' => 2
        ]);

        $response->assertStatus(404);
    }

    public function test_store_hired_freelancers_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/1/jobs/0/hiredfreelancers', [
            'freelancer_id' => 2
        ]);

        $response->assertStatus(404);
    }

    public function test_store_hired_freelancer(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('POST', '/api/users/1/jobs/1/hiredfreelancers', [
            'freelancer_id' => 2,
        ]);

        $response->assertStatus(200);
    }

    public function test_show_hired_freelancers_hf_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/show_hf/0');

        $response->assertStatus(404);
    }

    public function test_show_hired_freelancer(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/show_hf/1');

        $response->assertStatus(200);
    }

    public function test_update_hired_freelancer_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/0/jobs/1/hiredfreelancers/1');

        $response->assertStatus(404);
    }

    public function test_update_hired_freelancer_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/1/jobs/0/hiredfreelancers/1');

        $response->assertStatus(404);
    }

    public function test_update_hired_freelancer_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/1/jobs/1/hiredfreelancers/0');

        $response->assertStatus(404);
    }

    public function test_update_hired_freelancer(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('PUT', '/api/users/1/jobs/1/hiredfreelancers/1');

        $response->assertStatus(200);
    }

    public function test_user_job_hired_freelancer_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/0/jobs/1/hiredfreelancers/1');

        $response->assertStatus(404);
    }

    public function test_user_job_hired_freelancer_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/1/jobs/0/hiredfreelancers/1');

        $response->assertStatus(404);
    }

    public function test_user_job_hired_freelancer_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('GET', '/api/users/1/jobs/1/hiredfreelancers/0');

        $response->assertStatus(404);
    }

    public function test_user_job_hired_freelancer(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('GET', '/api/users/1/jobs/1/hiredfreelancers/1');

        $response->assertStatus(200);
    }

    public function test_destroy_hired_freelancer_user_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('DELETE', '/api/users/0/jobs/1/hiredfreelancers/1');

        $response->assertStatus(404);
    }

    public function test_destroy_hired_freelancer_job_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('DELETE', '/api/users/1/jobs/0/hiredfreelancers/1');

        $response->assertStatus(404);
    }

    public function test_destroy_hired_freelancer_not_found(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . JWT_TOKEN)->json('DELETE', '/api/users/1/jobs/1/hiredfreelancers/0');

        $response->assertStatus(404);
    }

    public function test_destroy_hired_freelancer(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer '. JWT_TOKEN)->json('DELETE', '/api/users/1/jobs/1/hiredfreelancers/1');

        $response->assertStatus(200);
    }
}
